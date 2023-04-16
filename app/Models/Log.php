<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model{
    protected $table='events.log';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'username', 'event', 'date'
    ];


}

?>
