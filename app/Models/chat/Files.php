<?php

namespace App\Models\chat;

use Illuminate\Database\Eloquent\Model;

class Files extends Model{
    protected $table='chat.files';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'name', 'uid', 'size'
    ];


}

?>
