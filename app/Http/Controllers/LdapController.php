<?php

namespace App\Http\Controllers;


use App\Ldap\User;
use App\Models\Hour_params;
use App\Models\Min_params;
use App\Models\Sut_params;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Container;

class LdapController extends Controller
{
    public function test()
    {
        $date = date('Y-m-d', strtotime('2023-03-10'));
        $timepar = TableObj::where('inout', '=', 'ВХОД')->select('id')->get();
        while ($date < date('Y-m-d H:i', strtotime('+3 days'))){
            foreach ($timepar as $par){
                Sut_params::create(['timestamp'=>$date, 'param_id'=>$par->id, 'val'=>rand(0, 1000)/10]);
            }
            $date = date('Y-m-d', strtotime($date.' +1 days'));
        }
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


