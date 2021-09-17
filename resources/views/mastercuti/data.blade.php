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
    <h1 class="h3 mb-0 text-gray-800">Data Cuti</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('home')}}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Master Cuti</li>
    </ol>
</div>

<!-- Row -->
<div class="row">
    <!-- DataTable with Hover -->
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">
                <a href="{{route('master-cuti.create')}}" class="btn btn-primary btn-sm modal-show"
                    title="Tambah Data Cuti">Tambah</a>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="table">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Tahun</th>
                            <th>Sisa Cuti</th>
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
            ajax: "data/master-cuti",
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'name'
                },
                {
                    data: 'tahun'
                },
                {
                    data: 'sisa_cuti'
                }
            ],
            columnDefs: [{
                targets: [0],
                orderable: false,
                searchable: false
            }]
        })
    });
</script>
@endpush