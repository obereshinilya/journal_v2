<?php

namespace App\Models\chat;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model{
    protected $table='chat.groups';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'name', 'creator_id'
    ];


}

?>
