<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterCuti extends Model
{
    protected $table = 'master_cuti';
    protected $fillable = [
        'id_user',
        'tahun',
        'sisa_cuti'
    ];
}
