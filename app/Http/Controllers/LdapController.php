<?php

namespace App\Http\Controllers;


use App\Ldap\User;
use App\Models\Hour_params;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Container;

class LdapController extends Controller
{
    public function test()
    {
//        echo 'вызвал';
//        foreach (TableObj::where('inout', '!=', '!')->select('id')->get() as $item) {
//            for ($date = date('Y-m-d 12:00', strtotime('-1 month')); strtotime($date)<strtotime(date('Y-m-d H:i')); $date=date('Y-m-d H:i', strtotime($date.' +1 hours'))){
//                Hour_params::create(['param_id'=>$item->id, 'val'=>rand(0, 100), 'timestamp'=>$date]);
//            }
//        }
//        dd('fsdfsdf');



//        dd(Auth::user()->displayname[0]);
//        $users = User::get();
//        foreach ($users as $user) {
//            dd($user);
//        }
//
//        dd($users);
//
////        Проверка пользователя
//        $connection = Container::getDefaultConnection();
//        if ($connection->auth()->attempt('cn=admin,dc=maxcrc,dc=com', 'secret')) {
//            dd('Попал');
//        }else{
//            $message = $connection->getLdapConnection()->getDiagnosticMessage();
//
//            dd($message);
//        }

    }
}


