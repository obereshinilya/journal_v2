<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Min_params extends Model{
    protected $table='app_info.min_params';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'val', 'param_id', 'timestamp', 'manual',  'xml_create', 'change_by'
    ];


}

?>
