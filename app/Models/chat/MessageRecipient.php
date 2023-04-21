<?php

namespace App\Models\chat;

use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model{
    protected $table='chat.message_recipient';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'recipient_id', 'recipient_group_id', 'message_id', 'is_read'
    ];


}

?>
