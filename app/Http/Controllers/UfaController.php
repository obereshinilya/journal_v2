<?php

namespace App\Http\Controllers;

use App\Models\Ufa\ComplexAlgoritm;

class UfaController extends Controller
{
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

}

?>
