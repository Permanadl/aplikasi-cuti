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
    <h1 class="h3 mb-0 text-gray-800">Data Pengajuan</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('home')}}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengajuan Cuti</li>
    </ol>
</div>

<!-- Row -->
<div class="row">
    <!-- DataTable with Hover -->
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">
                @role('karyawan')
                @if ($status == true)
                <a href="{{route('pengajuan.create')}}" class="btn btn-primary btn-sm modal-show"
                    title="Ajukan Cuti">Ajukan Cuti</a>
                @else
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            {{$pesan}}
                        </div>
                    </div>
                </div>
                @endif

                @endrole
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Nomor</th>
                            <th>Karyawan</th>
                            <th>Tanggal Awal</th>
                            <th>Tanggal Akhir</th>
                            <th>Jumlah Cuti</th>
                            <th>Keterangan</th>
                            <th>Status</th>
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
        $.fn.dataTable.render.status = function(data) {
            return function(data) {
                if (data == null) {
                    return '<span class="badge badge-info">proses</span>'
                } else if (data == '0') {
                    return '<span class="badge badge-danger">ditolak</span>'
                }else if(data == '1'){
                    return '<span class="badge badge-success">disetujui</span>'
                }
            }
        }
        $('#table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [1, 'asc'],
            ajax: "data/pengajuan",
            columns: [{
                    data: 'nomor'
                },
                {
                    data: 'name'
                },
                {
                    data: 'tanggal_awal'
                },
                {
                    data: 'tanggal_akhir'
                },
                {
                    data: 'jumlah_cuti'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'status_persetujuan',
                    render: $.fn.dataTable.render.status()
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