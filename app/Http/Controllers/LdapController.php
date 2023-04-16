<?php

namespace App\Http\Controllers;


use App\Ldap\User;
use App\Models\TableObj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Container;

class LdapController extends Controller
{
    public function test()
    {
        dd(Auth::user()->displayname[0]);
        $users = User::get();
        foreach ($users as $user) {
            dd($user);
        }

        dd($users);

//        Проверка пользователя
        $connection = Container::getDefaultConnection();
        if ($connection->auth()->attempt('cn=admin,dc=maxcrc,dc=com', 'secret')) {
            dd('Попал');
        }else{
            $message = $connection->getLdapConnection()->getDiagnosticMessage();

            dd($message);
        }

    }
}


