<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormPengajuan extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'form_pengajuan';
    //public $incrementing = false;

    protected $fillable = ['user_id', 'jenis_pengajuan', 'tgl_mulai', 'tgl_berakhir', 'lampiran', 'catatan', 'status', 'catatan_admin'];

    protected $dates = ['tgl_mulai', 'tgl_berakhir'];

    /**
     * Get the user that owns the FormPengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
