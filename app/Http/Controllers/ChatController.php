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
    function save_new_member($user_id, $group_id){
        try {
            PeopleGroup::where('user_id', '=', $user_id)->where('group_id', '=', $group_id)->first()->update(['is_active'=>true]);
        }catch (\Throwable $e){
            PeopleGroup::create(['user_id'=>$user_id, 'group_id'=>$group_id]);
        }
    }
    function add_user_to_group($group_id){
        $current_user_id = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        $creator_id = Groups::where('id', '=', $group_id)->first()->creator_id;
        if ($current_user_id == $creator_id){
            $includes_people = PeopleGroup::where('group_id', '=', $group_id)->where('is_active', '=', true)->get()->pluck('user_id')->toArray();
            $users = Users::whereNotIn('id', $includes_people)->get()->toArray();
            return $users;
        }else{
            return 'false';
        }
    }
    function delete_user_from_group($user_id, $group_id){
        PeopleGroup::where('group_id', '=', $group_id)->where('user_id', '=', $user_id)->update(['is_active'=>false]);
    }
    function delete_group($id){
        PeopleGroup::where('group_id', '=', $id)->delete();
        $ids_message = MessageRecipient::where('recipient_group_id', '=', $id)
            ->select('message_id')->get()->pluck('message_id')->toArray();
        MessageRecipient::where('recipient_group_id', '=', $id)->delete();
        Groups::where('id', '=', $id)->delete();
        Message::wherein('id', $ids_message)->delete();
    }
    public function get_user_info($id){
        $user_login = Users::where('id', '=', $id)->first()->login;
        return User::where('cn', '=', $user_login)->first()->toArray();
    }
    public function get_group_info($id){
        $current_user = Users::where('login', '=', Auth::user()->cn[0])->first()->id;
        $user_ids = PeopleGroup::where('group_id', '=', $id)->where('users_group.is_active', '=', true)
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
        $files = Message::join('chat.message_recipient as mr', 'messages.id', '=', 'mr.message_id')
            ->join('chat.files as files', 'messages.file_id', '=', 'files.id')
            ->whereNotNull('file_id')->whereNull('recipient_group_id')
            ->whereRaw('(creator_id = '.$id.' and recipient_id = '.$current_user.' or creator_id = '.$current_user.' and recipient_id = '.$id.')')
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
                        $new_message = Message::create(['creator_id'=>$creator_id, 'message_body'=>'Создана группа "'.$request['name_group'].'"']);
                        $id_new_message = $new_message->id;
                        for ($i=0; $i<count($request['users']); $i++){
                            MessageRecipient::create(['recipient_id'=>$request['users'][$i], 'recipient_group_id'=>$group_id, 'message_id'=>$id_new_message]);
                        }
                        MessageRecipient::create(['recipient_id'=>$creator_id, 'recipient_group_id'=>$group_id, 'message_id'=>$id_new_message]);
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
            foreach (PeopleGroup::where('group_id', '=', $request['id'])->where('users_group.is_active', '=', true)->get() as $user_from_group){
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
            $read_message = MessageRecipient::where('recipient_id', '=', $current_user)->where('recipient_group_id', '=', $id)->where('is_read', '=', false)->update(['is_read'=>true]);
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
            $read_message = MessageRecipient::where('recipient_id', '=', $current_user)->whereNull('recipient_group_id')->where('is_read', '=', false)->update(['is_read'=>true]);
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
            $read_message = MessageRecipient::where('recipient_id', '=', $current_user)->where('recipient_group_id', '=', $id)->where('is_read', '=', false)->update(['is_read'=>true]);
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
            $read_message = MessageRecipient::where('recipient_id', '=', $current_user)->whereNull('recipient_group_id')->where('is_read', '=', false)->update(['is_read'=>true]);
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
        $return_data = DB::select("select group_id as recipient_id, name as display_name, create_date,message_body, sum_unread, case when 1 is NULL then 'false' else 'true' end as is_group from (
select u.id as user_id, u.display_name, g.id as group_id, g.name  from chat.users u
inner join chat.users_group ug on u.id=ug.user_id
inner join chat.groups g on ug.group_id = g.id
where user_id = ".$user_id." and ug.is_active=true) as us_group
inner join
-- вытащить последнее сообщение из каждой группы
(select g_m.* from(
select mr.recipient_group_id, max(m.create_date) as create_date,case when m.message_body is NULL then 'Файл' else m.message_body end  as message_body from chat.messages m
inner join chat.message_recipient mr on m.id = mr.message_id
where mr.recipient_group_id is not NULL
group by mr.recipient_group_id, m.message_body) as g_m
inner join (select mr.recipient_group_id, max(m.create_date) as create_date from chat.messages m
inner join chat.message_recipient mr on m.id = mr.message_id
where mr.recipient_group_id is not NULL
group by mr.recipient_group_id) g_m2
on g_m.recipient_group_id=g_m2.recipient_group_id and g_m.create_date = g_m2.create_date) as message_for_group
on us_group.group_id = message_for_group.recipient_group_id
left join (select mr.recipient_group_id, count(is_read) as sum_unread from chat.message_recipient as mr
where mr.recipient_id = ".$user_id." and mr.is_read = false
group by mr.recipient_group_id) as unread
         on group_id = unread.recipient_group_id

union
select main.recipient_id as group_id,u.display_name,main.create_date, case when main.message_body is NULL then 'Файл' else main.message_body end as message_body, main.sum_unread,  main.is_group from (
-- for chats
select c_g.creator_id, c_g.recipient_id, c_g.sum_unread, c_g.create_date,c_g.message_body, case when 1 is NULL then 'true' else 'false' end as is_group from (
select  case when m.creator_id = ".$user_id." then m.creator_id else mr.recipient_id end as creator_id ,
        case when mr.recipient_id =".$user_id." then m.creator_id else mr.recipient_id end as recipient_id ,
        sum( case when mr.recipient_id =".$user_id." and mr.is_read = false then 1 else 0 end) as sum_unread ,
        m.create_date,  m.message_body from chat.message_recipient mr
left join chat.messages as m on m.id = mr.message_id
where recipient_group_id is NULL and (creator_id = ".$user_id." or recipient_id=".$user_id.")
group by m.creator_id, mr.recipient_id, m.message_body, m.create_date) as c_g

inner join (
select creator_id, recipient_id, max(create_date) as create_date from (
select  case when m.creator_id = ".$user_id." then m.creator_id else mr.recipient_id end as creator_id ,
        case when mr.recipient_id =".$user_id." then m.creator_id else mr.recipient_id end as recipient_id ,
        max(m.create_date) as create_date from chat.message_recipient mr
left join chat.messages as m on m.id = mr.message_id
where recipient_group_id is NULL and (creator_id = ".$user_id." or recipient_id=".$user_id.")
group by m.creator_id, mr.recipient_id, m.create_date) as fooo
group by creator_id, recipient_id) as c_g1
on c_g.creator_id=c_g1.creator_id and c_g.recipient_id = c_g1.recipient_id and c_g.create_date = c_g1.create_date) as main
left join chat.users u on u.id = main.recipient_id
order by create_date desc ;");
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
