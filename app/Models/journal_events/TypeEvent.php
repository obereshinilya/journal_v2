<?php

namespace App\Models\journal_events;

use Illuminate\Database\Eloquent\Model;

class TypeEvent extends Model{
    protected $table='journal_events.type_events';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'event', 'on_graph',
        'color', 'visible'
    ];


}

?>
