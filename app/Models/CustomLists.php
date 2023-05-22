<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomLists extends Model{
    protected $table='app_info.custom_lists';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'user', 'param_id', 'name_list'
    ];


}

?>
