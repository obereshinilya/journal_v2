<?php

namespace App\Models\chat;

use Illuminate\Database\Eloquent\Model;

class PeopleGroup extends Model{
    protected $table='chat.users_group';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'group_id'
    ];


}

?>
