<?php

namespace App\Models\Ufa;

use Illuminate\Database\Eloquent\Model;

class ComplexAlgoritm extends Model{
    protected $table='ufa_tm.complex_algoritm';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'date',
    'comment'  ,
    'p_low_date' ,
    'p_low_interval' ,
    'p_low_result' ,
    'p_hi_date' ,
    'p_hi_interval' ,
    'p_hi_result' ,
    'fire_date' ,
    'fire_interval' ,
    'fire_result' ,
    'button_date' ,
    'button_interval' ,
    'button_result' ,
    'fire_two_date' ,
    'fire_two_interval',
    'fire_two_result' ,
    'button_two_date' ,
    'button_two_interval',
    'button_two_result',
    'fire_alarm_date' ,
    'fire_alarm_interval',
    'fire_alarm_result',
    'gas_alarm_date' ,
    'gas_alarm_interval' ,
    'gas_alarm_result' ,
    'filter_date' ,
    'filter_interval' ,
    'filter_result' ,
    'heat_date' ,
    'heat_interval' ,
    'heat_result' ,
    'regulator_date' ,
    'regulator_interval' ,
    'regulator_result' ,
    'electro_date' ,
    'electro_interval' ,
    'electro_result' ,
    'auto_heat_date' ,
    'auto_heat_interval' ,
    'auto_heat_result' ,
    'checked',
    'focus'
    ];


}

?>
