<?php

namespace App\Http\Controllers;


use App\Exports\HourExport;
use App\Models\CustomLists;
use App\Models\HiddenHour;
use App\Models\Hour_params;
use App\Models\Min_params;
use App\Models\Setting;
use App\Models\Sut_params;
use App\Models\TableObj;
use App\Models\UserCustomList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HourController extends Controller
{
    public function post_copy_custom_list($id_list){
        UserCustomList::create(['user'=>Auth::user()->cn[0], 'id_list'=>$id_list]);
    }
    public function get_custom_list(){
        $user = Auth::user()->cn[0];
        return CustomLists::whereNotIn('custom_lists.id', UserCustomList::where('user', '=', $user)->get()->pluck('id_list'))
            ->join('chat.users', 'custom_lists.user', '=', 'users.login')
            ->select('name_list', 'display_name', 'custom_lists.id')
            ->get();
    }
    public function hide_list($id_list){
        UserCustomList::where('user', '=', Auth::user()->cn[0])->where('id_list', '=', $id_list)->delete();
    }
    public function delete_list($id_list){
        CustomLists::where('id', '=', $id_list)->delete();
        UserCustomList::where('id_list', '=', $id_list)->delete();
    }
    public function custom_param_minutes($id_list, $date, $hour){
        $start_hour = Setting::where('name_setting', '=', 'start_smena')->first()->value;
        if ((int) $hour < $start_hour){
            $date = date('d.m.Y', strtotime($date.' +1 days'));
        }
        $date_start = $date . ' ' . date("$hour:05");
        $date_stop = date('Y-m-d H:59', strtotime($date_start));
        $param_from_list = explode(',', CustomLists::where('id', '=', $id_list)->first()->param_id);
        $data = TableObj::whereIn('id', $param_from_list)->where('inout', '!=', '!')->orderby('full_name')->get();
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
            $k = array_search((int)$row->param_id, $array_id);
            $j = floor(((int)date('i', strtotime($row->timestamp))) / 5);
            $result[$k][$j] = $row->val;
        }
        return $result;
    }
    public function get_custom_data($id_list, $date){
        $setting = Setting::get()->pluck('value', 'name_setting');
        try {
            $all_param_hour = DB::table('app_info.main_table')->
                join(DB::raw('(SELECT unnest(string_to_array(param_id, \',\'))::int AS param, id FROM app_info.custom_lists where id = \''.$id_list.'\') as lists'),
                    'main_table.id','=','lists.param')
                ->select('full_name', 'e_unit', 'param as id')
                ->get();
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
            $data = Hour_params::wherein('param_id', $array_id)->wherebetween('timestamp', [$disp_date_time,
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
            $data_sut = Sut_params::wherein('param_id', $array_id)->where('timestamp', '=', date('d.m.Y', strtotime($date)))->orderby('id')->get();
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
    function get_user_lists(){
        return UserCustomList::where('user_custom_list.user', '=', Auth::user()->cn[0])
            ->join('app_info.custom_lists', 'user_custom_list.id_list', '=', 'custom_lists.id')
            ->select('id_list', 'name_list')
            ->get();
    }
    public function save_list(Request $request){
        try {
            $data = $request->all();
            if (count(CustomLists::where('name_list', '=', $data['name_list'])->get())>0){
                return 'false';
            }else{
                $new_list = CustomLists::create(['name_list'=>$data['name_list'], 'user'=>Auth::user()->cn[0], 'param_id'=>implode(",", $data['param_ids'])]);
                UserCustomList::create(['user'=>Auth::user()->cn[0], 'id_list'=>$new_list->id]);
            }
        }catch (\Throwable $e){
            return  $e;
        }
    }
    public function update_list(Request $request, $id_list){
        try {
            $data = $request->all();
            if (count(CustomLists::where('id', '!=', $id_list)->where('name_list', '=', $data['name_list'])->get())>0){
                return 'false';
            }else{
                CustomLists::where('id', '=', $id_list)->first()->update(['name_list'=>$data['name_list'], 'user'=>Auth::user()->cn[0], 'param_id'=>implode(",", $data['param_ids'])]);
            }
        }catch (\Throwable $e){
            return  $e;
        }
    }
    public function custom_list($id_list){
        $data = TableObj::where('inout', '!=', '!')->orderby('main_table.id')
            ->get();
        if ($id_list != 'false'){
            $param_from_list = CustomLists::where('id', '=', $id_list)->first();
        }else{
            $param_from_list = 'false';
        }
        return view('web.custom_list', compact('data', 'param_from_list'));
    }
    public function main(){
        $setting = Setting::get()->pluck('value', 'name_setting');
        return view('web.time_param_hour', compact('setting'));
    }

    public function get_hour_param($date){
//        $start = microtime(true);
        $setting = Setting::get()->pluck('value', 'name_setting');
        try {
            $hidden_hour = HiddenHour::where('login_user', '=', Auth::user()->cn[0])->get()->pluck('param_id');
            $all_param_hour = TableObj::whereNotIn('id', $hidden_hour)->where('inout', '!=', '!')->select('id', 'full_name', 'e_unit')->orderby('full_name')->get();
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
            $data = Hour_params::wherein('param_id', $array_id)->wherebetween('timestamp', [$disp_date_time,
                date('Y-m-d H:i', strtotime($disp_date_time . '+1499 minutes'))])->
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
            $data_sut = Sut_params::wherein('param_id', $array_id)->where('timestamp', '=', date('d.m.Y', strtotime($date)))->orderby('id')->get();
            foreach ($data_sut as $row) {
                $k = array_search((int)$row->param_id, $array_id);
                $result[$k][0] = $row->toArray();
                $result[$k][0]['visible'] = 'display: none';
            }
//            echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';
//            dd($result);
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
        $hidden_hour = HiddenHour::where('login_user', '=', Auth::user()->cn[0])->get()->pluck('param_id');
        $data = TableObj::whereNotIn('id', $hidden_hour)->where('inout', '!=', '!')->orderby('full_name')->get();
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
            $k = array_search((int)$row->param_id, $array_id);
            $j = floor(((int)date('i', strtotime($row->timestamp))) / 5);
            $result[$k][$j] = $row->val;
        }
        return $result;
    }

    public function update_param($value, $id, $sutki){
        if ($sutki == 'false'){
            Hour_params::where('id', '=', $id)->update(['val'=>$value, 'manual'=>true, 'change_by'=>'<br>'.Auth::user()->displayname[0].'<br>В '.date('H:i d.m.Y')]);
            (new MainController)->create_log_record('Обновление часового показателя','За '.date('H:i d.m.Y', strtotime(Hour_params::where('id', '=', $id)->first()->timestamp)));
        }else{
            Sut_params::where('id', '=', $id)->update(['val'=>$value, 'manual'=>true, 'change_by'=>'<br>'.Auth::user()->displayname[0].'<br>В '.date('H:i d.m.Y')]);
            (new MainController)->create_log_record('Обновление суточного показателя','За '.date('d.m.Y', strtotime(Sut_params::where('id', '=', $id)->first()->timestamp)));
        }
        return true;
    }
    public function create_param($value, $timestamp, $date, $param_id){
        $start_hour = Setting::where('name_setting', '=', 'start_smena')->first()->value;
        if ($timestamp == 'false'){ //Если суточный
            if (strtotime(date('d.m.Y '.$start_hour.':00', strtotime($date.' +1 days'))) < strtotime(date('d.m.Y H:i'))){
                Sut_params::create(['val'=>$value, 'timestamp'=>$date, 'param_id'=>$param_id, 'manual'=>true, 'change_by'=>'<br>'.Auth::user()->displayname[0].'<br>В '.date('H:i d.m.Y')]);
                (new MainController)->create_log_record('Запись суточного показателя','За '.date('d.m.Y', strtotime(Sut_params::orderbydesc('id')->first()->timestamp)));
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
                Hour_params::create(['val'=>$value, 'timestamp'=>$timestamp, 'param_id'=>$param_id, 'manual'=>true, 'change_by'=>'<br>'.Auth::user()->displayname[0].'<br>В '.date('H:i d.m.Y')]);
                (new MainController)->create_log_record('Запись часового показателя','За '.date('H:i d.m.Y', strtotime(Hour_params::orderbydesc('id')->first()->timestamp)));
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

    public function print_hour($date){
        $start_hour = Setting::where('name_setting', '=', 'start_smena')->first()->value;
        return view('web.pdf.hour_param', compact('date', 'start_hour'));
    }
    public function excel_hour($date){
        $data = (new HourController)->get_hour_param($date);
        $start_hour = Setting::where('name_setting', '=', 'start_smena')->first()->value;
        $title = 'Часовые показатели за ' . $date;
        $patch = 'Hour_' . date('Y_m_d', strtotime($date)) . '.xlsx';
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new HourExport($title, $data, $start_hour), $patch);

    }

}


