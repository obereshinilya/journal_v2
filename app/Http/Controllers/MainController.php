<?php

namespace App\Http\Controllers;


use App\Models\Hour_params;
use App\Models\Log;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function delete_object($parent_id){
        (new MainController)->create_log_record('Удалил объект "'.TableObj::where('id', '=', $parent_id)->first()->full_name.'"');
        $this->delete_child($parent_id);
    }
    private function delete_child($parent_id){
        foreach (TableObj::where('parent_id', $parent_id)->get() as $row){
            if (TableObj::where('parent_id', $row->id)->get()){
                $this->delete_child($row->id);
            }else{
                $row->delete();
            }
        }
        TableObj::where('id', $parent_id)->delete();
    }
    public function store_new_signal(Request $request, $parent_id){
        (new MainController)->create_log_record('Добавил сигналы в объект "'.TableObj::where('id', '=', $parent_id)->first()->full_name.'"');
        try {
            $level = TableObj::where('id', '=', $parent_id)->first()->level + 1;
            $data = $request->all();
            $keys = array_keys($data);
            for ($i=0; $i<count($data[$keys[0]]); $i++){
                foreach ($keys as $key){
                    $data_to_base[$key] = $data[$key][$i];
                }
                $data_to_base['level'] = $level;
                $data_to_base['inout'] = 'ВХОД';
                $data_to_base['parent_id'] = $parent_id;
                TableObj::create($data_to_base);
            }
        }catch (\Throwable $e){
            return $e;
        }

    }

    public function store_new_name($id, $new_name){
        (new MainController)->create_log_record('Переименовал объект "'.TableObj::where('id', '=', $id)->first()->full_name.' в '.$new_name.'"');
        TableObj::where('id', '=', $id)->first()->update(['full_name'=>$new_name]);
    }
    public function store_new_object($parent_id, $name_new_object){
        (new MainController)->create_log_record('Добавил объект "'.$name_new_object.'"');
        $parent = TableObj::where('id', '=', $parent_id)->first();
        $new_obj = [
            'full_name'=>$name_new_object,
            'inout'=>'!',
            'parent_id'=>$parent_id,
            'level'=>$parent->level+1
        ];
        TableObj::create($new_obj);
    }
    public function get_side_object(){
        $arr=TableObj::getTree();
        $dom=new \DOMDocument('1.0');
        $div_tree=$dom->createElement('ul');
        $level=1;
        $this->treeFormed($dom, $arr[0], $div_tree, $level);
        return $dom->saveHTML($div_tree);
    }

    private function treeFormed($dom, $dict, $element, $level){
        try{
            foreach ($dict['children'] as $value){
                $element_li=$dom->createElement('li');
                $element_li->setAttribute('data-id', $value['id']);
                if (array_key_exists('children', $value)){
                    $element_ul=$dom->createElement('ul');
                    $element_ul->setAttribute('id', 'ul_'.$value['id']);
                    $element_li->setAttribute('class', 'side_obj');
                    $element_li->textContent = $value['full_name'];
                    $plus_icon=$dom->createElement('img');
                    $plus_icon->setAttribute('class', 'plus_icon hide');
                    $plus_icon->setAttribute('data-ul', 'ul_'.$value['id']);
                    $plus_icon->setAttribute('src', asset('/assets/img/plus.png'));
                    $element_li->appendChild($plus_icon);
                    $element->appendChild($element_li);
                    $element->appendChild($element_ul);
                    $this->treeFormed($dom, $value, $element_ul, $level+1);
                }else{
                    $element_li->textContent = $value['full_name'];
                    $element->appendChild($element_li);
                }
            }
        }catch (\Exception $err){

        }
    }

    public function get_data_for_graph($param_id){
        $param_string = substr($param_id,0,-1);
        $param_id = explode(",", $param_string);

        $selectRow = 'time.date';
        $joinRow = "(SELECT date_trunc('minute', time.timestamp) as date FROM app_info.hour_params as time WHERE time.param_id in (".$param_string.") GROUP BY time.timestamp ORDER BY time.timestamp) as time ";
        $replace = array('{', '}', '"date":');
        $replace_to = array('[',']', '');
        for ($i = 0; $i<count($param_id); $i++){
            array_push($replace, '"val'.$i.'":');
            array_push($replace_to, '');
            $selectRow = $selectRow.', p'.$i.'.val as val'.$i;
            $joinRow = $joinRow."left join (SELECT date_trunc('minute', p".$i.".timestamp) as date_p".$i.", val FROM app_info.hour_params as p".$i." WHERE p".$i.".param_id = ".$param_id[$i].") as p".$i." on p".$i.".date_p".$i." = time.date ";
        }
        $result = DB::select("SELECT ".$selectRow." FROM ".$joinRow);
        $json = str_replace($replace,$replace_to, json_encode($result));
        return $json;
//        dd($json);
//        dd(json_decode($json));

    }

    function get_hide_id($parent_id){
        $arr = [];
        return $this->hide_id($parent_id, $arr);
    }
    private function hide_id($parent_id, $arr){
        foreach (TableObj::where('parent_id', '=', $parent_id)->get() as $item) {
            if ($item->inout == '!'){
                $arr = $this->hide_id($item->id, $arr);
            }else{
                array_push($arr, $item->id);
            }
        }
        return $arr;
    }
    public function create_log_record($message){
        $record['username'] = Auth::user()->displayname[0];
        $record['event'] = $message;
        Log::create($record);
    }
}


