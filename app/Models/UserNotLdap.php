<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserNotLdap extends Authenticatable{

    protected $table='public.users';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [

    ];

}

?>
