<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalSodu extends Model{
    protected $table='reports.journal_sodu';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'date', 'fio', 'event', 'type_event', 'otdel',
    ];


}

?>
