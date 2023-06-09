<?php

namespace App\Http\Controllers;


use App\Models\chat\Users;
use App\Models\ConfirmHour;
use App\Models\CustomLists;
use App\Models\HiddenHour;
use App\Models\Hour_params;
use App\Models\journal_events\JournalEvents;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Sut_params;
use App\Models\TableObj;
use App\Models\UserCustomList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function copy_hour(Request $request){
        $data = $request->all();
        $hour = mb_strtolower($data['hour_from']);
        $setting = Setting::get()->pluck('value', 'name_setting');
        if ($data['hour_from'] == 'Сутки'){
            if (strtotime(date('Y-m-d '.$setting['start_smena'].':00', strtotime($data['date_to'].' + 1 days')))<strtotime(date('Y-m-d H:i'))){
                $data_from = Sut_params::where('timestamp', '=', date('Y-m-d ', strtotime($data['date_from'])))
                    ->get();
                if (count($data_from) == 0){
                    return 'Копирование сводки за сутки '.$data['date_from'].'<br><br>Нет данных для копирования!';
                }
                if (count(ConfirmHour::where('date', '=', $data['date_to'])->where('hour', '=', null)->get())>0 && $setting['authenticity_copy'] == 'false'){
                    return 'Копирование сводки за сутки '.$data['date_from'].'<br><br>Запрещено копировать в достоверную сводку!';
                }
                $param_ids = $data_from->pluck('param_id');
                Sut_params::wherein('param_id', $param_ids)->where('timestamp', '=', $data['date_to'])->delete();
                foreach ($data_from as $row) {
                    $newRow = $row->replicate();
                    $newRow->timestamp = $data['date_to'];
                    $newRow->comment = '<b>Комментарий:</b><br>'.Auth::user()->displayname[0].':<br><em>Скопировано с '.$data['date_from'].'</em>';
                    $newRow->save();
                }
                (new MainController)->create_log_record('Копирование суточных показателей', 'С '.$data['date_from'].' на '.$data['date_to']);
                return 'false';
            }else{
                return 'Копирование сводки за сутки '.$data['date_from'].'<br><br>Операция невозможна!<br>Время выбранной сводки не наступило!';
            }
        }else{
            if (strtotime(date('Y-m-d H:i', strtotime($data['date_to'])))<strtotime(date('Y-m-d H:i'))){
                $data_from = Hour_params::whereRaw("date_trunc('hour', timestamp) = '".date('Y-m-d H:i', strtotime($data['date_from'].' '.$data['hour_from']))."'")
                    ->get();
                if (count($data_from) == 0){
                    return 'Копирование сводки за '.$data['hour_from'].' '.$data['date_from'].'<br><br>Нет данных для копирования!';
                }
                if (count(ConfirmHour::where('date', '=', substr($data['date_to'],0,-5))->where('hour', '=', mb_substr($data['date_to'], 10).':00')->get())>0 && $setting['authenticity_copy'] == 'false'){
                    return 'Копирование сводки за '.$data['hour_from'].' '.$data['date_from'].'<br><br>Запрещено копировать в достоверную сводку!';
                }
                $param_ids = $data_from->pluck('param_id');
                Hour_params::wherein('param_id', $param_ids)->
                whereRaw("date_trunc('hour', timestamp) = '".date('Y-m-d H:i', strtotime($data['date_to']))."'")
                    ->delete();
                foreach ($data_from as $row) {
                    $newRow = $row->replicate();
                    $newRow->timestamp = $data['date_to'];
                    $newRow->comment = '<b>Комментарий:</b><br>'.Auth::user()->displayname[0].':<br><em>Скопировано с '.$data['hour_from'].' '.$data['date_from'].'</em>';
                    $newRow->save();
                }
                (new MainController)->create_log_record('Копирование часовых показателей', 'С '.$data['date_from'].' '.$data['hour_from'].' на '.$data['date_to']);
                return 'false';
            }else{
                return 'Копирование сводки за '.$data['hour_from'].' '.$data['date_from'].'<br><br>Операция невозможна!<br>Время выбранной сводки не наступило!';
            }
        }
    }
    public function confirm_hour(Request $request){
        $setting = Setting::get()->pluck('value', 'name_setting');
        $data = $request->all();
        $hour = mb_strtolower($data['hour']);
        if ($data['hour'] == 'Сутки'){
            $data['hour'] = null;
        }else{
            if (date('H:i', strtotime($data['hour']))<date('H:i', strtotime($setting['start_smena'].':00'))){
                $data['date'] = date('d.m.Y', strtotime($data['date'].' +1 days'));
            }
        }
        $from_db = ConfirmHour::where('hour', '=', $data['hour'])->where('date', '=', $data['date'])->get();
        if(count($from_db) > 0){
            if($setting['authenticity_remove'] == 'true'){
                (new MainController)->create_log_record('Снятие отметки достоверности', 'За '.$hour.' '.$data['date']);
                try {
                    $from_db->first()->delete();
                }catch (\Throwable $e){
                    return $e;
                }
            }else{
                return 'Снятие отметки о достоверности запрещено!';
            }
        }else{
            (new MainController)->create_log_record('Подтверждение достоверности', 'За '.$hour.' '.$data['date']);
            ConfirmHour::create($data);
        }
    }
    public function get_confirmed_hours($date){
        $setting = Setting::where('name_setting', '=', 'start_smena')->first()->value;
        $confirmed = ConfirmHour::
            where([['date', '=', $date], ['hour', '>=', date('H:i', strtotime($setting.':00'))]])
            ->orwhere([['date', '=', date('d.m.Y', strtotime($date.' +1 days'))], ['hour', '<', date('H:i', strtotime($setting.':00'))]])
            ->orwhere([['date', '=', $date], ['hour', '=', null]])
            ->select(DB::raw("to_char(hour, 'HH24:mi') as time"))
            ->get()->pluck('time');
        return $confirmed;
    }

    public function delete_object($parent_id)
    {
        (new MainController)->create_log_record('Удаление объекта', 'Объект: "' . TableObj::where('id', '=', $parent_id)->first()->full_name . '"');
        $this->delete_child($parent_id);
    }

    private function delete_child($parent_id)
    {
        foreach (TableObj::where('parent_id', $parent_id)->get() as $row) {
            if (TableObj::where('parent_id', $row->id)->get()) {
                $this->delete_child($row->id);
            } else {
                $row->delete();
            }
        }
        TableObj::where('id', $parent_id)->delete();
    }

    public function store_new_signal(Request $request, $parent_id)
    {
        (new MainController)->create_log_record('Добавление сигнала', 'Объект: "' . TableObj::where('id', '=', $parent_id)->first()->full_name . '"');
        try {
            $level = TableObj::where('id', '=', $parent_id)->first()->level + 1;
            $data = $request->all();
            $keys = array_keys($data);
            for ($i = 0; $i < count($data[$keys[0]]); $i++) {
                foreach ($keys as $key) {
                    $data_to_base[$key] = $data[$key][$i];
                }
                $data_to_base['level'] = $level;
                $data_to_base['inout'] = 'ВХОД';
                $data_to_base['parent_id'] = $parent_id;
                $visible_hour_param = $data_to_base['hour_param'];
                unset($data_to_base['hour_param']);
                $new_signal_data = TableObj::create($data_to_base);
                if ($visible_hour_param == 'false'){
                    HiddenHour::create(['login_user'=>Auth::user()->cn[0], 'param_id'=>$new_signal_data->id]);
                }
            }
        } catch (\Throwable $e) {
            return $e;
        }

    }

    public function store_new_name($id, $new_name)
    {
        (new MainController)->create_log_record('Переименовывание', 'Объект "' . TableObj::where('id', '=', $id)->first()->full_name . '" в "' . $new_name . '"');
        TableObj::where('id', '=', $id)->first()->update(['full_name' => $new_name]);
    }

    public function store_new_object($parent_id, $name_new_object)
    {
        (new MainController)->create_log_record('Добавление объекта', 'Объект: "' . $name_new_object . '"');
        if ($parent_id == 'false'){
            $parent = TableObj::where('level', '=', 0)->first();
            $parent_id = $parent->id;
        }else{
            $parent = TableObj::where('id', '=', $parent_id)->first();
        }
        $new_obj = [
            'full_name' => $name_new_object,
            'inout' => '!',
            'parent_id' => $parent_id,
            'level' => $parent->level + 1
        ];
        TableObj::create($new_obj);
    }

    public function get_side_object()
    {
        $arr = TableObj::getTree();
        $dom = new \DOMDocument('1.0');
        $div_tree = $dom->createElement('ul');
        $level = 1;
        $this->treeFormed($dom, $arr[0], $div_tree, $level);
        return $dom->saveHTML($div_tree);
    }

    private function treeFormed($dom, $dict, $element, $level)
    {
        try {
            foreach ($dict['children'] as $value) {
                $element_li = $dom->createElement('li');
                $element_li->setAttribute('data-id', $value['id']);
                if (array_key_exists('children', $value)) {
                    $element_ul = $dom->createElement('ul');
                    $element_ul->setAttribute('id', 'ul_' . $value['id']);
                    $element_li->setAttribute('class', 'side_obj');
                    $element_li->textContent = $value['full_name'];
                    $plus_icon = $dom->createElement('img');
                    $plus_icon->setAttribute('class', 'plus_icon hide');
                    $plus_icon->setAttribute('data-ul', 'ul_' . $value['id']);
                    $plus_icon->setAttribute('src', asset('/assets/img/plus.png'));
                    $element_li->appendChild($plus_icon);
                    $element->appendChild($element_li);
                    $element->appendChild($element_ul);
                    $this->treeFormed($dom, $value, $element_ul, $level + 1);
                } else {
                    $element_li->textContent = $value['full_name'];
                    $element->appendChild($element_li);
                }
            }
        } catch (\Exception $err) {

        }
    }

    public function get_data_for_graph($param_id, $date_start, $date_stop)
    {
        $param_string = substr($param_id, 0, -1);
        $param_id = explode(",", $param_string);
        $date_end = date('d.m.Y 00:00', strtotime($date_stop.' +1 days'));
        $date_start = date('d.m.Y 00:00', strtotime($date_start));
        $sql = 'SELECT time.date, ';
        $joinrow = "(SELECT date_trunc('minute', time.timestamp) as date from app_info.hour_params as time where time.param_id in ($param_string) and time.timestamp between date '$date_start' and date '$date_end' group by  time.timestamp order by time.timestamp) as time";
        foreach ($param_id as $key => $id) {
            if ($param_id[$key] != end($param_id)) {
                $sql .= 'p' . $key . '.val as val' . $key . ', ';
            } else {
                $sql .= 'p' . $key . '.val as val' . $key;

            }
            $joinrow .= " left join (SELECT date_trunc('minute', p$key.timestamp) as date_p$key, val from app_info.hour_params as p$key where p$key.param_id=$id and p$key.timestamp between date '$date_start' and date '$date_end' ) as p$key";
            $joinrow .= " on p$key.date_p$key = time.date";
        }
        $sql .= ' FROM ';
        $sql .= $joinrow;
        $result = DB::select($sql);
        foreach ($result as $row) {
            $new_result[] = array_values((array)$row);
        }
        $events = JournalEvents::wherebetween('timestamp', [$date_start, $date_end])
            ->join('journal_events.type_events', 'journal.type_id', '=', 'type_events.id')
            ->where('type_events.on_graph', '=', true)
            ->selectRaw('timestamp as start , description as label, \'%Y-%m-%d %H:%M:%S\' as timeformat, concat(\'{"marker": {"fill": "\', color, \'"}}\') as style')
            ->get();
        $events = json_encode($events);
        $events = str_replace(['"style":"{\"marker\"', '\"fill\"', '\"#', '\"}}"'], ['"style":{"marker"', '"fill"', '"#', '"}}'], $events);
        $new_result['events'] = json_decode($events);
        return json_encode($new_result);
    }

    function get_hide_id($parent_id)
    {
        $arr = [];
        return $this->hide_id($parent_id, $arr);
    }

    private function hide_id($parent_id, $arr)
    {
        foreach (TableObj::where('parent_id', '=', $parent_id)->get() as $item) {
            if ($item->inout == '!') {
                $arr = $this->hide_id($item->id, $arr);
            } else {
                array_push($arr, $item->id);
            }
        }
        return $arr;
    }

    public function create_log_record($message, $comment)
    {
        try {
            $record['username'] = Auth::user()->displayname[0];
        } catch (\Throwable $e) {
            $record['username'] = '';
        }
        $record['event'] = $message;
        $record['comment'] = $comment;
        try {
            $last_record = Log::orderbydesc('id')->first()->toArray();
            if ($last_record['username'] == $record['username'] && $last_record['event'] == $record['event'] && $last_record['comment'] == $record['comment']){

            }else{
                Log::create($record);
            }
        }catch (\Throwable $e){
            Log::create($record);
        }
    }

}


