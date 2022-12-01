<?php

namespace App\Http\Livewire\Absensi;

use App\Models\DataKaryawan;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;


class DataKaryawanController extends Component
{

    public $data_karyawan_id;
    public $nik;
    public $telepon;
    public $alamat;
    public $tgl_masuk;
    public $jabatan;
    public $tgl_lahir;
    public $jenis_kelamin;
    public $name;
    public $email;
    public $user_id;
    public $password;

    public $route_name = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataDataKaryawanById', 'getDataKaryawanId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.absensi.data-karyawan', [
            'items' => DataKaryawan::all()
        ]);
    }

    public function store()
    {
        $this->_validate();



        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->nik,
                'password' => Hash::make($this->password),
            ]);

            $data = [
                'nik'  => $this->nik,
                'telepon'  => $this->telepon,
                'alamat'  => $this->alamat,
                'tgl_masuk'  => $this->tgl_masuk,
                'tgl_lahir'  => $this->tgl_lahir,
                'jenis_kelamin'  => $this->jenis_kelamin,
                'jabatan'  => $this->jabatan,
                'user_id'  => $user->id,
            ];

            $team = Team::find(1);
            $team->users()->attach($user, ['role' => 'member']);
            $user->roles()->attach('0feb7d3a-90c0-42b9-be3f-63757088cb9a');
            DataKaryawan::create($data);
            DB::commit();
            $this->_reset();
            return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->_reset();
            // dd($th->getMessage());
            return $this->emit('showAlertError', ['msg' => 'Data Gagal Disimpan']);
        }
    }

    public function update()
    {
        $this->_validate();

        $user = User::find($this->user_id);
        $data = [
            'nik'  => $this->nik,
            'telepon'  => $this->telepon,
            'alamat'  => $this->alamat,
            'tgl_masuk'  => $this->tgl_masuk,
            'jabatan'  => $this->jabatan,
            'tgl_lahir'  => $this->tgl_lahir,
            'jenis_kelamin'  => $this->jenis_kelamin,
        ];

        $dataUser = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $dataUser['password'] = Hash::make($this->password);
        }

        $user->update($dataUser);

        $user->dataKaryawan()->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        User::find($this->user_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nik'  => 'required',
            'name'  => 'required',
            'email'  => 'required',
            'telepon'  => 'required',
            'alamat'  => 'required',
            'tgl_masuk'  => 'required',
            'jabatan'  => 'required'
        ];

        if (!$this->update_mode) {
            $rule['password'] = 'required';
        }

        return $this->validate($rule);
    }

    public function getDataDataKaryawanById($data_karyawan_id)
    {
        $this->_reset();
        $row = DataKaryawan::find($data_karyawan_id);
        $this->data_karyawan_id = $row->id;
        $this->nik = $row->nik;
        $this->telepon = $row->telepon;
        $this->alamat = $row->alamat;
        $this->tgl_masuk = date('Y-m-d', strtotime($row->tgl_masuk));
        $this->jabatan = $row->jabatan;
        $this->tgl_lahir = date('Y-m-d', strtotime($row->tgl_lahir));
        $this->jenis_kelamin = $row->jenis_kelamin;
        $this->name = $row->user->name;
        $this->email = $row->user->email;
        $this->user_id = $row->user_id;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getDataKaryawanId($data_karyawan_id)
    {
        $row = DataKaryawan::find($data_karyawan_id);
        $this->data_karyawan_id = $row->id;
        $this->user_id = $row->user_id;
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
        $this->data_karyawan_id = null;
        $this->nik = null;
        $this->telepon = null;
        $this->alamat = null;
        $this->tgl_masuk = null;
        $this->jabatan = null;
        $this->tgl_lahir = null;
        $this->jenis_kelamin = null;
        $this->name = null;
        $this->email = null;
        $this->user_id = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
