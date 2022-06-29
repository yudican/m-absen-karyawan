<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAbsen extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'data_absen';

    //public $incrementing = false;

    protected $fillable = ['user_id', 'jadwal_absen_id', 'waktu_absen', 'foto_absen', 'status_absen', 'status_perizinan', 'note'];

    protected $dates = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalAbsen()
    {
        return $this->belongsTo(JadwalAbsen::class);
    }
}
