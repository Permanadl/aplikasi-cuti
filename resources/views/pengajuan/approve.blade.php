{!! Form::model($data, [
'route' => ['approve', $data->nomor],
'method' => 'POST'
]) !!}
<div class="form-group row">
    <div class="col-md-6">
        <label>Nama</label>
        {!!Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'readonly'])!!}
    </div>
    <div class="col-md-6">
        <label>Jumlah Cuti (Hari)</label>
        {!!Form::text('jumlah_cuti', null, ['class' => 'form-control', 'id' => 'jumlah_cuti', 'readonly'])!!}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6">
        <label>Tanggal Awal</label>
        {!!Form::date('tanggal_awal', null, ['class' => 'form-control', 'id' => 'tanggal_awal', 'readonly'])!!}
    </div>
    <div class="col-md-6">
        <label>Tanggal Akhir</label>
        {!!Form::date('tanggal_akhir', null, ['class' => 'form-control', 'id' => 'tanggal_akhir', 'readonly'])!!}
    </div>
</div>
<div class="form-group">
    <label>Keterangan</label>
    {!!Form::textarea('keterangan', null, ['class' => 'form-control', 'id' => 'keterangan', 'rows' => '3',
    'readonly'])!!}
</div>
<div class="form-group" id="status_persetujuan">
    <label>Persetujuan</label>
    <div class="row">
        <div class="col-md-6">
            <div class="custom-control custom-radio">
                {!!Form::radio('status_persetujuan', '1', false, ['class' => 'custom-control-input', 'id' =>
                'setuju'])!!}
                <label for="setuju" class="custom-control-label">Setujui</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="custom-control custom-radio">
                {!!Form::radio('status_persetujuan', '0', false, ['class' => 'custom-control-input', 'id' =>
                'tolak'])!!}
                <label for="tolak" class="custom-control-label">Tolak</label>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label>Keterangan Persetujuan</label>
    {!!Form::textarea('ket_persetujuan', null, ['class' => 'form-control', 'id' => 'ket_persetujuan', 'rows' =>
    '3'])!!}
</div>
{!! Form::close() !!}