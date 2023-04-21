<?php

namespace App\Models\chat;

use Illuminate\Database\Eloquent\Model;

class Message extends Model{
    protected $table='chat.messages';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'creator_id', 'message_body', 'parent_message_id', 'file_id'
    ];


}

?>
