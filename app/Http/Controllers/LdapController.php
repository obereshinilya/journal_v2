<?php

namespace App\Http\Controllers;


use App\Ldap\User;
use App\Models\Hour_params;
use App\Models\Min_params;
use App\Models\Sut_params;
use App\Models\TableObj;
use App\Models\XMLJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Container;

class LdapController extends Controller
{
    public function test()
    {
        dd(Auth::user());
        //Для генерации параметров
//        $date = date('Y-m-d', strtotime('2023-03-10'));
//        $timepar = TableObj::where('inout', '=', 'ВХОД')->select('id')->get();
//        while ($date < date('Y-m-d H:i', strtotime('+3 days'))){
//            foreach ($timepar as $par){
//                Sut_params::create(['timestamp'=>$date, 'param_id'=>$par->id, 'val'=>rand(0, 1000)/10]);
//            }
//            $date = date('Y-m-d', strtotime($date.' +1 days'));
//        }
//        Hour_params::where('timestamp', '>', date('Y-m-d H:i', strtotime('2023-05-16 15:00')))->delete();
//        Min_params::where('timestamp', '>', date('Y-m-d H:i', strtotime('2023-05-16 15:00')))->delete();
//        $date = date('Y-m-d H:i:s', strtotime('2023-03-10 11:00'));
//        while($date < date('Y-m-d H:i')){
//            XMLJournal::create(['event'=>'Отправка PT5M_'.date('Y_m_d_H_i', strtotime($date)), 'status'=>'Успешно отправлено', 'timestamp'=>$date]);
//            $date = date('Y-m-d H:i:s', strtotime($date.' +5 minutes'));
//        }


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

    public function register_without_password(Request $request, $login){
        $user = User::where('cn', '=', $login)->first();
        if (isset($user)){
            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/');
        }
    }
}


