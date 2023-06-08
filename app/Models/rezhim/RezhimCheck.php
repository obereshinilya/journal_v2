<?php

namespace App\Models\rezhim;

use Illuminate\Database\Eloquent\Model;

class RezhimCheck extends Model{
    protected $table='rezhim_lists.check_rezhim';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'confirm_param', 'id_rezhim'
    ];


}

?>
