<?php

namespace App\Http\Controllers;


use App\Models\Hour_params;
use App\Models\Log;
use App\Models\TableObj;

class JournalController extends Controller
{
    public function user_log()
    {
        return view('web.journal.user_log');
    }
    public function admin_journal_data($date_start, $date_stop){
        $data = Log::wherebetween('date', [date('d.m.Y 00:00', strtotime($date_start)), date('d.m.Y 23:59', strtotime($date_stop))])
            ->orderbydesc('id')->select('username', 'event', 'comment', 'date')->get();
        return $data;
    }
}


