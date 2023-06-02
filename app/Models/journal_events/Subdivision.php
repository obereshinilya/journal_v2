<?php

namespace App\Models\journal_events;

use Illuminate\Database\Eloquent\Model;

class Subdivision extends Model{
    protected $table='journal_events.subdivision';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'subdivision', 'visible'
    ];


}

?>
