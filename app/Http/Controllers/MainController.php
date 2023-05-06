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
                TableObj::create($data_to_base);
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
        $parent = TableObj::where('id', '=', $parent_id)->first();
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

    public function get_data_for_graph($param_id, $number)
    {
        $param_string = substr($param_id, 0, -1);
        $param_id = explode(",", $param_string);
        $date_end = date('Y-m-d');
        $date_start = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 7 * $number, date("Y")));
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
//        $selectRow = 'time.date'; //старый запрос
//        $joinRow = "(SELECT date_trunc('minute', time . timestamp) as date FROM app_info . hour_params as time WHERE time . param_id in(".$param_string.") GROUP BY time . timestamp ORDER BY time . timestamp) as time ";
//        $replace = array('{', '}', '"date":');
//        $replace_to = array('[',']', '');
//        for ($i = 0; $i<count($param_id); $i++){
//            array_push($replace, '"val'.$i.'":');
//            array_push($replace_to, '');
//            $selectRow = $selectRow.', p'.$i.'.val as val'.$i;
//            $joinRow = $joinRow."left join(SELECT date_trunc('minute', p".$i." . timestamp) as date_p".$i.", val FROM app_info . hour_params as p".$i." WHERE p".$i." . param_id = ".$param_id[$i].") as p".$i." on p".$i." . date_p".$i." = time . date ";
//        }
//        return $sql;
        $result = DB::select($sql);
//      $result = DB::select("SELECT $selectRow FROM $joinRow"); // старый запрос
        foreach ($result as $row) {
            $new_result[] = array_values((array)$row);
        }

//         $json = str_replace($replace, $replace_to, json_encode($result)); //старый запрос
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
        Log::create($record);
    }

    public function test_page()
    {
        $all_param_hour = TableObj::where('hour_param', '=', true)->select('id', 'full_name', 'e_unit')->orderby('full_name')->get();

        return view('test_page', compact('all_param_hour'));
    }

    public function test_data_for_charts($param_id, $number)
    {
        $params = explode('!', $param_id);
        unset($params[count($params) - 1]);
        $date_end = date('Y-m-d');
        $date_start = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 7 * $number, date("Y")));
        foreach ($params as $key => $param) {
            $param = substr($param, 0, -1);
            $result[] = $param;
            $array_params = explode(',', $param);
            $sql = '';
            foreach ($array_params as $parametr) {
                $sql .= 'SELECT time.date,name.full_name, p0.val as val0  FROM ';
                $joinrow = "(SELECT date_trunc('minute', time.timestamp) as date from app_info.hour_params as time where time.param_id in ($parametr)  and time.timestamp between date '$date_start' and date '$date_end' group by  time.timestamp order by time.timestamp) as time";
                $joinrow .= " left join (SELECT date_trunc('minute', p0.timestamp) as date_p0, val,param_id from app_info.hour_params as p0 where p0.param_id=$parametr and p0.timestamp between date '$date_start' and date '$date_end') as p0  on p0.date_p0 = time.date";
                $joinrow .= " left join (SELECT id, full_name from app_info.main_table as name where name.id='$parametr' ) as name on p0.param_id=name.id";
                $sql .= $joinrow;
                if ($parametr != end($array_params) && count($array_params) > 1) {
                    $sql .= ' UNION ';
                }
            }
            $result = DB::select($sql);
            foreach ($result as $row) {
                $new_result[$key][] = array_values((array)$row);
            }
        }
        return json_encode($new_result);

    }
}


