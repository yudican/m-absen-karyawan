<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAbsen extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'jadwal_absen';

    //public $incrementing = false;

    protected $fillable = ['nama_jadwal', 'jam_absen'];

    /**
     * Get all of the dataAbsen for the JadwalAbsen
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dataAbsen()
    {
        return $this->hasMany(DataAbsen::class);
    }

    public function hasAbsen($jadwal_id)
    {
        return $this->dataAbsen()->where('jadwal_absen_id', $jadwal_id)->whereDate('created_at', date('Y-m-d'))->count();
    }
}
