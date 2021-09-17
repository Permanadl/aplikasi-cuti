<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('users.data');
    }

    public function create()
    {
        $data = new User();
        $roles = DB::table('roles')->get();
        $select = [];
        foreach ($roles as $roles) {
            $select[$roles->id] = $roles->name;
        }

        return view('users.form', compact('data', 'select'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'confirm' => 'required|same:password'
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email salah',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'confirm.required' => 'Konfirmasi password wajib diisi',
                'confirm.same' => 'Password berbeda'
            ]
        );

        DB::beginTransaction();

        try {
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $data->assignRole($request->roles);

            DB::commit();

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 422);
        }
    }

    public function getData()
    {
        $data = User::select(
            'users.name',
            'users.email',
            'roles.name as role_name'
        )
            ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->get();
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return view('layouts.action', [
                    'data' => $data
                ]);
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }
}
