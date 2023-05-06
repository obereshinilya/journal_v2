<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiddenHour extends Model{
    protected $table='app_info.hidden_hour_param';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'param_id', 'login_user'
    ];


}

?>
