@if ($aktif == false)
<div class="text-center">
    <span class="text text-muted">Semua karyawan telah memiliki jatah cuti pada tahun ini</span>
</div>
@else
{!! Form::model($data, [
'route' => ['master-cuti.store'],
'method' => 'POST'
]) !!}
<div class="form-group">
    <label>Nama</label>
    {!!Form::select('id_user',$karyawan, null, ['class' => 'form-control custom-select', 'id' => 'id_user'])!!}
</div>
<div class="form-group">
    <label>Tahun</label>
    {!!Form::text('tahun', date('Y'), ['class' => 'form-control', 'id' => 'tahun', 'readonly'])!!}
</div>

<div class="form-group">
    <label>Sisa Cuti (Hari)</label>
    {!!Form::text('sisa_cuti', null, ['class' => 'form-control', 'id' => 'sisa_cuti'])!!}
</div>

{!! Form::close() !!}
@endif