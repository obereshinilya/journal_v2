<?php

namespace App\Http\Controllers;

use App\Models\Hour_params;
use App\Models\rezhim\RezhimHour;
use App\Models\rezhim\RezhimLists;
use App\Models\rezhim\RezhimParams;
use App\Models\Setting;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RezhimController extends Controller
{
    public function save_formula(Request $request){
        $data = $request->all();
        $data['formula'] = str_replace([' ', '{', '}', ','], ['', 'Param', '', '.'], $data['formula']);
        RezhimParams::where('id', '=', $data['param_id'])->update(['calc'=>true, 'calc_operations' => '='.$data['formula'], 'hand'=>false, 'from_hour_params'=>false, 'id_hour_param'=>null]);
    }
    public function rezhim_table_data($id_rezhim, $date){
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
        ///Заполняем начальный массив $result[время(будущий столбец)][параметр(номер строки)] = значение в ячейку
        $zero_array = array_fill(0, count($params), '');
        $time_arr = [];
        while (strtotime($date_start) <= strtotime($date_stop)){
            $result[$date_stop] = $zero_array;
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
            ->wherein('param_id', RezhimParams::where('rezhim_id', '=', $id_rezhim)->where('hand', '=', true)->get()->pluck('id'))
            ->groupby('timestamp')
            ->select(DB::raw("to_char(timestamp, 'HH24:MI dd.mm.yyyy') as timestamp"), DB::raw("array_agg(param_id) as param_id, array_agg(val) as val"))
            ->get();
//        dd($hand_param);
        $hand_array = [];
        foreach ($hand_param as $row){
            $hand_array[$row->timestamp]['val'] = explode(',', str_replace( ['{', '}', '"'], '',$row->val));
            $hand_array[$row->timestamp]['param_id'] = explode(',', str_replace( ['{', '}', '"'], '',$row->param_id));
        }
        $array_column = ['E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD'];
        for ($j=0; $j<count($time_arr); $j++){
            for ($k=0; $k<count($params); $k++){
                if ($params[$k]['hand']){
                    try {
                        $result[$time_arr[$j]][$k] = $hand_array[$time_arr[$j]]['val'][array_search($params[$k]['id_hour_param'],$hand_array[$time_arr[$j]]['param_id'])];
                    }catch (\Throwable $e){
                        $result[$time_arr[$j]][$k] = 0;
                    }
                }elseif ($params[$k]['calc']){
                    $result[$time_arr[$j]][$k] = str_replace(['Param'], [$array_column[$j]], $params[$k]['calc_operations']);
                }else{
                    try {
                        $result[$time_arr[$j]][$k] = $hour_array[$time_arr[$j]]['val'][array_search($params[$k]['id_hour_param'],$hour_array[$time_arr[$j]]['param_id'])];
                    }catch (\Throwable $e){
                        $result[$time_arr[$j]][$k] = 0;
                    }
                }
            }
        }
        return$result;
    }
    public function rezhim_data($id_rezhim){
        return RezhimParams::where('rezhim_id', '=', $id_rezhim)->orderby('num_row')
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
            ->get();
//        DB::raw('CONCAT(
//                    (CASE WHEN folder = true THEN
//                    \'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); float: left"><path d="M2.165 19.551c.186.28.499.449.835.449h15c.4 0 .762-.238.919-.606l3-7A.998.998 0 0 0 21 11h-1V7c0-1.103-.897-2-2-2h-6.1L9.616 3.213A.997.997 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14h.007a1 1 0 0 0 .158.551zM17.341 18H4.517l2.143-5h12.824l-2.143 5zM18 7v4H6c-.4 0-.762.238-.919.606L4 14.129V7h14z"></path></svg>\'
//                    ELSE
//                    \'\'
//                    END
//                    ),
//                    \'<span style="width: calc(100% - 25px); float: right; text-align: left">\',
//                    name,
//                    \'</span>\') as full_name'),
    }

    public function rezhim_list($id_rezhim){
        $name_rezhim = RezhimLists::find($id_rezhim)->first()->name_rezhim;
        return view('web.rezhim.rezhim_list', compact('name_rezhim', 'id_rezhim'));
    }
    public function select_param($id_rezhim, $id_param){
        $data = TableObj::where('inout', '!=', '!')->orderby('main_table.id')
            ->get();
        return view('web.rezhim.links', compact('data', 'id_rezhim', 'id_param'));
    }
    public function delete_rezhim($id){
        RezhimLists::find($id)->delete();
    }
    public function save_select_param($id_param, $select_id){
        RezhimParams::find($id_param)->update(['id_hour_param'=>$select_id, 'from_hour_params'=>true, 'calc'=>false, 'hand'=>false, 'calc_operations'=>'']);
    }
    public function edit_rezhim(Request $request, $id_rezhim){
        $data = $request->all();
        if ($data['column'] != 'hand' && $data['column'] != 'calc' && $data['column'] != 'from_hour_params' && $data['column'] != 'num_row'){
            RezhimParams::find($data['id'])->update([$data['column']=>$data['value']]);
        }else{
            switch ($data['column']){
                case 'hand':
                    RezhimParams::find($data['id'])->
                    update(
                        [
                            'hand'=>'true',
                            'id_hour_param'=>null,
                            'from_hour_params'=>'false',
                            'calc_operations'=>'',
                            'calc'=>'false',
                        ]);
                    break;
                case 'calc':
                    return 'calc';
//                    RezhimParams::find($data['id'])->
//                    update(
//                        [
//                            'calc'=>'true',
//                            'id_hour_param'=>null,
//                            'from_hour_params'=>'false',
//                            'calc_operations'=>'',
//                            'hand'=>'false',
//                        ]);
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
        return RezhimParams::where('rezhim_id', '=', $id)->orderby('num_row')->select('id','num_row','name','e_unit','level_row','folder','hand','calc','from_hour_params')->get();
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
