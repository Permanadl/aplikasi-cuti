<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    protected $table = 'pengajuan_cuti';
    protected $primaryKey = 'nomor';
    public $incrementing = false;

    protected $fillable = [
        'nomor',
        'id_user',
        'user_persetujuan',
        'tanggal_awal',
        'tanggal_akhir',
        'keterangan',
        'ket_persetujuan',
        'jumlah_cuti',
        'status_persejutuan',
        'tanggal_persejutuan'
    ];
}
