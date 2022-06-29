<?php

namespace App\Http\Livewire\Absensi;

use App\Models\DataAbsen;
use App\Models\FormPengajuan;
use DateInterval;
use DatePeriod;
use DateTime;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class FormPengajuanController extends Component
{
    use WithFileUploads;
    public $form_pengajuan_id;
    public $user_id;
    public $jenis_pengajuan;
    public $tgl_mulai;
    public $tgl_berakhir;
    public $lampiran;
    public $catatan;
    public $catatan_admin;
    public $status;
    public $lampiran_path;
    public $hasConfirm = false;


    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;
    public $role;

    protected $listeners = ['getDataFormPengajuanById', 'getFormPengajuanId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
        $this->role = auth()->user()->role->role_type;
    }

    public function render()
    {
        return view('livewire.absensi.form-pengajuan', [
            'items' => FormPengajuan::all(),
            'role' => auth()->user()->role
        ]);
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'user_id'  => auth()->user()->id,
            'jenis_pengajuan'  => $this->jenis_pengajuan,
            'tgl_mulai'  => $this->tgl_mulai,
            'catatan'  => $this->catatan,
        ];

        if (in_array($this->role, ['superadmin', 'admin'])) {
            $data = [
                'catatan_admin'  => $this->catatan_admin,
                'status'  => $this->status,
            ];
        }

        if (in_array($this->role, ['member'])) {
            if (in_array($this->jenis_pengajuan, ['izin', 'cuti'])) {
                if (strtotime($this->tgl_berakhir) < strtotime($this->tgl_mulai)) {
                    return $this->addError('tgl_berakhir', 'Tanggal Berakhir tidak sesuai');
                }
                $data['tgl_berakhir'] = $this->tgl_berakhir;
            } else if (in_array($this->jenis_pengajuan, ['izin', 'sakit'])) {
                if ($this->lampiran_path) {
                    $lampiran = $this->lampiran_path->store('upload', 'public');
                    $data['lampiran'] = $lampiran;
                }
            }
        }

        FormPengajuan::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'user_id'  => auth()->user()->id,
            'jenis_pengajuan'  => $this->jenis_pengajuan,
            'tgl_mulai'  => $this->tgl_mulai,
            'tgl_berakhir'  => $this->tgl_berakhir,
            'catatan'  => $this->catatan,
        ];

        if (in_array($this->role, ['superadmin', 'admin'])) {
            $data = [
                'status'  => $this->status,
            ];

            if ($this->status == 2) {
                $data['catatan_admin'] = $this->catatan_admin;
            }

            if (in_array($this->jenis_pengajuan, ['izin', 'cuti'])) {
                $periods = new DatePeriod(
                    new DateTime($this->tgl_mulai),
                    new DateInterval('P1D'),
                    new DateTime($this->tgl_berakhir)
                );
                $masuk = [];
                $pulang = [];

                foreach ($periods as $key => $value) {
                    $perizinan = $this->jenis_pengajuan  === 'izin' ? 1 : 0;
                    $masuk[] = [
                        'jadwal_absen_id' => 1,
                        'waktu_absen' => date('Y-m-d H:i:s', strtotime($value->format('Y-m-d'))),
                        'status_absen' => $this->jenis_pengajuan  === 'izin' ? 2 : 3,
                        'status_perizinan' => $value->format('Y-m-d') == date('Y-m-d') ? 1 : $perizinan,
                        'user_id' => $this->user_id,
                    ];
                    $pulang[] = [
                        'jadwal_absen_id' => 4,
                        'waktu_absen' => date('Y-m-d H:i:s', strtotime($value->format('Y-m-d'))),
                        'status_absen' => $this->jenis_pengajuan  === 'izin' ? 2 : 3,
                        'status_perizinan' => $value->format('Y-m-d') == date('Y-m-d') ? 1 : $perizinan,
                        'user_id' => $this->user_id
                    ];
                }

                DataAbsen::insert($masuk);
                DataAbsen::insert($pulang);
            }
        }

        if (in_array($this->role, ['member'])) {
            if (in_array($this->jenis_pengajuan, ['izin', 'cuti'])) {
                $data['tgl_berakhir'] = $this->tgl_berakhir;
            } else if (in_array($this->jenis_pengajuan, ['izin', 'sakit'])) {
                if ($this->lampiran_path) {
                    $lampiran = $this->lampiran_path->store('upload', 'public');
                    $data = ['lampiran' => $lampiran];
                    if (Storage::exists('public/' . $this->lampiran)) {
                        Storage::delete('public/' . $this->lampiran);
                    }
                }
            }
        }



        $row = FormPengajuan::find($this->form_pengajuan_id);
        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        FormPengajuan::find($this->form_pengajuan_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'jenis_pengajuan'  => 'required',
            'tgl_mulai'  => 'required',
            'tgl_berakhir'  => 'required',
        ];
        if (in_array($this->role, ['superadmin', 'admin'])) {
            $rule = [
                'status'  => 'required'
            ];

            if ($this->status == 2) {
                $rule['catatan_admin'] = 'required';
            }
        }

        if (in_array($this->role, ['member'])) {
            if (in_array($this->jenis_pengajuan, ['izin', 'cuti'])) {
                $rule['tgl_berakhir'] = 'required';
            }
        }


        return $this->validate($rule);
    }

    public function getDataFormPengajuanById($form_pengajuan_id)
    {
        $this->_reset();
        $row = FormPengajuan::find($form_pengajuan_id);
        $this->form_pengajuan_id = $row->id;
        $this->user_id = $row->user_id;
        $this->jenis_pengajuan = $row->jenis_pengajuan;
        $this->tgl_mulai = $row->tgl_mulai;
        $this->tgl_berakhir = $row->tgl_berakhir;
        $this->lampiran = $row->lampiran;
        $this->catatan = $row->catatan;
        $this->catatan_admin = $row->catatan_admin;
        $this->status = $row->status;
        $this->hasConfirm = $row->status > 0;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getFormPengajuanId($form_pengajuan_id)
    {
        $row = FormPengajuan::find($form_pengajuan_id);
        $this->form_pengajuan_id = $row->id;
    }

    public function toggleForm($form)
    {
        $this->_reset();
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->_reset();
        $this->emit('showModal');
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->form_pengajuan_id = null;
        $this->user_id = null;
        $this->jenis_pengajuan = null;
        $this->tgl_mulai = null;
        $this->tgl_berakhir = null;
        $this->lampiran_path = null;
        $this->catatan_admin = null;
        $this->catatan = null;
        $this->status = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
