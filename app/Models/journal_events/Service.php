<?php

namespace App\Models\journal_events;

use Illuminate\Database\Eloquent\Model;

class Service extends Model{
    protected $table='journal_events.service';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'service', 'visible'
    ];


}

?>
