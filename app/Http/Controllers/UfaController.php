<?php

namespace App\Http\Controllers;

use App\Models\Ufa\KranJournal;
use Illuminate\Http\Request;
use App\Models\Ufa\ComplexAlgoritm;
use Illuminate\Support\Facades\DB;

class UfaController extends Controller
{
    public function change_comment_ufa(Request $request){
        $data = $request->all();
        ComplexAlgoritm::where('id', '=', $data['id'])->update(['comment'=>$data['comment']]);
    }
    public function create_record_ufa(){
        if (count(ComplexAlgoritm::wherebetween('date',[date('Y-m-d 00:00'), date('Y-m-d 23:59')])->where('checked', '=', false)->get())<1){
            ComplexAlgoritm::create(['date'=>date('Y-m-d H:i')]);
        }
    }
    public function open_lists_ufa_tm(){
        return view('web.ufa_tm.test_algoritm');
    }
    public function get_data_ufa_tm(){
        return ComplexAlgoritm::orderbydesc('date')->select('id', 'date', 'comment', 'checked')->get();
    }
    public function open_record_ufa_tm($id){
        ComplexAlgoritm::where('focus', '=', true)->update(['focus'=>false]);
        ComplexAlgoritm::where('id', '=', $id)->update(['focus'=>true]);
        $result = ComplexAlgoritm::where('id', '=', $id)->first();
        return view('web.ufa_tm.page_algoritm', compact('result'));
    }
    public function open_journal_perestanovok(){
        return view('web.ufa_tm.open_journal_perestanovok');
    }
    public function get_data_journal_perestanovok($date_start, $date_stop){
        return DB::select("select name_kran, sum(open) as open, sum(close) as close, sum(accident) as accident,
       case when sum(open)>0 then
           (select to_char(date, 'dd.mm.yyyy HH24:mi') from ufa_tm.journal_perestanovok
            where
            date
            between
            '".date('d.m.Y 00:00', strtotime($date_start))."'
            and
            '".date('d.m.Y 23:59', strtotime($date_stop))."'
            and open = 1
            and name_kran = tm.name_kran
            order by date desc limit 1)::text
            ELSE
            'н.д.'
            END as date_open,
        case when sum(close)>0 then
           (select to_char(date, 'dd.mm.yyyy HH24:mi') from ufa_tm.journal_perestanovok
            where
            date
            between
            '".date('d.m.Y 00:00', strtotime($date_start))."'
            and
            '".date('d.m.Y 23:59', strtotime($date_stop))."'
            and close = 1
            and name_kran = tm.name_kran
            order by date desc limit 1)::text
            ELSE
            'н.д.'
            END as date_close,
    case when sum(accident)>0 then
           (select to_char(date, 'dd.mm.yyyy HH24:mi') from ufa_tm.journal_perestanovok
            where
            date
            between
            '".date('d.m.Y 00:00', strtotime($date_start))."'
            and
            '".date('d.m.Y 23:59', strtotime($date_stop))."'
            and accident = 1
            and name_kran = tm.name_kran
            order by date desc limit 1)::text
            ELSE
            'н.д.'
            END as date_accident
from ufa_tm.journal_perestanovok tm
where date between '".date('d.m.Y 00:00', strtotime($date_start))."' and '".date('d.m.Y 23:59', strtotime($date_stop))."'
group by name_kran");
    }
    public function test_bufer(){
        return view('web.ufa_tm.test_bufer');
    }
    public function test_bufer_data(){
        $data = DB::select("select array_agg(value) as val, array_agg(to_char(timestamp, 'HH24:mi:ss')) as time, name_param from (select * from ufa_tm.bufer_values join ufa_tm.bufer_params bp on bufer_values.param_id = bp.address
where bufer_values.timestamp between '".date('Y-m-d H:i:s', strtotime('-1 hours'))."' and '".date('Y-m-d H:i:s')."'
    order by bufer_values.timestamp) a
group by name_param");
        foreach ($data as $row) {
            $new_result[] = array_values((array)$row);
        }
        for ($i =0 ; $i<7; $i++){
            try {
                $new_result[$i][1] = str_replace(['{', '}'], '', $new_result[$i][1]);
                $new_result[$i][1] = explode(',', $new_result[$i][1]);
                $new_result[$i][0] = str_replace(['{', '}'], '', $new_result[$i][0]);
                $new_result[$i][0] = explode(',', $new_result[$i][0]);
            }catch (\Throwable $e){

            }
        }
        try {
            if ($new_result){
                return $new_result;
            }else{
                return 'false';
            }
        }catch (\Throwable $e){
            return 'false';
        }
    }
}

?>
