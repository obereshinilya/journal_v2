<?php

namespace App\Models\journal_events;

use Illuminate\Database\Eloquent\Model;

class JournalEvents extends Model{
    protected $table='journal_events.journal';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'timestamp', 'dispatcher_id',
        'description', 'type_id',
        'accept', 'time_accept',
        'user_id_accept', 'ingener',
        'subdivision_id', 'service_id'
    ];


}

?>
