<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model{
    protected $table='app_info.setting';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'name_setting', 'value'
    ];


}

?>
