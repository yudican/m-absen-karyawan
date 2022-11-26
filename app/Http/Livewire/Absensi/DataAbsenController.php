<?php

namespace App\Http\Livewire\Absensi;

use App\Models\DataAbsen;
use Livewire\Component;


class DataAbsenController extends Component
{

    public $data_absen_id;
    public $user_id;
    public $jadwal_absen_id;
    public $waktu_absen;
    public $foto_absen;
    public $status_absen;
    public $status_perizinan;



    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataDataAbsenById', 'getDataAbsenId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.absensi.data-absen', [
            'items' => DataAbsen::all()
        ]);
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'user_id'  => $this->user_id,
            'jadwal_absen_id'  => $this->jadwal_absen_id,
            'waktu_absen'  => $this->waktu_absen,
            'foto_absen'  => $this->foto_absen,
            'status_absen'  => $this->status_absen,
            'status_perizinan'  => $this->status_perizinan
        ];

        DataAbsen::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'user_id'  => $this->user_id,
            'jadwal_absen_id'  => $this->jadwal_absen_id,
            'waktu_absen'  => $this->waktu_absen,
            'foto_absen'  => $this->foto_absen,
            'status_absen'  => $this->status_absen,
            'status_perizinan'  => $this->status_perizinan
        ];
        $row = DataAbsen::find($this->data_absen_id);



        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        DataAbsen::find($this->data_absen_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'user_id'  => 'required',
            'jadwal_absen_id'  => 'required',
            'waktu_absen'  => 'required',
            'foto_absen'  => 'required',
            'status_absen'  => 'required',
            'status_perizinan'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataDataAbsenById($data_absen_id)
    {
        $this->_reset();
        $row = DataAbsen::find($data_absen_id);
        $this->data_absen_id = $row->id;
        $this->user_id = $row->user_id;
        $this->jadwal_absen_id = $row->jadwal_absen_id;
        $this->waktu_absen = $row->waktu_absen;
        $this->foto_absen = $row->foto_absen;
        $this->status_absen = $row->status_absen;
        $this->status_perizinan = $row->status_perizinan;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getDataAbsenId($data_absen_id)
    {
        $row = DataAbsen::find($data_absen_id);
        $this->data_absen_id = $row->id;
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
        $this->data_absen_id = null;
        $this->user_id = null;
        $this->jadwal_absen_id = null;
        $this->waktu_absen = null;
        $this->foto_absen = null;
        $this->status_absen = null;
        $this->status_perizinan = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }

    public function setFilter()
    {
        $this->validate(
            [
                'startDate' => 'required',
                'endDate' => 'required',
            ]
        );

        if (strtotime($this->startDate) > strtotime($this->endDate)) {
            return $this->emit('showAlert', ['msg' => 'Tanggal awal tidak boleh lebih besar dari tanggal akhir']);
        }

        $this->emit('setFilter', [$this->startDate, $this->endDate]);
    }
}
