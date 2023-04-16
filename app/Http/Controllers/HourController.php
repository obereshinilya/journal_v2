<?php

namespace App\Http\Controllers;


use App\Models\Hour_params;
use App\Models\Min_params;
use App\Models\Setting;
use App\Models\Sut_params;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HourController extends Controller
{
    public function main(){
        $start_hour = Setting::where('name_setting', '=', 'start_smena')->first()->value;
        return view('web.time_param_hour', compact('start_hour'));
    }

    public function get_hour_param($date){
        try {
            $all_param_hour = TableObj::where('hour_param', '=', true)->select('id', 'full_name', 'e_unit')->orderby('full_name')->get();
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
            $start_hour = Setting::where('name_setting', '=', 'start_smena')->first()->value;
            $disp_date_time = date('d.m.Y '.$start_hour.':00:00', strtotime($date));
            $data = Hour_params::wherein('param_id', $array_id)->wherebetween('timestamp', [$disp_date_time,
                date('Y-m-d H:i', strtotime($disp_date_time . '+1439 minutes'))])->
            orderby('id')->get();
            foreach ($data as $row) {
                $k = array_search((int)$row->param_id, $array_id);
                $j = (int)date('H', strtotime($row->timestamp . '- '.($start_hour-1).' hours'));
                if ($j == 0)
                    $j = 24;
                $result[$k][$j] = $row->toArray();
            }
            $data_sut = Sut_params::wherein('param_id', $array_id)->where('timestamp', '=', date('d.m.Y', strtotime($date)))->orderby('id')->get();
            foreach ($data_sut as $row) {
                $k = array_search((int)$row->param_id, $array_id);
                $result[$k][0] = $row->toArray();
            }
            return $result;
        }catch (\Throwable $e){
            return $e;
        }
    }

    public function hours_param_minutes($date, $hour){
        $start_hour = Setting::where('name_setting', '=', 'start_smena')->first()->value;
        if ((int) $hour < $start_hour){
            $date = date('d.m.Y', strtotime($date.' +1 days'));
        }
        $date_start = $date . ' ' . date("$hour:05");
        $date_stop = date('Y-m-d H:59', strtotime($date_start));
        $data = TableObj::where('hour_param', '=', true)->select('id')->get();
        $i = 0;
        $array_id = [];
        $zero_array = [null, null, null, null, null, null, null, null, null, null, null];
        foreach ($data as $row) {
            array_push($array_id, $row->id);
            $result[$i] = $zero_array;
            $i++;
        }
        $buff_data = Min_params::wherein('param_id', $array_id)
            ->whereBetween('timestamp', [date('Y-m-d H:i', strtotime($date_start)),
                date('Y-m-d H:i', strtotime($date_stop))])->get();
        foreach ($buff_data as $row) {
            $k = array_search((int)$row->hfrpok_id, $array_id);
            $j = floor(((int)date('i', strtotime($row->timestamp))) / 5);
            $result[$k][$j] = $row->val;
        }
        return $result;
    }

    public function update_param($value, $id, $sutki){
        if ($sutki == 'false'){
            Hour_params::where('id', '=', $id)->update(['val'=>$value, 'manual'=>true, 'change_by'=>Auth::user()->displayname[0]]);
            (new MainController)->create_log_record('Обновил часовой показатель за '.date('H:i d.m.Y', strtotime(Hour_params::where('id', '=', $id)->first()->timestamp)));
        }else{
            Sut_params::where('id', '=', $id)->update(['val'=>$value, 'manual'=>true, 'change_by'=>Auth::user()->displayname[0]]);
            (new MainController)->create_log_record('Обновил суточный показатель за '.date('d.m.Y', strtotime(Sut_params::where('id', '=', $id)->first()->timestamp)));
        }
        return true;
    }
    public function create_param($value, $timestamp, $date, $param_id){
        $start_hour = Setting::where('name_setting', '=', 'start_smena')->first()->value;
        if ($timestamp == 'false'){ //Если суточный
            if (date('d.m.Y '.$start_hour.':00', strtotime($date.' +1 days')) < date('d.m.Y H:i')){
                Sut_params::create(['val'=>$value, 'timestamp'=>$date, 'param_id'=>$param_id, 'manual'=>true, 'change_by'=>Auth::user()->displayname[0]]);
                (new MainController)->create_log_record('Записал суточный показатель за '.date('d.m.Y', strtotime(Sut_params::orderbydesc('id')->first()->timestamp)));
            }else{
                return 'false';
            }
        }else{
            if ((int) $timestamp < $start_hour){
                $date = date('d.m.Y', strtotime($date.' +1 days'));
            }
            $timestamp = date('d.m.Y H:i', strtotime($date.' '.$timestamp.':00:10'));
            if (strtotime(date('d.m.Y H:i')) < strtotime($timestamp)){
                return 'false';
            }else{
                Hour_params::create(['val'=>$value, 'timestamp'=>$timestamp, 'param_id'=>$param_id, 'manual'=>true, 'change_by'=>Auth::user()->displayname[0]]);
                (new MainController)->create_log_record('Записал часовой показатель за '.date('H:i d.m.Y', strtotime(Hour_params::orderbydesc('id')->first()->timestamp)));
            }
        }
    }

    public function save_comment(Request $request, $id, $type){
        try {
            if ($type == 'sutki'){
                $comment = Sut_params::where('id', '=', $id)->first()->comment;
                if ($comment){
                    Sut_params::where('id', '=', $id)->update(['comment'=>$comment.' <br> '.Auth::user()->displayname[0].':<br><em>'.$request->all()['text'].'</em>']);
                }else{
                    Sut_params::where('id', '=', $id)->update(['comment'=>'<b>Комментарий:</b><br>'.Auth::user()->displayname[0].':<br><em>'.$request->all()['text'].'</em>']);
                }
            }else{
                $comment = Hour_params::where('id', '=', $id)->first()->comment;
                if ($comment){
                    Hour_params::where('id', '=', $id)->update(['comment'=>$comment.' <br> '.Auth::user()->displayname[0].':<br><em>'.$request->all()['text'].'</em>']);
                }else{
                    Hour_params::where('id', '=', $id)->update(['comment'=>'<b>Комментарий:</b><br>'.Auth::user()->displayname[0].':<br><em>'.$request->all()['text'].'</em>']);
                }
            }
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function delete_comment($id, $type){
        if ($type == 'sutki'){
            Sut_params::where('id', '=', $id)->update(['comment'=>'']);
        }else{
            Hour_params::where('id', '=', $id)->update(['comment'=>'']);
        }
    }



}


