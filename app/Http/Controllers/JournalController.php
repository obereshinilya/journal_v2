<?php

namespace App\Http\Controllers;


use App\Models\chat\Users;
use App\Models\journal_events\JournalEvents;
use App\Models\journal_events\Service;
use App\Models\journal_events\Subdivision;
use App\Models\journal_events\Templates;
use App\Models\journal_events\TypeEvent;
use App\Models\JournalSodu;
use App\Models\Log;
use App\Models\XMLJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function xml_journal(){
        return view('web.journal.xml_journal');
    }
    public function xml_journal_data($date_start, $date_stop){
        $data = XMLJournal::wherebetween('timestamp', [date('d.m.Y 00:00', strtotime($date_start)), date('d.m.Y 23:59', strtotime($date_stop))])
            ->orderbydesc('timestamp')->select('event', 'status','timestamp')->get();
        return $data;
    }
    public function journal_sodu(){
        return view('web.journal.sodu');
    }
    public function journal_sodu_data($date_start, $date_stop){
        $data = JournalSodu::wherebetween('date', [date('d.m.Y 00:00', strtotime($date_start)), date('d.m.Y 23:59', strtotime($date_stop))])
            ->orderbydesc('date')->selectRaw("fio,event,type_event,date,otdel,id")->get()->toArray();
        $data['type_event'] = JournalSodu::whereNotNull('type_event')->select('type_event')->groupby('type_event')->orderby('type_event')->get()->pluck('type_event');
        $data['otdel'] = JournalSodu::whereNotNull('otdel')->select('otdel')->groupby('otdel')->orderby('otdel')->get()->pluck('otdel');
        return $data;
    }
    public function get_dropbox_sodu_data(){
        $data['type_event'] = JournalSodu::whereNotNull('type_event')->select('type_event')->groupby('type_event')->orderby('type_event')->get()->pluck('type_event');
        $data['otdel'] = JournalSodu::whereNotNull('otdel')->select('otdel')->groupby('otdel')->orderby('otdel')->get()->pluck('otdel');
        return $data;
    }
    public function edit_sodu(Request $request){
        $request = $request->all();
        JournalSodu::where('id', '=', $request['id'])->update([$request['column']=>$request['value']]);
        (new MainController)->create_log_record('Редактирование журнала СОДУ', 'Запись от '.date('d.m.Y H:i', strtotime(JournalSodu::where('id', '=', $request['id'])->first()->date)));
    }
    public function delete_sodu(Request $request){
        try {
            $data = $request->all();
            $arr = explode(',', $data['id']);
            array_pop($arr);
            for ($i=0; $i<count($arr); $i++){
                (new MainController)->create_log_record('Удаление записи журнала СОДУ', 'Запись от '.date('d.m.Y H:i', strtotime(JournalSodu::where('id', '=', $arr[$i])->first()->date)));
            }
            JournalSodu::wherein('id', $arr)->delete();
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function create_sodu(Request $request){
        $data = $request->all();
        if (!$data['date']){
            $data['date'] = date('Y-m-d H:i');
        }
        JournalSodu::create($data);
    }
    public function journal_events(){
        return view('web.journal.events');
    }
    public function journal_events_data($date_start, $date_stop){
            return JournalEvents::wherebetween('timestamp', [date('d.m.Y 00:00', strtotime($date_start)), date('d.m.Y 23:59', strtotime($date_stop))])
            ->join('chat.users', 'journal.dispatcher_id', '=', 'users.id')
            ->join('journal_events.type_events', 'journal.type_id', '=', 'type_events.id')
            ->join('journal_events.subdivision', 'journal.subdivision_id', '=', 'subdivision.id')
            ->join('journal_events.service', 'journal.service_id', '=', 'service.id')
            ->orderByDesc('journal.timestamp')
            ->selectRaw('to_char(timestamp, \'HH24:MI dd.mm.yyyy\') as timestamp, display_name, subdivision, description, event, service, accept, ingener, journal.id as id,
             \'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1)"><path d="m16 2.012 3 3L16.713 7.3l-3-3zM4 14v3h3l8.299-8.287-3-3zm0 6h16v2H4z"></path></svg>\'
             as img')
            ->get();
    }
    public function get_subdivisions(){
        return Subdivision::orderby('subdivision')->where('visible', '=', true)->select('id' ,'subdivision')->get();
    }
    public function edit_subdivisions(Request $request){
        $data = $request ->all();
        if ($data['column'] == 'visible'){
            $ids = explode(',', $data['id']);
            for ($i=0;$i<count($ids)-1; $i++){
                Subdivision::where('id', '=', $ids[$i])->first()->update([$data['column']=>$data['value']]);
            }
            return $ids;
        }else{
            Subdivision::where('id', '=', $data['id'])->first()->update([$data['column']=>$data['value']]);
        }
    }
    public function create_subdivision(Request $request){
        $data = $request->all();
        Subdivision::create(['subdivision'=>$data['value']]);
    }
    public function get_types(){
        return TypeEvent::orderby('event')->where('visible', '=', true)->select('id' ,'event', 'on_graph', 'color')->get();
    }
    public function edit_types(Request $request){
        $data = $request ->all();
        if ($data['column'] == 'visible'){
            $ids = explode(',', $data['id']);
            for ($i=0;$i<count($ids)-1; $i++){
                TypeEvent::where('id', '=', $ids[$i])->first()->update([$data['column']=>$data['value']]);
            }
        }else{
            TypeEvent::where('id', '=', $data['id'])->first()->update([$data['column']=>$data['value']]);
        }
    }
    public function create_types(Request $request){
        $data = $request->all();
        TypeEvent::create(['event'=>$data['value']]);
    }
    public function get_service(){
        return Service::orderby('service')->where('visible', '=', true)->select('id' ,'service')->get();
    }
    public function edit_service(Request $request){
        $data = $request ->all();
        if ($data['column'] == 'visible'){
            $ids = explode(',', $data['id']);
            for ($i=0;$i<count($ids)-1; $i++){
                Service::where('id', '=', $ids[$i])->first()->update([$data['column']=>$data['value']]);
            }
            return $ids;
        }else{
            Service::where('id', '=', $data['id'])->first()->update([$data['column']=>$data['value']]);
        }
    }
    public function create_service(Request $request){
        $data = $request->all();
        Service::create(['service'=>$data['value']]);
    }
    public function get_templates($type_event){
        if ($type_event == 'false'){
            $data = Templates::orderby('template')
                ->join('journal_events.type_events', 'templates.type_event', '=', 'type_events.id')
                ->select('templates.id as id', 'event', 'template')
                ->where('templates.visible', '=', true)
                ->get()->toArray();
            $data['dropbox'] = TypeEvent::where('visible', '=', true)->orderby('event')->select('event')->get()->pluck('event');
            return $data;
        }else{
            return Templates::where('type_event', '=', $type_event)->where('visible', '=', true)->orderby('template')->select('id' ,'type_event', 'template')->get();
        }
    }
    public function edit_templates(Request $request){
        $data = $request ->all();
        if ($data['column'] == 'visible'){
            $ids = explode(',', $data['id']);
            for ($i=0;$i<count($ids)-1; $i++){
                Templates::where('id', '=', $ids[$i])->first()->update([$data['column']=>$data['value']]);
            }
        }elseif($data['column'] == 'template'){
            Templates::where('id', '=', $data['id'])->first()->update([$data['column']=>$data['value']]);
        }else{
            $id_event = TypeEvent::where('event', '=', $data['value'])->where('visible', '=', true)->first()->id;
            Templates::where('id', '=', $data['id'])->first()->update(['type_event'=>$id_event]);
        }
    }
    public function create_templates(Request $request){
        $data = $request->all();
        Templates::create(['type_event'=>$data['type_event'], 'template'=>$data['template']]);
    }
    public function save_event(Request $request, $id){
        try {
            if ($id == 'false'){
                $data = $request->all();
                $data['dispatcher_id'] = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
                JournalEvents::create($data);
                (new MainController)->create_log_record('Редактирование журнала событий', 'Создал запись от '.$data['timestamp']);
            }else{
                $data = $request->all();
                if ($data['accept'] == 'true'){
                    $data['time_accept'] = date('Y-m-d H:i');
                    $data['user_id_accept'] = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
                    (new MainController)->create_log_record('Редактирование журнала событий', 'Принял запись от '.$data['timestamp']);
                }else{
                    (new MainController)->create_log_record('Редактирование журнала событий', 'Отредактировал запись от '.$data['timestamp']);
                }
                JournalEvents::where('id', '=', $id)->first()->update($data);
            }
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function get_record_event($id){
        $data = JournalEvents::where('journal.id', '=', $id)
            ->leftjoin('chat.users', 'journal.user_id_accept', '=', 'users.id')
            ->first()->toArray();
        $data['timestamp'] = date('d.m.Y H:i', strtotime($data['timestamp']));
        $data['time_accept'] = date('d.m.Y H:i', strtotime($data['time_accept']));
        return $data;
    }
    public function setting_journal_events(){
            return view('web.journal.events_setting');
    }
}


