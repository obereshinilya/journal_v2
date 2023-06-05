<?php

namespace App\Models\rezhim;

use Illuminate\Database\Eloquent\Model;

class RezhimLists extends Model{
    protected $table='rezhim_lists.lists';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'name_rezhim'
    ];


}

?>
