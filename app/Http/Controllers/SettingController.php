<?php

namespace App\Http\Controllers;


use App\Models\Setting;
use App\Models\TableObj;

class SettingController extends Controller
{
    public function signal_settings($id_param){
        $data = TableObj::where('inout', '!=', '!')->orderby('id')->get();
        return view('web.signal_settings', compact('data', 'id_param'));
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
        if (TableObj::where('id', '=', $id)->first()->hour_param){
            TableObj::where('id', '=', $id)->update(['hour_param'=>false]);
        }else{
            TableObj::where('id', '=', $id)->update(['hour_param'=>true]);
        }
    }
    public function delete_param($id){
        (new MainController)->create_log_record('Удаление параметра','Параметр "'.TableObj::where('id', '=', $id)->first()->full_name.'"');
        TableObj::where('id', '=', $id)->delete();
    }
}


