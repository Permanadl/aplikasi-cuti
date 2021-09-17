{!! Form::model($data, [
'route' => $data->exists ? ['pengguna.update', $data->id] : ['pengguna.store'],
'method' => $data->exists ? 'PUT' : 'POST'
]) !!}
<div class="form-group">
    <label>Nama</label>
    {!!Form::text('name', null, ['class' => 'form-control', 'id' => 'name'])!!}
</div>
<div class="form-group">
    <label>Email</label>
    {!!Form::text('email', null, ['class' => 'form-control', 'id' => 'email'])!!}
</div>
<div class="form-group">
    <label>Hak Akses</label>
    {!!Form::select('roles', $select, null, ['class' => 'form-control custom-select',
    'id' =>
    'roles'])!!}
</div>
<div class="form-group">
    <label>Password</label>
    {!!Form::text('password', null, ['class' => 'form-control', 'id' => 'password'])!!}
</div>
<div class="form-group">
    <label>Konfirmasi Password</label>
    {!!Form::text('confirm', null, ['class' => 'form-control', 'id' => 'confirm'])!!}
</div>
{!! Form::close() !!}