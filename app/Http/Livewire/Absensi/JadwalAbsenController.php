<?php

namespace App\Http\Livewire\Absensi;

use App\Models\JadwalAbsen;
use Livewire\Component;


class JadwalAbsenController extends Component
{

    public $jadwal_absen_id;
    public $nama_jadwal;
    public $jam_absen;



    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataJadwalAbsenById', 'getJadwalAbsenId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.absensi.jadwal-absen', [
            'items' => JadwalAbsen::all()
        ]);
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'nama_jadwal'  => $this->nama_jadwal,
            'jam_absen'  => $this->jam_absen,
        ];

        JadwalAbsen::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'nama_jadwal'  => $this->nama_jadwal,
            'jam_absen'  => $this->jam_absen,
        ];
        $row = JadwalAbsen::find($this->jadwal_absen_id);



        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        JadwalAbsen::find($this->jadwal_absen_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama_jadwal'  => 'required',
            'jam_absen'  => 'required',
        ];

        return $this->validate($rule);
    }

    public function getDataJadwalAbsenById($jadwal_absen_id)
    {
        $this->_reset();
        $row = JadwalAbsen::find($jadwal_absen_id);
        $this->jadwal_absen_id = $row->id;
        $this->nama_jadwal = $row->nama_jadwal;
        $this->jam_absen = $row->jam_absen;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getJadwalAbsenId($jadwal_absen_id)
    {
        $row = JadwalAbsen::find($jadwal_absen_id);
        $this->jadwal_absen_id = $row->id;
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
        $this->jadwal_absen_id = null;
        $this->nama_jadwal = null;
        $this->jam_absen = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
