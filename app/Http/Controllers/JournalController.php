<?php

namespace App\Http\Controllers;


use App\Models\JournalSodu;
use App\Models\Log;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function user_log(){
        return view('web.journal.user_log');
    }
    public function admin_journal_data($date_start, $date_stop){
        $data = Log::wherebetween('date', [date('d.m.Y 00:00', strtotime($date_start)), date('d.m.Y 23:59', strtotime($date_stop))])
            ->orderbydesc('id')->select('username', 'event', 'comment', 'date')->get();
        return $data;
    }
    public function journal_sodu(){
        return view('web.journal.sodu');
    }
    public function journal_sodu_data($date_start, $date_stop){
        return JournalSodu::wherebetween('date', [date('d.m.Y 00:00', strtotime($date_start)), date('d.m.Y 23:59', strtotime($date_stop))])
            ->orderbydesc('id')->selectRaw("fio,event,type_event,date,otdel,id")->get();
    }
    public function edit_sodu(Request $request){
        $request = $request->all();
        JournalSodu::where('id', '=', $request['id'])->update([$request['column']=>$request['value']]);
        (new MainController)->create_log_record('Редактирование журнала СОДУ', 'Запись от '.date('d.m.Y H:i', strtotime(JournalSodu::where('id', '=', $request['id'])->first()->date)));
    }
    public function delete_sodu($id){
        $arr = explode(',', $id);
        array_pop($arr);
        for ($i=0; $i<count($arr); $i++){
            (new MainController)->create_log_record('Удаление записи журнала СОДУ', 'Запись от '.date('d.m.Y H:i', strtotime(JournalSodu::where('id', '=', $arr[$i])->first()->date)));
        }
        JournalSodu::wherein('id', $arr)->delete();
    }
    public function create_sodu(){
        JournalSodu::create();
    }
}


