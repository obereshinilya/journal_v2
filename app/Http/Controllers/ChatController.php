<?php

namespace App\Http\Controllers;

use App\Models\chat\Files;
use App\Models\chat\Groups;
use App\Models\chat\Message;
use App\Models\chat\MessageRecipient;
use App\Models\chat\PeopleGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LdapRecord\Models\OpenLDAP\User;
use App\Models\chat\Users;

class ChatController extends Controller
{
    public function get_user_info($id){
        $user_login = Users::where('id', '=', $id)->first()->login;
        return User::where('cn', '=', $user_login)->first()->toArray();
    }
    public function get_group_info($id){
        $current_user = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        $user_ids = PeopleGroup::where('group_id', '=', $id)
            ->join('chat.users', 'users_group.user_id', '=', 'users.id')
            ->join('chat.groups', 'users_group.group_id', '=', 'groups.id')
            ->select('users.display_name as nameuser', 'users.id as user_id',
                DB::raw('(CASE WHEN user_id = groups.creator_id THEN true ELSE false END) AS text'),
                DB::raw('(CASE WHEN '.$current_user.' = groups.creator_id THEN true ELSE false END) AS delete'))
            ->get()->toArray();
        return $user_ids;
    }
    public function get_user_files($id){
        $current_user = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        $files = Message::wherein('creator_id', [$current_user, $id])->whereNotNull('file_id')
            ->join('chat.message_recipient as message_recipient', 'messages.id', '=', 'message_recipient.message_id')
            ->whereNull('message_recipient.recipient_group_id')
            ->join('chat.files as files', 'messages.file_id', '=', 'files.id')
            ->select('files.name as filename', 'files.size', 'files.uid', DB::raw('to_char(messages.create_date, \'YYYY-MM-DD HH24:mi\') as time'))
            ->orderbydesc('time')
            ->get()->toArray();
        return $files;
    }
    public function get_group_files($id){
        $current_user = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        $user_ids = PeopleGroup::where('group_id', '=', $id)->get()->pluck('user_id')->toArray();
        $files = Message::wherein('creator_id', $user_ids)->whereNotNull('file_id')
            ->join('chat.message_recipient as message_recipient', 'messages.id', '=', 'message_recipient.message_id')
            ->where('message_recipient.recipient_group_id', '=', $id)->where('message_recipient.recipient_id', '=', $current_user)
            ->join('chat.files as files', 'messages.file_id', '=', 'files.id')
            ->select('files.name as filename', 'files.size', 'files.uid', DB::raw('to_char(messages.create_date, \'YYYY-MM-DD HH24:mi\') as time'))
            ->orderbydesc('time')
            ->get()->toArray();
        return $files;
    }

    public function save_group(Request $request){
        $request = $request->all();
        try {
            if ($request['name_group']){
                if(isset($request['users'])){
                    if (count($request['users'])<2){
                        return 'Указан всего один пользователь!';
                    }else{
                        $creator_id = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
                        $group = Groups::create(['name'=>$request['name_group'], 'creator_id'=>$creator_id]);
                        $group_id = $group->id;
                        for ($i=0; $i<count($request['users']); $i++){
                            PeopleGroup::create(['user_id'=>$request['users'][$i], 'group_id'=>$group_id]);
                        }
                        PeopleGroup::create(['user_id'=>$creator_id, 'group_id'=>$group_id]);
                        return 'false';
                    }
                }else{
                    return 'Не выбраны пользователи!';
                }
            }else{
                return 'Укажите наименование группы!';
            }
        }catch (\Throwable $e){
            return $e;
        }


    }
    public function get_all_users(){
        return Users::where('login', '!=', Auth::user()->cn[0])->get()->toArray();
    }
    public function new_message(Request $request){
        $request = $request->all();
        $creator_id = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        $message = Message::create(['creator_id'=>$creator_id, 'message_body'=>$request['text']]);
        $message_id = $message->id;
        if($request['group'] == 'true'){
            foreach (PeopleGroup::where('group_id', '=', $request['id'])->get() as $user_from_group){
                MessageRecipient::create(['recipient_id'=>$user_from_group->user_id ,'recipient_group_id'=>$request['id'], 'message_id'=>$message_id]);
            }
        }else{
            MessageRecipient::create(['recipient_id'=>$request['id'],'message_id'=>$message_id]);
        }
    }

    public function get_chat($id, $group){
        $current_user = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        if ($group == 'true'){
            $user_ids = PeopleGroup::where('group_id', '=', $id)->get()->pluck('user_id')->toArray();
            $message = Message::wherein('creator_id', $user_ids)
                ->leftjoin('chat.message_recipient', 'messages.id', '=', 'message_recipient.message_id')
                ->where('message_recipient.recipient_group_id', '=', $id)->where('message_recipient.recipient_id', '=', $current_user)
                ->leftjoin('chat.users as usr1', 'messages.creator_id', '=', 'usr1.id')
                ->leftjoin('chat.users', 'message_recipient.recipient_id', '=', 'users.id')
                ->leftjoin('chat.files', 'messages.file_id', '=', 'files.id')
                ->select('usr1.display_name as creator', 'messages.message_body as message', 'messages.id as message_id',
                    'messages.create_date as date', DB::raw('(CASE WHEN messages.creator_id = ' . $current_user . ' THEN true ELSE false END) AS mine_message'),
                    DB::raw('to_char(messages.create_date, \'HH24:mi\') as time'), 'files.name as filename', 'files.size as filesize', 'files.uid as fileuid')
                ->orderby('date', 'desc')->limit(50)
                ->get()->reverse()->groupby(function ($message){
                    return Carbon::parse($message->date)->format('Y-m-d');
                });
        }else{
            $message = Message::wherein('creator_id', [$current_user, $id])
                ->leftjoin('chat.message_recipient', 'messages.id', '=', 'message_recipient.message_id')
                ->wherein('message_recipient.recipient_id', [$current_user, $id])->whereNull('message_recipient.recipient_group_id')
                ->leftjoin('chat.users as usr1', 'messages.creator_id', '=', 'usr1.id')
                ->leftjoin('chat.users', 'message_recipient.recipient_id', '=', 'users.id')
                ->leftjoin('chat.files', 'messages.file_id', '=', 'files.id')
                ->select('usr1.display_name as creator', 'users.display_name as recipient', 'messages.message_body as message', 'messages.id as message_id',
                    'messages.create_date as date', DB::raw('(CASE WHEN messages.creator_id = ' . $current_user . ' THEN true ELSE false END) AS mine_message'),
                    DB::raw('to_char(messages.create_date, \'HH24:mi\') as time'), 'files.name as filename', 'files.size as filesize', 'files.uid as fileuid')
                ->orderby('date', 'desc')->limit(50)
                ->get()->reverse()->groupby(function ($message){
                    return Carbon::parse($message->date)->format('Y-m-d');
                });
        }
        return $message;
    }
    public function get_old_chat($id, $group, $last_id){
        $current_user = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        if ($group == 'true'){
            $user_ids = PeopleGroup::where('group_id', '=', $id)->get()->pluck('user_id')->toArray();
            $message = Message::wherein('creator_id', $user_ids)
                ->leftjoin('chat.message_recipient', 'messages.id', '=', 'message_recipient.message_id')
                ->where('message_recipient.recipient_group_id', '=', $id)->where('message_recipient.recipient_id', '=', $current_user)->where('messages.id', '<', $last_id)
                ->leftjoin('chat.users as usr1', 'messages.creator_id', '=', 'usr1.id')
                ->leftjoin('chat.users', 'message_recipient.recipient_id', '=', 'users.id')
                ->leftjoin('chat.files', 'messages.file_id', '=', 'files.id')
                ->select('usr1.display_name as creator', 'messages.message_body as message', 'messages.id as message_id',
                    'messages.create_date as date', DB::raw('(CASE WHEN messages.creator_id = ' . $current_user . ' THEN true ELSE false END) AS mine_message'),
                    DB::raw('to_char(messages.create_date, \'HH24:mi\') as time'), 'files.name as filename', 'files.size as filesize', 'files.uid as fileuid')
                ->orderby('date', 'desc')->limit(50)
                ->get()->reverse()->groupby(function ($message){
                    return Carbon::parse($message->date)->format('Y-m-d');
                });
        }else{
            $message = Message::wherein('creator_id', [$current_user, $id])
                ->leftjoin('chat.message_recipient', 'messages.id', '=', 'message_recipient.message_id')
                ->wherein('message_recipient.recipient_id', [$current_user, $id])->whereNull('message_recipient.recipient_group_id')->where('messages.id', '<', $last_id)
                ->leftjoin('chat.users as usr1', 'messages.creator_id', '=', 'usr1.id')
                ->leftjoin('chat.users', 'message_recipient.recipient_id', '=', 'users.id')
                ->leftjoin('chat.files', 'messages.file_id', '=', 'files.id')
                ->select('usr1.display_name as creator', 'users.display_name as recipient', 'messages.message_body as message', 'messages.id as message_id',
                    'messages.create_date as date', DB::raw('(CASE WHEN messages.creator_id = ' . $current_user . ' THEN true ELSE false END) AS mine_message'),
                    DB::raw('to_char(messages.create_date, \'HH24:mi\') as time'), 'files.name as filename', 'files.size as filesize', 'files.uid as fileuid')
                ->orderby('date', 'desc')->limit(50)
                ->get()->reverse()->groupby(function ($message){
                    return Carbon::parse($message->date)->format('Y-m-d');
                });
        }
        return $message;
    }
    public function get_new_chat($id, $group, $first_id){
        $current_user = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        if ($group == 'true'){
            $user_ids = PeopleGroup::where('group_id', '=', $id)->get()->pluck('user_id')->toArray();
            $message = Message::wherein('creator_id', $user_ids)
                ->leftjoin('chat.message_recipient', 'messages.id', '=', 'message_recipient.message_id')
                ->where('message_recipient.recipient_group_id', '=', $id)->where('message_recipient.recipient_id', '=', $current_user)->where('messages.id', '>', $first_id)
                ->leftjoin('chat.users as usr1', 'messages.creator_id', '=', 'usr1.id')
                ->leftjoin('chat.users', 'message_recipient.recipient_id', '=', 'users.id')
                ->leftjoin('chat.files', 'messages.file_id', '=', 'files.id')
                ->select('usr1.display_name as creator', 'messages.message_body as message', 'messages.id as message_id',
                    'messages.create_date as date', DB::raw('(CASE WHEN messages.creator_id = ' . $current_user . ' THEN true ELSE false END) AS mine_message'),
                    DB::raw('to_char(messages.create_date, \'HH24:mi\') as time'), 'files.name as filename', 'files.size as filesize', 'files.uid as fileuid')
                ->orderby('date', 'desc')->limit(50)
                ->get()->reverse()->groupby(function ($message){
                    return Carbon::parse($message->date)->format('Y-m-d');
                });
        }else{
            $message = Message::wherein('creator_id', [$current_user, $id])
                ->leftjoin('chat.message_recipient', 'messages.id', '=', 'message_recipient.message_id')
                ->wherein('message_recipient.recipient_id', [$current_user, $id])->whereNull('message_recipient.recipient_group_id')->where('messages.id', '>', $first_id)
                ->leftjoin('chat.users as usr1', 'messages.creator_id', '=', 'usr1.id')
                ->leftjoin('chat.users', 'message_recipient.recipient_id', '=', 'users.id')
                ->leftjoin('chat.files', 'messages.file_id', '=', 'files.id')
                ->select('usr1.display_name as creator', 'users.display_name as recipient', 'messages.message_body as message', 'messages.id as message_id',
                    'messages.create_date as date', DB::raw('(CASE WHEN messages.creator_id = ' . $current_user . ' THEN true ELSE false END) AS mine_message'),
                    DB::raw('to_char(messages.create_date, \'HH24:mi\') as time'), 'files.name as filename', 'files.size as filesize', 'files.uid as fileuid')
                ->orderby('date', 'desc')->limit(50)
                ->get()->reverse()->groupby(function ($message){
                    return Carbon::parse($message->date)->format('Y-m-d');
                });
        }
        return $message;
    }

    public function upload_file_chat(Request $request, $group, $id){
        try {
            foreach ($request->file() as $file) {
                foreach ($file as $f) {
                    $uid = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                    $file_rec = Files::create(['name'=>$f->getClientOriginalName(), 'uid'=>$uid, 'size'=>$f->getSize()]);
                    $f->move(public_path('storage/chat_document/'), $uid); //public\storage\chat_document
                    $creator_id = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
                    $message = Message::create(['creator_id'=>$creator_id, 'file_id'=>$file_rec->id]);
                    if($group == 'true'){
                        foreach (PeopleGroup::where('group_id', '=', $id)->get() as $user_from_group){
                            MessageRecipient::create(['recipient_id'=>$user_from_group->user_id ,'recipient_group_id'=>$id, 'message_id'=>$message->id]);
                        }
                    }else{
                        MessageRecipient::create(['recipient_id'=>$id,'message_id'=>$message->id]);
                    }
                }
            }
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function download_file_chat($fileuid){
        $name = Files::where('uid', '=', $fileuid)->first()->name;
        $path = 'storage/chat_document/'.$fileuid;
        return response()->download($path, basename($name));
    }

    public function get_user_block(){
        $user_id = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        $return_data = DB::select("select * from (select distinct main.group_id as recipient_id, main.name as display_name, message_body, create_date, sum_unread, CASE WHEN 1 IS NULL THEN 'false' ELSE 'true' END as is_group from (
            select g.id as group_id, g.name, mss.message_body,mss.create_date, mss.sum_unread from chat.users u
            inner join chat.users_group ug on u.id = ug.user_id
            inner join chat.groups g on  ug.group_id = g.id
            inner join (select mr.recipient_group_id, SUM(CASE WHEN mr.is_read = false THEN 1 ELSE 0 END) over (partition by mr.recipient_group_id) as sum_unread, m.message_body,m.create_date
            from chat.message_recipient mr
            inner join chat.messages m on mr.message_id = m.id order by m.create_date desc limit 1000) as mss
            on ug.group_id = mss.recipient_group_id
            where u.id = ".$user_id." and g.is_active = true
            order by mss.create_date desc) as main,
            (select k.group_id, k.name, max(k.create_date) as date from(
            select g.id as group_id, g.name, mss.message_body,mss.create_date, mss.sum_unread from chat.users u
            inner join chat.users_group ug on u.id = ug.user_id
            inner join chat.groups g on  ug.group_id = g.id
            inner join (select mr.recipient_group_id, SUM(CASE WHEN mr.is_read = false THEN 1 ELSE 0 END) over (partition by mr.recipient_group_id) as sum_unread, m.message_body,m.create_date
            from chat.message_recipient mr
            inner join chat.messages m on mr.message_id = m.id order by m.create_date desc limit 1000) as mss
            on ug.group_id = mss.recipient_group_id
            where u.id = ".$user_id." and g.is_active = true
            order by mss.create_date desc) as k group by k.group_id, k.name) max_date
            where main.group_id =max_date.group_id and main.create_date = max_date.date
            union
            select recipient_id, display_name, message_body, create_date, sum_unread,CASE WHEN 1 IS NULL THEN 'true' ELSE 'false' END as is_group from
            (    select * from (
            select distinct  recipient_id, sum(case when is_read = false THEN 1 ELSE 0 END) over (partition by recipient_id) as sum_unread from chat.messages
            join chat. message_recipient
            on message_recipient.message_id = messages.id
            where creator_id = ".$user_id." and recipient_id!= ".$user_id.") as recipients
             join (select t1.creator_id, t1.message_body, t1.create_date from
            chat.messages t1 join chat.messages t2 on t1.creator_id=t2.creator_id and t2.create_date >= t1.create_date
            group by t1.creator_id, t1.message_body, t1.create_date
            having count(*) =1) last_message
            on recipients.recipient_id = last_message.creator_id
            left join chat.users on users.id = recipients.recipient_id) as base) as result order by result.create_date desc;");
        $return_data['today'] = date('Y-m-d');
        return $return_data;
    }


    public function update_users(){
        foreach (User::all() as $user){
            try {
                Users::create(['display_name'=>$user->displayname[0],
                    'login'=>$user->cn[0]]);
            }catch (\Throwable $e){
                Users::where('login', '=', $user->cn[0])->update(['display_name'=>$user->displayname[0]]);
            }
        }
    }

}

?>
