<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCustomList extends Model{
    protected $table='app_info.user_custom_list';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'user', 'id_list'
    ];


}

?>
