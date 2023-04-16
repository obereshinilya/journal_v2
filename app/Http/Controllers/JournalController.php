<?php

namespace App\Http\Controllers;


use App\Models\Hour_params;
use App\Models\TableObj;

class JournalController extends Controller
{
    public function user_log()
    {
        return view('web.journal.user_log');
    }
}


