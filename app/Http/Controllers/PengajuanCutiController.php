<?php

namespace App\Http\Controllers;

use App\MasterCuti;
use App\PengajuanCuti;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PengajuanCutiController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('karyawan')) {
            $data = PengajuanCuti::where('id_user', Auth::user()->id)
                ->orderBy('nomor', 'desc');

            $master = MasterCuti::where('id_user', Auth::user()->id)
                ->where('tahun', date('Y'));

            if ($master->count() == 0) {
                $status = false;
                $pesan = 'Anda tidak dapat mengajukan cuti, HRD belum menambahkan data master cuti anda!';
                return view('pengajuan.data', compact('status', 'pesan'));
            }

            if ($data->count() > 0) {
                $data = $data->first();
                if ($data->status_persetujuan == 0) {
                    $status = true;
                    $pesan = null;
                } elseif ($data->status_persetujuan == 1) {
                    $status = $data->tanggal_akhir > date('Y-m-d') ? false : true;
                    $pesan = $status == false ? 'Anda tidak dapat mengajukan cuti, masa cuti anda belum berakhir' : null;
                } else {
                    $status = true;
                    $pesan = null;
                }
            } else {
                $status = true;
                $pesan = null;
            }
            return view('pengajuan.data', compact('status', 'pesan'));
        }

        return view('pengajuan.data');
    }

    public function create()
    {
        $data = new PengajuanCuti();
        $pengajuan = PengajuanCuti::max('nomor');
        if ($pengajuan !== null) {
            $pengajuan = (int) substr($pengajuan, 6) + 1;
            $nomor = 'PC' . date('ym') . sprintf("%05d", $pengajuan);
        } else {
            $nomor = 'PC' . date('ym') . '00001';
        }

        $cuti = MasterCuti::where('id_user', Auth::user()->id)
            ->where('tahun', date('Y'));
        if ($cuti->count() == 0) {
            $sisa_cuti = 12;
        } else {
            $sisa_cuti = $cuti->first();
            $sisa_cuti = $sisa_cuti->sisa_cuti;
        }
        return view('pengajuan.form', compact('data', 'nomor', 'sisa_cuti'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
                'jumlah_cuti' => 'numeric|min:1|max:' . $request->sisa_cuti,
                'keterangan' => 'required'
            ],
            [
                'tanggal_awal.required' => 'Tentukan tanggal awal',
                'tanggal_akhir.required' => 'Tentukan tanggal akhir',
                'jumlah_cuti.number' => 'Terjadi kesalahan dalam penentuan tanggal cuti',
                'jumlah_cuti.min' => 'Terjadi kesalahan dalam penentuan tanggal cuti',
                'jumlah_cuti.max' => 'Jumlah cuti melebihi sisa cuti',
                'keterangan.required' => 'Berikan keterangan/alasan'
            ]
        );


        $data = PengajuanCuti::create([
            'nomor' => $request->nomor,
            'id_user' => Auth::user()->id,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'jumlah_cuti' => $request->jumlah_cuti,
            'keterangan' => $request->keterangan
        ]);
        return $data;
    }

    public function show($id)
    {
        $data = PengajuanCuti::join('users', 'users.id', 'pengajuan_cuti.id_user')
            ->where('pengajuan_cuti.nomor', $id)
            ->first();
        return view('pengajuan.detail', compact('data'));
    }

    public function approval($id)
    {
        $data = PengajuanCuti::join('users', 'users.id', 'pengajuan_cuti.id_user')
            ->where('pengajuan_cuti.nomor', $id)
            ->first();
        return view('pengajuan.approve', compact('data'));
    }

    public function setApproval(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'status_persetujuan' => 'required',
                'ket_persetujuan' => 'required'
            ],
            [
                'status_persetujuan.required' => 'Berikan keputusan',
                'ket_persetujuan.required' => 'Berikan keterangan'
            ]
        );

        DB::beginTransaction();
        try {
            $data = PengajuanCuti::findOrFail($id);
            $data->status_persetujuan = $request->status_persetujuan;
            $data->user_persetujuan = Auth::user()->id;
            $data->tanggal_persetujuan = date('Y-m-d');
            $data->ket_persetujuan = $request->ket_persetujuan;
            $data->updated_at = now();
            $data->save();

            if ($request->status_persetujuan == 1) {
                $cuti = MasterCuti::where('id_user', $data->id_user)
                    ->where('tahun', date('Y'))
                    ->first();
                $cuti->sisa_cuti = $cuti->sisa_cuti - $data->jumlah_cuti;
                $cuti->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 422);
        }
    }

    public function getData()
    {
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('hrd')) {
            $data = PengajuanCuti::select(
                'pengajuan_cuti.nomor',
                'pengajuan_cuti.tanggal_awal',
                'pengajuan_cuti.tanggal_akhir',
                'pengajuan_cuti.jumlah_cuti',
                'pengajuan_cuti.keterangan',
                'pengajuan_cuti.status_persetujuan',
                'users.name'
            )
                ->join('users', 'pengajuan_cuti.id_user', 'users.id')
                ->get();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    if ($data->status_persetujuan == null) {
                        return '<a href="' . route('pengajuan.show', $data->nomor) . '" class="btn btn-info btn-sm modal-show" title="Detail Pengajuan">Detail</a>
                        <a href="' . route('approval', $data->nomor) . '" class="btn btn-primary btn-sm modal-show" title="Persetujuan">Approve</a>';
                    } else {
                        return '<a href="' . route('pengajuan.show', $data->nomor) . '" class="btn btn-info btn-sm modal-show" title="Detail Pengajuan">Detail</a>';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        } elseif ($user->hasRole('karyawan')) {
            $data = PengajuanCuti::select(
                'pengajuan_cuti.nomor',
                'pengajuan_cuti.tanggal_awal',
                'pengajuan_cuti.tanggal_akhir',
                'pengajuan_cuti.jumlah_cuti',
                'pengajuan_cuti.keterangan',
                'pengajuan_cuti.status_persetujuan',
                'users.name'
            )
                ->join('users', 'pengajuan_cuti.id_user', 'users.id')
                ->where('users.id', Auth::user()->id)
                ->get();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    return '<a href="' . route('pengajuan.show', $data->nomor) . '" class="btn btn-info btn-sm modal-show" title="Detail Pengajuan">Detail</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            return response()->json(['Pesan' => 'Anda tidak memiliki akses ke halaman ini']);
        }
    }
}
