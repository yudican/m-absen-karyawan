<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKaryawan extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'data_karyawan';
    //public $incrementing = false;

    protected $fillable = [
        'nik', 'telepon', 'alamat', 'tgl_masuk', 'jabatan', 'tgl_lahir',
        'jenis_kelamin', 'user_id'
    ];

    protected $dates = ['tgl_masuk'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
