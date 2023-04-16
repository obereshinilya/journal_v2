<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HourController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LdapController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SettingController;


Route::group(['middleware' => ['auth']], function() {

    Route::get('/signal_settings/{id_param}', [SettingController::class, 'signal_settings']);
    Route::get('/save_signal_settings/{id}/{name_param}/{new_value}', [SettingController::class, 'save_signal_settings']);
    Route::get('/visible_param/{id}', [SettingController::class, 'visible_param']);






    Route::get('/test', [LdapController::class, 'test']);



    //БЛОК часовиков
    Route::get('/', [HourController::class, 'main']);
    Route::get('/get_hour_data/{date}', [HourController::class, 'get_hour_param']);   //Получаем часовики
    Route::get('/hours_param_minutes/{date}/{hour}', [HourController::class, 'hours_param_minutes']); //Значения минуток
    Route::get('/create_param/{value}/{timestamp}/{date}/{param_id}', [HourController::class, 'create_param']);  //Сохраняем новую часовку
    Route::get('/update_param/{value}/{id}/{sutki}', [HourController::class, 'update_param']); //Обновляем часовку
    Route::get('/get_hide_id/{parent_id}', [MainController::class, 'get_hide_id']);   //Получаем все id сигналов родителя
    Route::post('/save_comment/{id}/{type}', [HourController::class, 'save_comment']);   //Сохраняем комментарий
    Route::get('/delete_comment/{id}/{type}', [HourController::class, 'delete_comment']);   //обнуляем комментарий
    Route::get('/print_hour/{date}', [HourController::class, 'print_hour']); //Печать суточных
    Route::get('/excel_hour/{date}', [HourController::class, 'excel_hour']); //Excel суточных
    Route::get('/get_data_for_graph/{param_id}', [MainController::class, 'get_data_for_graph']);   //Данные для отображения в графике

    //БЛОК SIDE_MENU
    Route::get('/get_side_object', [MainController::class, 'get_side_object'])->name('get_side_object');    ///Получить древо объектов
    Route::get('/store_new_object/{parent_id}/{name_new_object}', [MainController::class, 'store_new_object'])->name('store_new_object');    ///Сохранить новый объект
    Route::get('/store_new_name/{id}/{new_name}', [MainController::class, 'store_new_name'])->name('store_new_name');    ///Переименовать объект
    Route::post('/store_new_signal/{parent_id}', [MainController::class, 'store_new_signal'])->name('store_new_signal');    ///Сохранить новые сигналы
    Route::get('/delete_object/{parent_id}', [MainController::class, 'delete_object'])->name('delete_object');    ///Сохранить новые сигналы

});

Route::get('/logout',  [Controller::class, 'logout']);
