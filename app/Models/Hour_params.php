<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hour_params extends Model{
    protected $table='app_info.hour_params';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'val', 'param_id', 'timestamp', 'manual',  'xml_create', 'change_by', 'comment'
    ];


}

?>
