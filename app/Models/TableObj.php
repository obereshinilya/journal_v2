<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableObj extends Model{

    protected $table='app_info.main_table';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'full_name', 'inout', 'e_unit', 'short_name', 'parent_id', 'level', 'tag_name', 'hour_param', 'sut_param', 'guid_masdu_hour', 'guid_masdu_min', 'guid_masdu_day',
    ];

    static function createTree(&$list, $parent){
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['id']])){
                $l['children'] = TableObj::createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    public static function getTree(){
        $data=TableObj::select('id',
            'full_name', 'parent_id', 'level')->where('inout', '=', '!')->orderBy('parent_id')->orderBy('id')->get();

        foreach ($data as $row){
            $arr[]=array('id'=>$row->id,
                'full_name'=>$row->full_name,
                'parent_id'=>$row->parent_id,
                'level'=>$row->level);
        }

        $new = array();
        foreach ($arr as $a){
            $new[$a['parent_id']][] = $a;
        }
        $tree = TableObj::createTree($new, array($data[0]));

        return $tree;
    }

}

?>
