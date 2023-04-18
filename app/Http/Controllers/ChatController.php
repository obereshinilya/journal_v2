<?php

namespace App\Http\Controllers;

use App\Models\LevelInfo;
use App\Models\LogSmena;
use App\Models\Message_chat;
use App\Models\NewMessageToGDU;
use App\Models\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Composer\Autoload\includeFile;
use function GuzzleHttp\Promise\all;
use function Livewire\str;

class ChatController extends Controller
{
    public function download_file_chat($filename){
        $path = 'storage/chat_document/'.$filename;
        return response()->download($path, basename($path));
    }
    public function upload_file_chat(Request $request, $recipient){
        try {
            foreach ($request->file() as $file) {
                foreach ($file as $f) {
                    if (!file_exists((public_path('storage/chat_document/' . $f->getClientOriginalName())))) {       // проверка на существование файла
                        $f->move(public_path('storage/chat_document/'), $f->getClientOriginalName()); //public\storage\docs
                    }
                    Message_chat::create([
                        'user_sender'=>UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first()->username,
                        'user_recipent'=>$recipient,
                        'message'=> $f->getClientOriginalName(),
                        'file'=>true,
                        'timestamp'=>date('Y-m-d H:i:s')
                    ]);
                }
            }
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function set_type_messege($id, $type, $color){
        if ($type == '-'){
            $message = Message_chat::where('id', '=', $id)->first()->update(['type_message'=>'',
                'color_message'=>'#E3E6EA'
            ]);
        }else{
            if ($type == 'Дисп. задание'){
                $last_type = Message_chat::where('id', '=', $id)->first()->type_message;
                if ($last_type == 'Дисп. задание') {
                    $message = Message_chat::where('id', '=', $id)->first()->update(['type_message'=>'Дисп. задание закрыто',
                        'color_message'=>$color
                    ]);
                }elseif($last_type == 'Дисп. задание закрыто'){
                    return true;
                }else{
                    $message = Message_chat::where('id', '=', $id)->first()->update(['type_message' => $type,
                        'color_message' => $color
                    ]);
                }
            }else {
                $message = Message_chat::where('id', '=', $id)->first()->update(['type_message' => $type,
                    'color_message' => $color
                ]);
            }
        }
    }
    public function send_messege(Request $request){
        $data_last_user = UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first();
        Message_chat::create([
            'user_sender'=>$data_last_user->username,
            'user_recipent'=>$request['recipient'],
            'message'=> $request['text'],
            'timestamp'=>date('Y-m-d H:i:s')
        ]);
        if ($request['recipient'] == LevelInfo::where('short_name', '=', 'vlg')->first()->full_name){
            if ($data_last_user->level == 'cdp'){   //если отправка с уровня ЦДП
                if (count(NewMessageToGDU::where('id', '=', 1)->get())>0){ //если есть запись уже
                    $count_new = NewMessageToGDU::where('id', '=', 1)->first()->count + 1;
                    NewMessageToGDU::where('id', '=', 1)->update(['count'=>$count_new]);
                }else{
                    NewMessageToGDU::create(['id'=>1, 'count'=>1]);
                }
            }else{  //если отправка с уровня ГДУ
/*                $content = '<?xml version="1.0" encoding="UTF-8"?>';*/
//                $content = $content.'<BusinessMessage>';
//                $content = $content.'   <CountUnreadMessage>1</CountUnreadMessage>';
//                $content = $content.'</BusinessMessage>';
//                $disk = Storage::build([
//                    'driver' => 'sftp',
//                    'host' => '172.16.205.139',
//                    'username' => 'horizont',
//                    'password' => 'demodemo',
//                    'visibility' => 'public',
//                    'permPublic' => 0777, /// <- this one did the trick
//                    'root' => '/usr/PROZESS/horizont/var/cc/dj/new_message/',
//                ]);
//                $disk->put('new_message_'.date('Y_m_d_H_i_s_').'.xml', $content, 'public');
            }
        }
        return $request->all();
    }
    public function get_chat($name){
        if (count(LevelInfo::where('full_name', '=', $name)->get())>0){    ///если вытягиваем сообщения группы
            $current_user = UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first()->username;
            $all_messege = Message_chat::orderby('timestamp')->where('user_sender', '=', $name)
                ->orWhere('user_recipent', '=', $name)->get()
                ->groupby(function ($all_messege){
                    return Carbon::parse($all_messege->timestamp)->format('Y-m-d');
                });
            foreach (Message_chat::where('user_recipent', '=', $name)->get() as $row){
                $row->update(['is_read'=>true]);
            }
            $all_messege['last_seen'] = 'Групповой чат';
        }else{
            $current_user = UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first()->username;
            $all_messege = Message_chat::orderby('timestamp')->where([['user_sender', '=', $name], ['user_recipent', '=', $current_user]])
                ->orWhere([['user_sender', '=', $current_user], ['user_recipent', '=', $name]])->get()
                ->groupby(function ($all_messege){
                    return Carbon::parse($all_messege->timestamp)->format('Y-m-d');
                });
            foreach (Message_chat::where([['user_sender', '=', $name], ['user_recipent', '=', $current_user]])->get() as $row){
                $row->update(['is_read'=>true]);
            }
            $log_smena = LogSmena::where('name_user', '=', $name)->orderbydesc('id')->get();
            if (count($log_smena)>0){
                if ($log_smena->first()->stop_smena){
                    $all_messege['last_seen'] = 'Был в сети '.date('Y-m-d H:i', strtotime($log_smena->first()->stop_smena));
                }else{
                    $all_messege['last_seen'] = 'На смене';
                }
            }
        }
        $all_messege['current_user'] = $current_user;
        return $all_messege;
    }
    public function get_people_block(){
        $users = UserAuth::groupby('username')->select('username')->get();
        $current_user = UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first()->username;
        $data = [];
        foreach ($users as $user){
            if ($user->username != $current_user){
                $to_data['name'] = $user->username;
                $last_messege = Message_chat::orderbydesc('timestamp')->where([['user_sender', '=', $user->username], ['user_recipent', '=', $current_user]])
                    ->orWhere([['user_sender', '=', $current_user], ['user_recipent', '=', $user->username]])->get();
                if (count($last_messege)){
                    $last_messege = $last_messege->first();
                    $time_last_message = $last_messege->timestamp;
                    if (date('Y-m-d', strtotime($time_last_message)) == date('Y-m-d')){
                        $to_data['time_last_messege'] = date('H:i', strtotime($last_messege->timestamp));
                    }else{
                        $to_data['time_last_messege'] = date('d.m', strtotime($last_messege->timestamp));
                    }
                    if ($last_messege->message){
                        $to_data['last_messege'] = $last_messege->message;
                    }else{
                        $to_data['last_messege'] = '';
                    }
                    $count = count(Message_chat::where('user_recipent', '=', $current_user)->where('user_sender', '=', $to_data['name'])->where('is_read', false)->get());
                    if ($count>0){
                        $to_data['count_unread_messege'] = $count;
                    }else{
                        $to_data['count_unread_messege'] = '';
                    }
                    $to_data['for_sort'] = strtotime($last_messege->timestamp);
                }else{
                    $to_data['time_last_messege'] = '';
                    $to_data['last_messege'] = '';
                    $to_data['count_unread_messege'] = '';
                    $to_data['for_sort'] = 0;
                }
                $to_data['group']= false;
                array_push($data, $to_data);
            }
        }
        ///Создание групп
        $level_current_user = UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first()->level;
        if ($level_current_user == 'cdp'){
            $operator = '!=';
        }else{
            $operator = '=';
        }
        foreach (LevelInfo::where('short_name', $operator, $level_current_user)->get() as $name_filiala){
            $to_data['name'] = $name_filiala->full_name;
            $last_messege = Message_chat::orderbydesc('timestamp')->where('user_sender', '=', $name_filiala->full_name)
                ->orWhere('user_recipent', '=', $name_filiala->full_name)->get();
            if (count($last_messege)){
                $last_messege = $last_messege->first();
                $time_last_message = $last_messege->timestamp;
                if (date('Y-m-d', strtotime($time_last_message)) == date('Y-m-d')){
                    $to_data['time_last_messege'] = date('H:i', strtotime($last_messege->timestamp));
                }else{
                    $to_data['time_last_messege'] = date('d.m', strtotime($last_messege->timestamp));
                }
                if ($last_messege->message){
                    $to_data['last_messege'] = $last_messege->message;
                }else{
                    $to_data['last_messege'] = '';
                }
                $count = count(Message_chat::where('user_recipent', '=', $current_user)->where('user_sender', '=', $to_data['name'])->where('is_read', false)->get());
                if ($count>0){
                    $to_data['count_unread_messege'] = $count;
                }else{
                    $to_data['count_unread_messege'] = '';
                }
                $to_data['for_sort'] = strtotime($last_messege->timestamp);
            }else{
                $to_data['time_last_messege'] = '';
                $to_data['last_messege'] = '';
                $to_data['count_unread_messege'] = '';
                $to_data['for_sort'] = 0;
            }
            $to_data['group']= true;
            array_push($data, $to_data);
        }
        usort($data,function($a,$b){
            if ($a['for_sort'] < $b['for_sort']){
                return 1;
            }else{
                return -1;
            }
        });
        return $data;
    }

}

?>
