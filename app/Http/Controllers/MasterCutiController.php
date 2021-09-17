<?php

namespace App\Http\Controllers;

use App\MasterCuti;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MasterCutiController extends Controller
{
    public function index()
    {
        return view('mastercuti.data');
    }

    public function create()
    {
        $data = new MasterCuti();
        $user = User::select(
            'users.id',
            'users.name'
        )
            ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->where('roles.name', 'karyawan')
            ->whereNotIn('users.id', DB::table('master_cuti')->select('id_user')->where('tahun', date('Y')))
            ->get();
        $karyawan = [];
        foreach ($user as $user) {
            $karyawan[$user->id] = $user->name;
        }

        if (count($karyawan) == 0) {
            $aktif = false;
        } else {
            $aktif = true;
        }

        return view('mastercuti.form', compact('data', 'karyawan', 'aktif'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'sisa_cuti' => 'required|numeric|min:1'
            ],
            [
                'sisa_cuti.required' => 'Sisa cuti wajib diisi',
                'sisa_cuti.numeric' => 'Pastikan hanya memasukan angka',
                'sisa_cuti.min' => 'Minimal 1 hari'
            ]
        );

        $data = MasterCuti::create([
            'id_user' => $request->id_user,
            'tahun' => $request->tahun,
            'sisa_cuti' => $request->sisa_cuti
        ]);
        return $data;
    }

    public function getData()
    {
        $data = MasterCuti::join('users', 'users.id', 'master_cuti.id_user')
            ->select(
                'users.name',
                'master_cuti.tahun',
                'master_cuti.sisa_cuti'
            )
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
