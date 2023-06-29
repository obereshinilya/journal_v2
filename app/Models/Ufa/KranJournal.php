<?php

namespace App\Models\Ufa;

use Illuminate\Database\Eloquent\Model;

class KranJournal extends Model{
    protected $table='ufa_tm.journal_perestanovok';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [

    ];


}

?>
