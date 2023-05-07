<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmHour extends Model{
    protected $table='app_info.confirm_hour';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'date', 'hour'
    ];


}

?>
