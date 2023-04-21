<?php

namespace App\Models\chat;

use Illuminate\Database\Eloquent\Model;

class Users extends Model{
    protected $table='chat.users';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'display_name', 'login'
    ];


}

?>
