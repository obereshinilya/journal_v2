<?php

namespace App\Http\Controllers;

use App\Models\rezhim\RezhimLists;
use App\Models\rezhim\RezhimParams;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RezhimController extends Controller
{
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
                    RezhimParams::find($data['id'])->
                    update(
                        [
                            'calc'=>'true',
                            'id_hour_param'=>null,
                            'from_hour_params'=>'false',
                            'calc_operations'=>'',
                            'hand'=>'false',
                        ]);
                    break;
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
