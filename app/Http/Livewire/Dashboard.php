<?php

namespace App\Http\Livewire;

use App\Models\DataAbsen;
use App\Models\DataKaryawan;
use App\Models\JadwalAbsen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Str;

class Dashboard extends Component
{
    public $jadwal_id;
    public $jam_absen;
    public function render()
    {
        $role  = auth()->user()->role->role_type;
        $col = 12;
        $data_absens = DataAbsen::whereIn('status_absen', [1, 5])->get();
        $cuti = 0;
        $sisa_cuti = 0;
        $izin = 0;
        $sakit = 0;
        $total_karyawan = DataKaryawan::count();
        $total_izin = DataAbsen::where('status_absen', 2)->groupBy('user_id')->get();
        $total_cuti =  DataAbsen::where('status_absen', 3)->groupBy('user_id')->get();
        $total_sakit = DataAbsen::where('status_absen', 4)->groupBy('user_id')->get();
        if ($role === 'member') {
            $data_absens = DataAbsen::where('user_id', auth()->user()->id)->whereIn('status_absen', [1, 5])->get();
            $col = 7;
            $cuti = DataAbsen::where('user_id', auth()->user()->id)->where('status_absen', 3)->count();
            $sisa_cuti = DataAbsen::where('user_id', auth()->user()->id)->where(['status_absen' => 3, 'status_perizinan' => 0])->count();
            $izin = DataAbsen::where('user_id', auth()->user()->id)->where(['status_absen' => 2, 'status_perizinan' => 1])->count();
            $sakit = DataAbsen::where('user_id', auth()->user()->id)->where(['status_absen' => 4, 'status_perizinan' => 1])->count();
        }
        return view('livewire.dashboard', [
            'jadwal_absens' => JadwalAbsen::all(),
            'data_absens' => $data_absens,
            'role' => $role,
            'col' => $col,
            'cuti' => $cuti,
            'sisa_cuti' => $sisa_cuti,
            'izin' => $izin,
            'sakit' => $sakit,
            'total_karyawan' => $total_karyawan,
            'total_izin' => count($total_izin),
            'total_cuti' => count($total_cuti),
            'total_sakit' => count($total_sakit),
        ]);
    }

    public function showModalAbsen($jadwal_id)
    {
        $data_absen = DataAbsen::where('jadwal_absen_id', 1)->whereDate('created_at', date('Y-m-d'))->first();
        $this->jadwal_id = $jadwal_id;
        $this->jam_absen = date('Y-m-d H:i:s');
        if ($data_absen) {
            return $this->emit('showModalAbsen', 'show');
        } else {
            if ($jadwal_id == 1) {
                return $this->emit('showModalAbsen', 'show');
            }
            $this->emit('showAlertError', ['msg' => 'Anda belum absen masuk']);
        }
    }

    public function takePhoto()
    {
        $this->emit('showModalWebcam', 'take');
    }

    public function simpanAbsen()
    {
        // create image
        try {
            DB::beginTransaction();

            // jadwal
            $jadwal_absen = JadwalAbsen::find($this->jadwal_id);
            $jam_absen = $this->jam_absen;
            $waktu_absen = date('Y-m-d ') . $jadwal_absen->waktu_absen;
            $status_absen = 1;
            $minutes_late = 0;
            if (strtotime($jam_absen) > strtotime($waktu_absen)) {
                $status_absen = 5;
                $minutes_late = (strtotime($jam_absen) - strtotime($waktu_absen)) / 60;
            }

            $data = [
                'user_id'  => auth()->user()->id,
                'jadwal_absen_id'  => $jadwal_absen->id,
                'waktu_absen'  => $jam_absen,
                // 'foto_absen'  => $image,
                'status_absen'  => $status_absen,
                'status_perizinan'  => 1,
                'note'  => $minutes_late > 0 ? 'Terlambat ' . $minutes_late . ' menit' : '',
            ];

            DataAbsen::create($data);
            $this->jadwal_id = null;
            $this->emit('closeModal');
            $this->emit('showModalAbsen', 'hide');
            DB::commit();

            if ($minutes_late > 0) {
                $this->emit('showAlert', ['msg' => $jadwal_absen->nama_jadwal . ' Absen Berhasil, Anda Terlambat ' . $minutes_late . ' Menit']);
            } else {
                $this->emit('showAlert', ['msg' => $jadwal_absen->nama_jadwal . ' Absen Berhasil']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('showAlertError', ['msg' => $jadwal_absen->nama_jadwal . ' Absen Gagal']);
        }
    }



    public function cancel()
    {
        $this->emit('closeModal');
        $this->emit('showModalAbsen', 'hide');
    }
}
