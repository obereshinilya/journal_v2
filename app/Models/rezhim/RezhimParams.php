<?php

namespace App\Models\rezhim;

use Illuminate\Database\Eloquent\Model;

class RezhimParams extends Model{
    protected $table='rezhim_lists.rezhim_params';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'rezhim_id', 'num_row','empty', 'level_row', 'folder', 'hand', 'calc', 'calc_operations', 'from_hour_params', 'id_hour_param', 'e_unit', 'name',
    ];


}

?>
