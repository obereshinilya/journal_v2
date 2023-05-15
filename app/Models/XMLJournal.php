<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XMLJournal extends Model{
    protected $table='events.xml_journal';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'timestamp', 'event', 'status'
    ];


}

?>
