<?php

namespace App\Models\journal_events;

use Illuminate\Database\Eloquent\Model;

class Templates extends Model{
    protected $table='journal_events.templates';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'template', 'type_event', 'visible'
    ];


}

?>
