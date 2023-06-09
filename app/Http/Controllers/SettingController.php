<?php

namespace App\Http\Controllers;


use App\Models\HiddenHour;
use App\Models\rezhim\RezhimParams;
use App\Models\Setting;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function main_setting(){
        $setting = Setting::get()->pluck('value', 'name_setting');
        return view('web.main_setting', compact('setting'));
    }
    public function save_main_setting(Request $request, $param){
        $data = $request->all();
        $value = $data['value'];
        $from_db = Setting::where('name_setting', '=', $param)->first();
        if ($value !== 'false'){
            if ($param == 'start_smena'){
                $hour = substr($value, 0, -3);
                if ($hour[0] == 0){
                    $hour = $hour[1];
                }
                $from_db->update(['value'=>$hour]);
            }else{
                $from_db->update(['value'=>$value]);
            }
            (new MainController)->create_log_record('Изменение настроек','Изменил '.$from_db->comment);
        }else{
            if ($from_db->value == 'true'){
                (new MainController)->create_log_record('Изменение настроек','Запретил '.$from_db->comment);
                $from_db->update(['value'=>'false']);
            }else{
                (new MainController)->create_log_record('Изменение настроек','Разрешил '.$from_db->comment);
                $from_db->update(['value'=>'true']);
            }
        }
    }
    public function save_opc($param, $value){
        $from_db = Setting::where('name_setting', '=', $param)->first()->update(['value'=>$value]);
    }

    public function signal_settings($id_param){
        $data = TableObj::where('inout', '!=', '!')->orderby('main_table.id')
            ->get();
        $hidden_hour = HiddenHour::where('login_user', '=', Auth::user()->cn[0])->get()->pluck('param_id')->toArray();
        return view('web.signal_settings', compact('data', 'id_param', 'hidden_hour'));
    }
    public function save_signal_settings($id, $name_param, $new_value){
        if ($new_value == 'false'){
            TableObj::where('id', '=', $id)->update([$name_param=>null]);
            if ($name_param == 'guid_masdu_min'){}
        }else{
            TableObj::where('id', '=', $id)->update([$name_param=>$new_value]);
        }
    }
    public function visible_param($id){
        if (HiddenHour::where('param_id', '=', $id)->where('login_user', '=', Auth::user()->cn[0])->first()){
            HiddenHour::where('param_id', '=', $id)->where('login_user', '=', Auth::user()->cn[0])->delete();
        }else{
            HiddenHour::create(['param_id'=>$id, 'login_user'=>Auth::user()->cn[0]]);
        }
    }
    public function delete_param($id){
        (new MainController)->create_log_record('Удаление параметра','Параметр "'.TableObj::where('id', '=', $id)->first()->full_name.'"');
        TableObj::where('id', '=', $id)->delete();
        try {
            RezhimParams::where('id_hour_param', '=', $id)->first()->update(['from_hour_params'=>false, 'hand'=>true, 'id_hour_param'=>null]);
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function check_param_in_rezhim($id){
        try {
            if (count(RezhimParams::where('id_hour_param', '=', $id)->get())>0){
                return 'false';
            }
        }catch (\Throwable $e){

        }
    }
}


