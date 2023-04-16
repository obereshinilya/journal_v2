<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sut_params extends Model{
    protected $table='app_info.sut_params';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'val', 'param_id', 'timestamp', 'manual',  'xml_create', 'change_by', 'comment'
    ];


}

?>
