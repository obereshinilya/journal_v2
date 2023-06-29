<?php

namespace App\Http\Controllers;

use App\Models\Hour_params;
use App\Models\journal_events\JournalEvents;
use App\Models\rezhim\RezhimCheck;
use App\Models\rezhim\RezhimHour;
use App\Models\rezhim\RezhimLists;
use App\Models\rezhim\RezhimParams;
use App\Models\rezhim\RezhimSut;
use App\Models\Setting;
use App\Models\Sut_params;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RezhimController extends Controller
{
    public function sut_rezhim_data($id_rezhim, $date, $history_column){
        $setting = Setting::get()->pluck('value', 'name_setting');
        ///Получаем оглавление и столбец с булькой суммы
        $result = RezhimParams::where('rezhim_id', '=', $id_rezhim)->orderby('num_row')
            ->select('id',
                DB::raw('(CASE WHEN hand = true THEN
                \'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1)"><path d="M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3 1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z"></path></svg>\'
                WHEN calc = true THEN
                \'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1)"><path d="M19 2H5c-1.103 0-2 .897-2 2v16c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zM5 20V4h14l.001 16H5z"></path><path d="M7 12h2v2H7zm0 4h2v2H7zm4-4h2v2h-2zM7 6h10v4H7zm4 10h2v2h-2zm4-4h2v6h-2z"></path></svg>\'
                WHEN from_hour_params = true THEN
                \'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1)"><path d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z"></path><path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z"></path></svg>\'
                ELSE \'\' END) AS img'),
                DB::raw('case when folder = true then
        concat(
            \'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="padding-left: \',
            level_row*10 - 10,
            \'px ;fill: rgba(0, 0, 0, 1); float: left"><path d="M2.165 19.551c.186.28.499.449.835.449h15c.4 0 .762-.238.919-.606l3-7A.998.998 0 0 0 21 11h-1V7c0-1.103-.897-2-2-2h-6.1L9.616 3.213A.997.997 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14h.007a1 1 0 0 0 .158.551zM17.341 18H4.517l2.143-5h12.824l-2.143 5zM18 7v4H6c-.4 0-.762.238-.919.606L4 14.129V7h14z"></path></svg>\'
            \'<span style="width: calc(100% - 25px - \',
            level_row*10 - 10,
            \'px);float: right; text-align: left">\',
            name,
            \'</span>\'
            )
    else
        concat(
            \'<span style="width: calc(100% - \',
            level_row*10 - 10,
            \'px); padding-left: \',
            level_row*10 - 10,
            \'px;float: right; text-align: left">\',
            name,
            \'</span>\'
            )
end as full_name
                '),
                'e_unit', 'sum')->get()->toArray();
        ///Получения начала часовок и текущего времени
        $date_stop = date('d.m.Y', strtotime($date));
        $date_start = date('d.m.Y', strtotime($date_stop.' -'.($history_column).' days'));
        ///Заполняем начальный массив $result['column'][время(будущий столбец)][параметр(номер строки)] = значение в ячейку
        $time_arr = [];
        while (strtotime($date_start) <= strtotime($date_stop)){
            array_push($time_arr, $date_stop);
            $date_stop = date('d.m.Y', strtotime($date_stop.' -1 days'));
        }

        ///Получаем параметры
        $params = RezhimParams::where('rezhim_id', '=', $id_rezhim)->orderby('num_row')->get()->toArray();
        ///Массив param_id из main_table
        $from_time_param = RezhimParams::where('rezhim_id', '=', $id_rezhim)->whereNotNull('id_hour_param')->get()->pluck('id_hour_param')->toArray();

        ///Получение суток в формате $hour[время] и внутри два массива - param_id и val
        $sut_param = Sut_params::wherebetween('timestamp', [date('Y-m-d', strtotime(end($time_arr))), date('Y-m-d', strtotime($time_arr[0]))])
            ->wherein('param_id', $from_time_param)
            ->groupby('timestamp')
            ->select(DB::raw("to_char(timestamp, 'dd.mm.yyyy') as timestamp"), DB::raw("array_agg(param_id) as param_id, array_agg(val) as val"))
            ->get();
        $sut_array = [];
        foreach ($sut_param as $row){
            $sut_array[$row->timestamp]['val'] = explode(',', str_replace( ['{', '}', '"'], '',$row->val));
            $sut_array[$row->timestamp]['param_id'] = explode(',', str_replace( ['{', '}', '"'], '',$row->param_id));
        }
        if (count($from_time_param)>0){
            ///Получение суток суммой в формате $hour[время] и внутри два массива - param_id и val
            $sum_hour_param = DB::select("select to_char(a.date, 'dd.mm.yyyy') as timestamp, array_agg(a.sum) as val, array_agg(a.param_id) as param_id from (
            select param_id, sum(val), date(timestamp-interval '".$setting['start_smena'].":00')
            from app_info.hour_params
            where timestamp >= '".date('Y-m-d',strtotime(end($time_arr)))." ".$setting['start_smena'].":00' and timestamp < '".date('Y-m-d',strtotime($time_arr[0].' +1 day'))." ".$setting['start_smena'].":00' and param_id in (".implode(",",$from_time_param).")
            group by param_id, date(timestamp-interval '".$setting['start_smena'].":00')) as a
            group by timestamp");
            $sum_hour_array = [];
            foreach ($sum_hour_param as $row){
                $sum_hour_array[$row->timestamp]['val'] = explode(',', str_replace( ['{', '}', '"'], '',$row->val));
                $sum_hour_array[$row->timestamp]['param_id'] = explode(',', str_replace( ['{', '}', '"'], '',$row->param_id));
            }
        }else{
            $sum_hour_array = [];
        }
        ///Массив param_id из main_table
        $from_rezhim_param = RezhimParams::where('rezhim_id', '=', $id_rezhim)->get()->pluck('id')->toArray();
        ///Получение ручного ввода в формате $hour[время] и внутри два массива - param_id и val
        $hand_param = RezhimSut::wherebetween('timestamp', [date('Y-m-d', strtotime(end($time_arr))), date('Y-m-d', strtotime($time_arr[0]))])
            ->wherein('param_id', $from_rezhim_param)
            ->groupby('timestamp')
            ->select(DB::raw("to_char(timestamp, 'dd.mm.yyyy') as timestamp"), DB::raw("array_agg(param_id) as param_id, array_agg(val) as val"))
            ->get();
        $hand_array = [];
        foreach ($hand_param as $row){
            $hand_array[$row->timestamp]['val'] = explode(',', str_replace( ['{', '}', '"'], '',$row->val));
            $hand_array[$row->timestamp]['param_id'] = explode(',', str_replace( ['{', '}', '"'], '',$row->param_id));
        }
        $sum_hand_param = DB::select("select to_char(a.timestamp, 'dd.mm.yyyy') as timestamp, array_agg(a.sum) as val, array_agg(a.param_id) as param_id from (
            select param_id, sum(val), date(timestamp-interval '".$setting['start_smena']."') as timestamp
            from rezhim_lists.rezhim_hour
            where timestamp >= '".date('Y-m-d',strtotime(end($time_arr)))." ".$setting['start_smena'].":00' and timestamp < '".date('Y-m-d',strtotime($time_arr[0].' +1 day'))." ".$setting['start_smena'].":00' and param_id in (".implode(",",$from_rezhim_param).")
            group by param_id, timestamp) as a
            group by timestamp");

        $sum_hand_array = [];
        foreach ($sum_hand_param as $row){
            $sum_hand_array[$row->timestamp]['val'] = explode(',', str_replace( ['{', '}', '"'], '',$row->val));
            $sum_hand_array[$row->timestamp]['param_id'] = explode(',', str_replace( ['{', '}', '"'], '',$row->param_id));
        }
        $array_column = ['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AC'];
        ///Получили подтвержденные суточные
        $check_rezhim = RezhimCheck::where('id_rezhim', '=', $id_rezhim)->get()->pluck('confirm_param')->toArray();
        $result['hidden_column'] = [];
        $result['hidden_rows'] = [];
        for ($k=0; $k<count($params); $k++){
            for ($j=0; $j<count($time_arr); $j++){
                if ($params[$k]['sum']){///Если параметр сумма
                    array_push($result['hidden_rows'], $k);
                    if (gettype(array_search($time_arr[$j], $check_rezhim)) == 'integer'){  ///Если сутки подтвердили
                        array_push($result['hidden_column'], (5+$j));
                        if (!$params[$k]['empty']){
                            try {
                                if (gettype(array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                    $result[$k][$time_arr[$j]] = $hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])];
                                }else{
                                    $result[$k][$time_arr[$j]] = 0;
                                }
                            }catch (\Throwable $e){
                                $result[$k][$time_arr[$j]] = 0;
                            }
                        }else{
                            $result[$k][$time_arr[$j]] = '';
                        }
                    }else{ ///Если сутки не подтверждены
                        if ($params[$k]['hand']){
                            try {
                                if (gettype(array_search($params[$k]['id'], $sum_hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                    $result[$k][$time_arr[$j]] = $sum_hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $sum_hand_array[$time_arr[$j]]['param_id'])];
                                }else{
                                    $result[$k][$time_arr[$j]] = 0;
                                }
                            }catch (\Throwable $e){
                                $result[$k][$time_arr[$j]] = 0;
                            }
                        }elseif ($params[$k]['calc']){
                            try {
                                if (gettype(array_search($params[$k]['id'], $sum_hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                    $result[$k][$time_arr[$j]] = $sum_hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $sum_hand_array[$time_arr[$j]]['param_id'])];
                                }else{
                                    $result[$k][$time_arr[$j]] = str_replace(['Line'], [$array_column[$j]], $params[$k]['calc_operations']);
                                }
                            }catch (\Throwable $e){
                                $result[$k][$time_arr[$j]] = str_replace(['Line'], [$array_column[$j]], $params[$k]['calc_operations']);
                            }
                        }elseif($params[$k]['empty']){
                            $result[$k][$time_arr[$j]] = '';
                        }else{
                            try {
                                $result[$k][$time_arr[$j]] = $sum_hour_array[$time_arr[$j]]['val'][array_search($params[$k]['id_hour_param'],$sum_hour_array[$time_arr[$j]]['param_id'])];
                            }catch (\Throwable $ex){
                                $result[$k][$time_arr[$j]] = 0;
                            }
                        }
                    }
                }else{
                    if (gettype(array_search($time_arr[$j], $check_rezhim)) == 'integer') {  ///Если сутки подтвердили
                        array_push($result['hidden_column'], (5+$j));
                        if (!$params[$k]['empty']){
                            try {
                                if (gettype(array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                    $result[$k][$time_arr[$j]] = $hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])];
                                }else{
                                    $result[$k][$time_arr[$j]] = 0;
                                }
                            }catch (\Throwable $e){
                                $result[$k][$time_arr[$j]] = 0;
                            }
                        }else{
                            $result[$k][$time_arr[$j]] = '';
                        }
                    }else{
                        if ($params[$k]['hand']){
                            try {
                                if (gettype(array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                    $result[$k][$time_arr[$j]] = $hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])];
                                }else{
                                    $result[$k][$time_arr[$j]] = 0;
                                }
                            }catch (\Throwable $e){
                                $result[$k][$time_arr[$j]] = 0;
                            }
                        }elseif ($params[$k]['calc']){
                            array_push($result['hidden_rows'], $k);
                            try {
                                if (gettype(array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                    $result[$k][$time_arr[$j]] = $hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])];
                                }else{
                                    $result[$k][$time_arr[$j]] = str_replace(['Line'], [$array_column[$j]], $params[$k]['calc_operations']);
                                }
                            }catch (\Throwable $e){
                                $result[$k][$time_arr[$j]] = str_replace(['Line'], [$array_column[$j]], $params[$k]['calc_operations']);
                            }
                        }elseif($params[$k]['empty']){
                            array_push($result['hidden_rows'], $k);
                            $result[$k][$time_arr[$j]] = '';
                        }else{
                            array_push($result['hidden_rows'], $k);
                            try {
                                $result[$k][$time_arr[$j]] = $sut_array[$time_arr[$j]]['val'][array_search($params[$k]['id_hour_param'],$sut_array[$time_arr[$j]]['param_id'])];
                            }catch (\Throwable $ex){
                                $result[$k][$time_arr[$j]] = 0;
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
    public function rezhim_math_and_name($id_rezhim){
        return RezhimParams::where('rezhim_id', '=', $id_rezhim)->
            select('num_row',
            DB::raw('(CASE when calc = true then CONCAT(\'Формула: "\', calc_operations, \'"\')
            when from_hour_params = true then CONCAT(\'Ссылка на объект: "\',(select full_name from app_info.main_table where id = id_hour_param limit 1), \'"\')
            else \'\' end) as result
            '),
            DB::raw('(CASE when calc = true then 7
            when from_hour_params = true then 8
            else 0 end) as num_column
            '))->get()->where('result', '!=', '')->toArray();
    }
    public function rezhim_data_for_graph($param_id, $date_start, $date_stop)
    {
        $param_string = substr($param_id, 0, -1);
        $param_id = explode(",", $param_string);
        $date_end = date('d.m.Y 00:00', strtotime($date_stop.' +1 days'));
        $date_start = date('d.m.Y 00:00', strtotime($date_start));
        $sql = 'SELECT time.date, ';
        $joinrow = "(SELECT date_trunc('minute', time.timestamp) as date from rezhim_lists.rezhim_hour as time where time.param_id in ($param_string) and time.timestamp between date '$date_start' and date '$date_end' group by  time.timestamp order by time.timestamp) as time";
        foreach ($param_id as $key => $id) {
            if ($param_id[$key] != end($param_id)) {
                $sql .= 'p' . $key . '.val as val' . $key . ', ';
            } else {
                $sql .= 'p' . $key . '.val as val' . $key;
            }
            $joinrow .= " left join (SELECT date_trunc('minute', p$key.timestamp) as date_p$key, val from rezhim_lists.rezhim_hour as p$key where p$key.param_id=$id and p$key.timestamp between date '$date_start' and date '$date_end' ) as p$key";
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
    public function get_rezhim_data($id_rezhim, $date){
        $setting = Setting::get()->pluck('value', 'name_setting');
        try {
            $all_param_hour = RezhimParams::where('rezhim_id', '=', $id_rezhim)->where('from_hour_params', '=', false)->orderby('num_row')->select('name as full_name', 'e_unit', 'id')->get();
            $array_id = [];
            for ($j = 0; $j < 25; $j++) {
                $zero_array[$j] = ['id' => false];
            }
            $i = 0;
            foreach ($all_param_hour as $row) {
                array_push($array_id, $row->id);
                $result[$i]['id'] = $row->id;
                $result[$i]['full_name'] = $row->full_name;
                $result[$i]['e_unit'] = $row->e_unit;
                $result[$i] += $zero_array;
                $i++;
            }
            $disp_date_time = date('d.m.Y '.$setting['start_smena'].':00:00', strtotime($date));
            $data = RezhimHour::wherein('param_id', $array_id)->wherebetween('timestamp', [$disp_date_time,
                date('Y-m-d H:i', strtotime($disp_date_time . '+1439 minutes'))])->
            orderby('timestamp')->get();
            foreach ($data as $row) {
                $k = array_search((int)$row->param_id, $array_id);
                $j = (int)date('H', strtotime($row->timestamp . '- '.($setting['start_smena']-1).' hours'));
                if ($j == 0)
                    $j = 24;
                $result[$k][$j] = $row->toArray();
                if ($setting['visible_risk'] == 'true'){
                    if ($j!=1 && array_key_exists('val', $result[$k][$j - 1])) {
                        $result[$k][$j]['difference'] = ($result[$k][$j - 1]['val'] - $result[$k][$j]['val']) / ($result[$k][$j - 1]['val']+0.0001);
                        if($result[$k][$j]['difference'] <= 0){
                            $result[$k][$j]['class_img'] = 'highter';
                        }else{
                            $result[$k][$j]['class_img'] = 'lower';
                        }
                        if (abs($result[$k][$j]['difference']) > $setting['percent_hight_risk']/100){
                            $result[$k][$j]['visible'] = '';
                            $result[$k][$j]['class_img'] = 'very_'.$result[$k][$j]['class_img'];
                        }elseif(abs($result[$k][$j]['difference']) > $setting['percent_middle_risk']/100){
                            $result[$k][$j]['visible'] = '';
                        }else{
                            $result[$k][$j]['visible'] = 'display: none';
                        }
                    }else{
                        $result[$k][$j]['visible'] = 'display: none';
                    }
                }
            }
            $data_sut = RezhimSut::wherein('param_id', $array_id)->where('timestamp', '=', date('d.m.Y', strtotime($date)))->orderby('id')->get();
            foreach ($data_sut as $row) {
                $k = array_search((int)$row->param_id, $array_id);
                $result[$k][0] = $row->toArray();
                $result[$k][0]['visible'] = 'display: none';
            }
            return $result;
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function get_rezhim_list(){
        return RezhimLists::get();
    }
    public function delete_sut_confirm_rezhim(Request $request){
        $data = $request->all();
        RezhimCheck::where('id_rezhim', '=', $data['rezhim'])->where('confirm_param', '=', $data['date'])->delete();
        try {
            $rezhim_param = RezhimParams::where('rezhim_id', '=', $data['rezhim'])->where('hand', '!=', true)
                ->get()->pluck('id')->toArray();
            RezhimSut::wherein('param_id', $rezhim_param)->where('timestamp', '=', date('Y-m-d', strtotime($data['date'])))->delete();
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function sut_confirm_rezhim(Request $request){
        $data = $request->all();
        RezhimCheck::create(['id_rezhim'=>$data['rezhim'], 'confirm_param'=>$data['date']]);
        try {
            if (array_key_exists('data', $data)){
                $param_arr = RezhimParams::where('rezhim_id', '=', $data['rezhim'])->get()->pluck('id', 'num_row')->toArray();
                $keys = array_keys($data['data']);
                for ($i=0; $i<count($keys); $i++){
                    if (array_key_exists($keys[$i], $param_arr)){
                        RezhimSut::where('timestamp', '=', date('Y-m-d', strtotime($data['date'])))->where('param_id', '=', $param_arr[$keys[$i]])->delete();
                        RezhimSut::create(['param_id'=>$param_arr[$keys[$i]], 'timestamp'=> date('Y-m-d', strtotime($data['date'])), 'val'=>round($data['data'][$keys[$i]], 3)]);
                    }
                }
            }
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function delete_confirm_rezhim(Request $request){
        $data = $request->all();
        RezhimCheck::where('id_rezhim', '=', $data['rezhim'])->where('confirm_param', '=', $data['date'])->delete();
        try {
            $rezhim_param = RezhimParams::where('rezhim_id', '=', $data['rezhim'])->where('hand', '!=', true)
                ->get()->pluck('id')->toArray();
            RezhimHour::wherein('param_id', $rezhim_param)->where('timestamp', '=', date('Y-m-d H:i:00', strtotime($data['date'])))->delete();
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function confirm_rezhim(Request $request){
        $data = $request->all();
        RezhimCheck::create(['id_rezhim'=>$data['rezhim'], 'confirm_param'=>$data['date']]);
        try {
            if (array_key_exists('data', $data)){
                $param_arr = RezhimParams::where('rezhim_id', '=', $data['rezhim'])->get()->pluck('id', 'num_row')->toArray();
                $keys = array_keys($data['data']);
                for ($i=0; $i<count($keys); $i++){
                    if (array_key_exists($keys[$i], $param_arr)){
                        RezhimHour::where('timestamp', '=', date('Y-m-d H:i:00', strtotime($data['date'])))->where('param_id', '=', $param_arr[$keys[$i]])->delete();
                        RezhimHour::create(['param_id'=>$param_arr[$keys[$i]], 'timestamp'=> date('Y-m-d H:i:00', strtotime($data['date'])), 'val'=>round($data['data'][$keys[$i]],3)]);
                    }
                }
            }
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function save_hand_param(Request $request){
        try {
            $data = $request->all();
            $param_id = RezhimParams::where('rezhim_id', '=', $data['rezhim'])->where('num_row', '=', $data['numRow'])->first()->id;
            $hand_param = RezhimHour::where('timestamp', '=', date('Y-m-d H:i', strtotime($data['date'])))->where('param_id', '=', $param_id)->get();
            if (count($hand_param)>0){
                RezhimHour::where('timestamp', '=', date('Y-m-d H:i', strtotime($data['date'])))->where('param_id', '=', $param_id)->update(['val'=>round($data['value'],3)]);
            }else{
                RezhimHour::create(['param_id'=>$param_id, 'val'=>round($data['value'],3), 'timestamp'=>date('Y-m-d H:i', strtotime($data['date']))]);
            }
        }catch (\Throwable $e){
            return $e;
        }

    }
    public function save_hand_param_sut(Request $request){
        try {
            $data = $request->all();
            if ($data['date'] == 'sum'){
                if (RezhimParams::where('rezhim_id', '=', $data['rezhim'])->where('num_row', '=', $data['numRow'])->first()->sum){
                    RezhimParams::where('rezhim_id', '=', $data['rezhim'])->where('num_row', '=', $data['numRow'])->update(['sum'=>false]);
                }else{
                    RezhimParams::where('rezhim_id', '=', $data['rezhim'])->where('num_row', '=', $data['numRow'])->update(['sum'=>true]);
                }
            }else{
                $param_id = RezhimParams::where('rezhim_id', '=', $data['rezhim'])->where('num_row', '=', $data['numRow'])->first()->id;
                if (!RezhimParams::where('id', '=', $param_id)->first()->calc){
                    $hand_param = RezhimSut::where('timestamp', '=', date('Y-m-d', strtotime($data['date'])))->where('param_id', '=', $param_id)->get();
                    if (count($hand_param)>0){
                        RezhimSut::where('timestamp', '=', date('Y-m-d', strtotime($data['date'])))->where('param_id', '=', $param_id)->update(['val'=>round($data['value'],3)]);
                    }else{
                        RezhimSut::create(['param_id'=>$param_id, 'val'=>round($data['value'],3), 'timestamp'=>date('Y-m-d', strtotime($data['date']))]);
                    }
                }
            }

        }catch (\Throwable $e){
            return $e;
        }

    }
    public function save_formula(Request $request){
        $data = $request->all();
        $data['formula'] = str_replace([' ', '{', '}', ','], ['', 'Line', '', '.'], $data['formula']);
        RezhimParams::where('id', '=', $data['param_id'])->update(['calc'=>true, 'calc_operations' => '='.$data['formula'], 'hand'=>false, 'from_hour_params'=>false, 'id_hour_param'=>null, 'empty'=>false, 'sum'=>'false']);
    }
    public function rezhim_data($id_rezhim, $date){
        $result = RezhimParams::where('rezhim_id', '=', $id_rezhim)->orderby('num_row')
            ->select('id',
                DB::raw('(CASE WHEN hand = true THEN
                \'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1)"><path d="M19.045 7.401c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.378-.378-.88-.586-1.414-.586s-1.036.208-1.413.585L4 13.585V18h4.413L19.045 7.401zm-3-3 1.587 1.585-1.59 1.584-1.586-1.585 1.589-1.584zM6 16v-1.585l7.04-7.018 1.586 1.586L7.587 16H6zm-2 4h16v2H4z"></path></svg>\'
                WHEN calc = true THEN
                \'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1)"><path d="M19 2H5c-1.103 0-2 .897-2 2v16c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zM5 20V4h14l.001 16H5z"></path><path d="M7 12h2v2H7zm0 4h2v2H7zm4-4h2v2h-2zM7 6h10v4H7zm4 10h2v2h-2zm4-4h2v6h-2z"></path></svg>\'
                WHEN from_hour_params = true THEN
                \'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1)"><path d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z"></path><path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z"></path></svg>\'
                ELSE \'\' END) AS img'),
                DB::raw('
case when folder = true then
        concat(
            \'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="padding-left: \',
            level_row*10 - 10,
            \'px ;fill: rgba(0, 0, 0, 1); float: left"><path d="M2.165 19.551c.186.28.499.449.835.449h15c.4 0 .762-.238.919-.606l3-7A.998.998 0 0 0 21 11h-1V7c0-1.103-.897-2-2-2h-6.1L9.616 3.213A.997.997 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14h.007a1 1 0 0 0 .158.551zM17.341 18H4.517l2.143-5h12.824l-2.143 5zM18 7v4H6c-.4 0-.762.238-.919.606L4 14.129V7h14z"></path></svg>\'
            \'<span style="width: calc(100% - 25px - \',
            level_row*10 - 10,
            \'px);float: right; text-align: left">\',
            name,
            \'</span>\'
            )
    else
        concat(
            \'<span style="width: calc(100% - \',
            level_row*10 - 10,
            \'px); padding-left: \',
            level_row*10 - 10,
            \'px;float: right; text-align: left">\',
            name,
            \'</span>\'
            )
end as full_name
                '),
                'e_unit'
            )
            ->get()->toArray();
        ///Получения начала часовок и текущего времени
        $setting = Setting::get()->pluck('value', 'name_setting');
        $date_start = date('H:00 d.m.Y', strtotime($date.' '.($setting['start_smena']).':00'));
        $date_stop = date('H:00 d.m.Y', strtotime($date_start.' +1 days'));
        $cur_date = date('H:00 d.m.Y', strtotime('+1 hour'));
        if(strtotime($cur_date) < strtotime($date_stop)) {
            $date_stop = date('H:00 d.m.Y', strtotime($cur_date));
        }else{
            $date_stop = date('H:00 d.m.Y', strtotime($date_stop.' -1 hour'));
        }
        ///Получаем параметры
        $params = RezhimParams::where('rezhim_id', '=', $id_rezhim)->orderby('num_row')->get()->toArray();
        ///Заполняем начальный массив $result['column'][время(будущий столбец)][параметр(номер строки)] = значение в ячейку
        $time_arr = [];
        while (strtotime($date_start) <= strtotime($date_stop)){
            array_push($time_arr, $date_stop);
            $date_stop = date('H:00 d.m.Y', strtotime($date_stop.' -1 hour'));
        }
        ///Получение часовок в формате $hour[время] и внутри два массива - param_id и val
        $hour_param = Hour_params::wherebetween('timestamp', [date('Y-m-d H:i', strtotime(end($time_arr))), date('Y-m-d H:i', strtotime($time_arr[0]))])
            ->wherein('param_id', RezhimParams::whereNotNull('id_hour_param')->get()->pluck('id_hour_param'))
            ->groupby('timestamp')
            ->select(DB::raw("to_char(timestamp, 'HH24:MI dd.mm.yyyy') as timestamp"), DB::raw("array_agg(param_id) as param_id, array_agg(val) as val"))
            ->get();
        $hour_array = [];
        foreach ($hour_param as $row){
            $hour_array[$row->timestamp]['val'] = explode(',', str_replace( ['{', '}', '"'], '',$row->val));
            $hour_array[$row->timestamp]['param_id'] = explode(',', str_replace( ['{', '}', '"'], '',$row->param_id));
        }
        ///Получение ручного ввода в формате $hour[время] и внутри два массива - param_id и val
        $hand_param = RezhimHour::wherebetween('timestamp', [date('Y-m-d H:i', strtotime(end($time_arr))), date('Y-m-d H:i', strtotime($time_arr[0]))])
            ->wherein('param_id', RezhimParams::where('rezhim_id', '=', $id_rezhim)->get()->pluck('id'))
            ->groupby('timestamp')
            ->select(DB::raw("to_char(timestamp, 'HH24:MI dd.mm.yyyy') as timestamp"), DB::raw("array_agg(param_id) as param_id, array_agg(val) as val"))
            ->get();
        $hand_array = [];
        foreach ($hand_param as $row){
            $hand_array[$row->timestamp]['val'] = explode(',', str_replace( ['{', '}', '"'], '',$row->val));
            $hand_array[$row->timestamp]['param_id'] = explode(',', str_replace( ['{', '}', '"'], '',$row->param_id));
        }
        $array_column = ['E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD'];
        ///Получили подтвержденные часовки
        $check_rezhim = RezhimCheck::where('id_rezhim', '=', $id_rezhim)->get()->pluck('confirm_param')->toArray();
        $result['hidden_column'] = [];

        $result['hidden_rows'] = [];
        for ($k=0; $k<count($params); $k++){
            for ($j=0; $j<count($time_arr); $j++){
                if (gettype(array_search($time_arr[$j], $check_rezhim)) == 'integer') {  ///Если сутки подтвердили
                    array_push($result['hidden_column'], (4+$j));
                    if (!$params[$k]['empty']){
                        try {
                            if (gettype(array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                $result[$k][$time_arr[$j]] = $hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])];
                            }else{
                                $result[$k][$time_arr[$j]] = 0;
                            }
                        }catch (\Throwable $e){
                            $result[$k][$time_arr[$j]] = 0;
                        }
                    }else{
                        $result[$k][$time_arr[$j]] = '';
                    }
                }else{
                    if ($params[$k]['hand']){
                        try {
                            if (gettype(array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                $result[$k][$time_arr[$j]] = $hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])];
                            }else{
                                $result[$k][$time_arr[$j]] = 0;
                            }
                        }catch (\Throwable $e){
                            $result[$k][$time_arr[$j]] = 0;
                        }
                    }elseif ($params[$k]['calc']){
                        array_push($result['hidden_rows'], $k);
                        try {
                            if (gettype(array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])) == 'integer'){
                                $result[$k][$time_arr[$j]] = $hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id'], $hand_array[$time_arr[$j]]['param_id'])];
                            }else{
                                $result[$k][$time_arr[$j]] = str_replace(['Line'], [$array_column[$j]], $params[$k]['calc_operations']);
                            }
                        }catch (\Throwable $e){
                            $result[$k][$time_arr[$j]] = str_replace(['Line'], [$array_column[$j]], $params[$k]['calc_operations']);
                        }

                    }elseif($params[$k]['empty']){
                        array_push($result['hidden_rows'], $k);
                        $result[$k][$time_arr[$j]] = '';
                    }else{
                        array_push($result['hidden_rows'], $k);
                        try {
                            $result[$k][$time_arr[$j]] = $hour_array[$time_arr[$j]]['val'][array_search($params[$k]['id_hour_param'],$hour_array[$time_arr[$j]]['param_id'])];
                        }catch (\Throwable $ex){
                            $result[$k][$time_arr[$j]] = 0;
                        }
                    }
                }
            }
        }
        $result['hidden_column'] = [];
        for ($i = 0; $i<count($time_arr); $i++){
            if (gettype(array_search($time_arr[$i], $check_rezhim)) == 'integer'){
                array_push($result['hidden_column'], (4+$i));
            }
        }
        return $result;
    }

    public function rezhim_list($id_rezhim){
        $name_rezhim = RezhimLists::find($id_rezhim)->first()->name_rezhim;
        return view('web.rezhim.rezhim_list', compact('name_rezhim', 'id_rezhim'));
    }
    public function sut_rezhim_list($id_rezhim){
        $name_rezhim = RezhimLists::find($id_rezhim)->first()->name_rezhim;
        return view('web.rezhim.sut_rezhim_list', compact('name_rezhim', 'id_rezhim'));
    }
    public function select_param($id_rezhim, $id_param){
        $data = TableObj::where('inout', '!=', '!')->orderby('main_table.id')
            ->get();
        return view('web.rezhim.links', compact('data', 'id_rezhim', 'id_param'));
    }
    public function delete_rezhim($id){
        RezhimLists::find($id)->delete();
        RezhimParams::where('rezhim_id', '=', $id)->delete();
    }
    public function save_select_param($id_param, $select_id){
        RezhimParams::find($id_param)->update(['id_hour_param'=>$select_id, 'from_hour_params'=>true, 'calc'=>false, 'hand'=>false, 'calc_operations'=>'', 'empty'=>'false', 'sum'=>'false']);
    }
    public function edit_rezhim(Request $request, $id_rezhim){
        $data = $request->all();
        if ($data['column'] != 'hand' && $data['column'] != 'calc' && $data['column'] != 'from_hour_params' && $data['column'] != 'num_row' && $data['column'] != 'empty'){
            RezhimParams::find($data['id'])->update([$data['column']=>$data['value']]);
        }else{
            switch ($data['column']){
                case 'hand':
                    RezhimParams::find($data['id'])->
                    update(
                        [
                            'hand'=>'true',
                            'sum'=>'false',
                            'empty'=>'false',
                            'id_hour_param'=>null,
                            'from_hour_params'=>'false',
                            'calc_operations'=>'',
                            'calc'=>'false',
                        ]);
                    break;
                case 'empty':
                    RezhimParams::find($data['id'])->
                    update(
                        [
                            'hand'=>'false',
                            'sum'=>'false',
                            'empty'=>'true',
                            'id_hour_param'=>null,
                            'from_hour_params'=>'false',
                            'calc_operations'=>'',
                            'calc'=>'false',
                        ]);
                    break;
                case 'calc':
                    return 'calc';
                case 'num_row':
                    $params = RezhimParams::where('rezhim_id', '=', $id_rezhim)->get();
                    $max_row = count($params);
                    if ($data['value'] > $max_row){
                        $data['value'] = $max_row;
                    }elseif ($data['value']<0){
                        $data['value'] = 1;
                    }
                    $current_number = $params->where('id', '=', $data['id'])->first()->num_row;
                    RezhimParams::where('rezhim_id', '=', $id_rezhim)->
                    where('num_row', '>=', $data['value'])->
                    where('num_row', '<', $current_number)->
                    update(['num_row'=>DB::raw( 'num_row + 1')]);
                    RezhimParams::where('rezhim_id', '=', $id_rezhim)->
                    where('num_row', '<=', $data['value'])->
                    where('num_row', '>', $current_number)->
                    update(['num_row'=>DB::raw( 'num_row - 1')]);
                    RezhimParams::find($data['id'])->update(['num_row'=>$data['value']]);
                    break;
                case 'from_hour_params':
                    return $data['id'];
            }
        }
    }
    public function admin_rezhim_lists($id){
        if ($id == 'false'){
            return view('web.rezhim.admin_rezhim_lists', compact('id'));
        }else{
            $name_rezhim = RezhimLists::find($id)->first()->name_rezhim;
            return view('web.rezhim.admin_rezhim_lists', compact('id', 'name_rezhim'));
        }
    }
    public function create_new_rezhim(Request $request){
        try {
            $new_rec = RezhimLists::create($request->all());
            RezhimParams::create(['rezhim_id'=>$new_rec->id]);
            return $new_rec->id;
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function get_rezhim_params($id){
        return RezhimParams::where('rezhim_id', '=', $id)->orderby('num_row')->select('id','num_row','name','e_unit','level_row','folder','hand','calc','from_hour_params','empty')->get();
    }
    public function update_name(Request $request, $id){
        RezhimLists::find($id)->update($request->all());
    }
    public function create_new_param($id){
        RezhimParams::where('rezhim_id', '=', $id)->update(['num_row'=>DB::raw('num_row + 1')]);
        RezhimParams::create(['rezhim_id'=>$id]);
    }
    public function delete_rezhim_params(Request $request, $id_rezhim){
        try {
            $data = $request->all();
            $arr = explode(',', $data['id']);
            array_pop($arr);
            RezhimParams::wherein('id', $arr)->delete();
            $i=1;
            foreach (RezhimParams::where('rezhim_id', '=', $id_rezhim)->orderby('num_row')->get() as $row){
                $row->update(['num_row'=>$i]);
                $i++;
            }
        }catch (\Throwable $e){
            return $e;
        }
    }

}

?>
