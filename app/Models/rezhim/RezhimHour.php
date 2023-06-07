<?php

namespace App\Models\rezhim;

use Illuminate\Database\Eloquent\Model;

class RezhimHour extends Model{
    protected $table='rezhim_lists.rezhim_hour';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'param_id', 'timestamp', 'val'
    ];


}

?>
