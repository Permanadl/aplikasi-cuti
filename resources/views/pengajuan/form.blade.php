<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    {!! Form::model($data, [
    'route' => $data->exists ? ['pengajuan.update', $data->nomor] : ['pengajuan.store'],
    'method' => $data->exists ? 'PUT' : 'POST'
    ]) !!}
    <div class="form-group">
        <label>Nomor Cuti</label>
        {!!Form::text('nomor', $nomor, ['class' => 'form-control', 'id' => 'nomor', 'readonly'])!!}
    </div>
    <div class="form-group">
        <label>Nama</label>
        {!!Form::text('name', Auth::user()->name, ['class' => 'form-control', 'id' => 'name', 'readonly'])!!}
    </div>
    <div class="form-group">
        <label>Tanggal Awal</label>
        {!!Form::date('tanggal_awal', null, ['class' => 'form-control', 'id' => 'tanggal_awal', 'min' => date('Y-m-d')])!!}
    </div>
    <div class="form-group">
        <label>Tanggal Akhir</label>
        {!!Form::date('tanggal_akhir', null, ['class' => 'form-control', 'id' => 'tanggal_akhir', 'min' => date('Y-m-d')])!!}
    </div>
    <div class="form-group">
        <label>Sisa Cuti (Hari)</label>
        {!!Form::text('sisa_cuti', $sisa_cuti, ['class' => 'form-control', 'id' => 'sisa_cuti', 'readonly'])!!}
    </div>
    <div class="form-group">
        <label>Jumlah Cuti (Hari)</label>
        {!!Form::text('jumlah_cuti', 0, ['class' => 'form-control', 'id' => 'jumlah_cuti', 'readonly'])!!}
    </div>
    <div class="form-group">
        <label>Keterangan</label>
        {!!Form::textarea('keterangan', null, ['class' => 'form-control', 'id' => 'keterangan', 'rows' => '3'])!!}
    </div>
    {!! Form::close() !!}
    <script>
        $(document).ready(function() {

            $('#tanggal_awal').change(function() {
                var start = new Date($('#tanggal_awal').val()),
                    end = new Date($('#tanggal_akhir').val()),
                    diffTime = (end - start),
                    diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if ($('#tanggal_akhir').val() === '') {
                    $('#jumlah_cuti').val('Tentukan tanggal akhir')
                } else {
                    $('#jumlah_cuti').val(diffDays + 1)
                }
            })

            $('#tanggal_akhir').change(function() {
                var start = new Date($('#tanggal_awal').val()),
                    end = new Date($('#tanggal_akhir').val()),
                    diffTime = (end - start),
                    diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if ($('#tanggal_awal').val() === '') {
                    $('#jumlah_cuti').val('Tentukan tanggal awal')
                } else {
                    $('#jumlah_cuti').val(diffDays + 1)
                }
            })
        })
    </script>
</body>

</html>