<?php

namespace App\Models\Ufa;

use Illuminate\Database\Eloquent\Model;

class ComplexKran extends Model{
    protected $table='ufa_tm.complex_kran';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
    'date',
    'comment',
    'checked',
    'focus'
    ];


}

?>
