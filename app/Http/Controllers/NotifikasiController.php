<?php

namespace App\Http\Controllers;

use App\PengajuanCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function hrd()
    {
        $data = PengajuanCuti::join('users', 'users.id', 'pengajuan_cuti.id_user')
            ->select(
                'users.name',
                'pengajuan_cuti.jumlah_cuti',
                'pengajuan_cuti.tanggal_awal',
                'pengajuan_cuti.tanggal_akhir'
            )
            ->where('pengajuan_cuti.status_persetujuan', NULL)
            ->get();
        return response()->json($data);
    }

    public function karyawan()
    {
        $data = PengajuanCuti::join('users', 'users.id', 'pengajuan_cuti.id_user')
            ->select(
                'users.name',
                'pengajuan_cuti.jumlah_cuti',
                'pengajuan_cuti.tanggal_awal',
                'pengajuan_cuti.tanggal_akhir',
                'pengajuan_cuti.status_persetujuan'
            )
            ->where('pengajuan_cuti.status_persetujuan', '!=', null)
            ->where('users.id', Auth::user()->id)
            ->orderBy('pengajuan_cuti.nomor', 'desc')
            ->limit(1)
            ->get();
        return response()->json($data);
    }
}
