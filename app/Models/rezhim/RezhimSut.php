<?php

namespace App\Models\rezhim;

use Illuminate\Database\Eloquent\Model;

class RezhimSut extends Model{
    protected $table='rezhim_lists.rezhim_sut';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'param_id', 'timestamp', 'val'
    ];


}

?>
