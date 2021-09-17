@extends('layouts.app')
@push('css')
<link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<style>
    .table {
        font-size: 14px;
    }
</style>
@endpush
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('home')}}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
    </ol>
</div>

<!-- Row -->
<div class="row">
    <!-- DataTable with Hover -->
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <a href="{{route('pengguna.create')}}" class="btn btn-primary btn-sm modal-show"
                    title="Tambah Pengguna">Tambah</a>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="table">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Hak Akses</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!--Row-->
@endsection
@push('js')
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $.fn.dataTable.render.roles = function(data) {
            return function(data) {
                if (data == 'karyawan') {
                    return '<span class="badge badge-info">karyawan</span>'
                } else if (data == 'hrd') {
                    return '<span class="badge badge-primary">hrd</span>'
                }
            }
        }
        $('#table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [1, 'asc'],
            ajax: "data/pengguna",
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'role_name',
                    render: $.fn.dataTable.render.roles()
                },
                {
                    data: 'action'
                }
            ],
            columnDefs: [{
                targets: [0, -1],
                orderable: false,
                searchable: false
            }]
        })
    });
</script>
@endpush