<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HourController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LdapController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;


Route::group(['middleware' => ['auth']], function() {


    Route::get('/test', [LdapController::class, 'test']);

    //БЛОК отчетов
    Route::get('/reports', [ReportController::class, 'reports'])->name('reports');   ///Главная страница отчетов
    Route::get('/journal_sodu', [JournalController::class, 'journal_sodu'])->name('journal_sodu');   ///Страница журнала СОДУ
    Route::get('/journal_sodu_data/{date_start}/{date_stop}', [JournalController::class, 'journal_sodu_data'])->name('journal_sodu_data');   ///Данные журнала СОДУ
    Route::post('/edit_sodu', [JournalController::class, 'edit_sodu'])->name('edit_sodu');   ///Сохранить изменения в журнале
    Route::get('/delete_sodu/{id}', [JournalController::class, 'delete_sodu'])->name('delete_sodu');   ///Удалить запись
    Route::get('/create_sodu', [JournalController::class, 'create_sodu'])->name('create_sodu');   ///Создать запись



    //БЛОК чата
    Route::get('/update_users', [ChatController::class, 'update_users'])->name('update_users');   ///Синхронизация LDAP и postgres пользователей
    Route::get('/get_all_users', [ChatController::class, 'get_all_users'])->name('get_all_users');   ///Список всех пользователей кроме текущего
    Route::post('/save_group', [ChatController::class, 'save_group'])->name('save_group');   ///Сохранить группу
    Route::post('/new_message', [ChatController::class, 'new_message'])->name('new_message');   ///Сохранить сообщение
    Route::get('/get_chat/{id}/{group}', [ChatController::class, 'get_chat'])->name('get_chat');   ///Получить чат
    Route::get('/get_old_chat/{id}/{group}/{last_id}', [ChatController::class, 'get_old_chat'])->name('get_old_chat');   ///Получить старые сообщения
    Route::get('/get_new_chat/{id}/{group}/{first_id}', [ChatController::class, 'get_new_chat'])->name('get_new_chat');   ///Получить новые сообщения
    Route::post('/upload_file_chat/{group}/{id}', [ChatController::class, 'upload_file_chat'])->name('upload_file_chat');   ///Загрузка файла
    Route::get('/download_file_chat/{uid}', [ChatController::class, 'download_file_chat'])->name('download_file_chat');   ///Скачивание файла
    Route::get('/get_user_info/{id}', [ChatController::class, 'get_user_info'])->name('get_user_info');   ///получаем данные по пользователю
    Route::get('/get_user_files/{id}', [ChatController::class, 'get_user_files'])->name('get_user_files');   ///получаем вложения чата
    Route::get('/get_group_files/{id}', [ChatController::class, 'get_group_files'])->name('get_group_files');   ///получаем вложения группы
    Route::get('/get_group_info/{id}', [ChatController::class, 'get_group_info'])->name('get_group_info');   ///получаем список пользователей
    Route::get('/delete_group/{id}', [ChatController::class, 'delete_group'])->name('delete_group');   ///Удаляем группу
    Route::get('/delete_user_from_group/{user_id}/{group_id}', [ChatController::class, 'delete_user_from_group'])->name('delete_user_from_group');   ///Удаляем пользователя из группы
    Route::get('/add_user_to_group/{group_id}', [ChatController::class, 'add_user_to_group'])->name('add_user_to_group');   ///Добавляем пользователя в группу
    Route::get('/save_new_member/{user_id}/{group_id}', [ChatController::class, 'save_new_member'])->name('save_new_member');   ///Сохраняем пользователя в группу
    Route::get('/get_user_block', [ChatController::class, 'get_user_block'])->name('get_user_block');   ///Получаем список чатов

    //БЛОК журналов
    Route::get('/admin_journal', [JournalController::class, 'user_log']);
    Route::get('/admin_journal_data/{date_start}/{date_stop}', [JournalController::class, 'admin_journal_data']);

    //БЛОК настройки сигналов
    Route::get('/signal_settings/{id_param}', [SettingController::class, 'signal_settings']);
    Route::get('/save_signal_settings/{id}/{name_param}/{new_value}', [SettingController::class, 'save_signal_settings']);
    Route::get('/visible_param/{id}', [SettingController::class, 'visible_param']);
    Route::get('/delete_param/{id}', [SettingController::class, 'delete_param']);

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
    Route::get('/get_data_for_graph/{param_id}/{date_start}/{date_stop}', [MainController::class, 'get_data_for_graph']);   //Данные для отображения в графике
    Route::post('/confirm_hour', [MainController::class, 'confirm_hour']);   //Подтверждение/снятие достоверности
    Route::get('/get_confirmed_hours/{date}', [MainController::class, 'get_confirmed_hours']);   //Получение подтвержденных часовиков
    Route::post('/copy_hour', [MainController::class, 'copy_hour']);   //Откуда и куда копируем

    //БЛОК SIDE_MENU
    Route::get('/get_side_object', [MainController::class, 'get_side_object'])->name('get_side_object');    ///Получить древо объектов
    Route::get('/store_new_object/{parent_id}/{name_new_object}', [MainController::class, 'store_new_object'])->name('store_new_object');    ///Сохранить новый объект
    Route::get('/store_new_name/{id}/{new_name}', [MainController::class, 'store_new_name'])->name('store_new_name');    ///Переименовать объект
    Route::post('/store_new_signal/{parent_id}', [MainController::class, 'store_new_signal'])->name('store_new_signal');    ///Сохранить новые сигналы
    Route::get('/delete_object/{parent_id}', [MainController::class, 'delete_object'])->name('delete_object');    ///Сохранить новые сигналы

});

Route::get('/logout',  [Controller::class, 'logout']);
