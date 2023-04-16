<?php

namespace App\Providers;

use App\Http\Controllers\MainController;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Для авторизации ldap

        Fortify::authenticateUsing(function ($request) {
            $validated = Auth::validate([
                'cn' => $request->email,
                'password' => $request->password
            ]);
            if (!$validated){
                $validated = Auth::validate([
                    'mail' => $request->email,
                    'password' => $request->password
                ]);
            }
            if ($validated){
                (new MainController)->create_log_record('Вход в систему', 'Пользователь: '.$request->email);
            }else{
                (new MainController)->create_log_record('Попытка хода в систему', 'Пользователь: '.$request->email);
            }
            return $validated ? Auth::getLastAttempted() : null;
        });
    }
}
