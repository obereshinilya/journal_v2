<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HourController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LdapController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\RezhimController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;


Route::group(['middleware' => ['auth']], function() {


    Route::get('/test', [LdapController::class, 'test']);

    //БЛОК отчетов
    Route::get('/reports', [ReportController::class, 'reports'])->name('reports');   ///Главная страница отчетов
    Route::get('/journal_sodu', [JournalController::class, 'journal_sodu'])->name('journal_sodu');   ///Страница журнала СОДУ
    Route::get('/journal_sodu_data/{date_start}/{date_stop}', [JournalController::class, 'journal_sodu_data'])->name('journal_sodu_data');   ///Данные журнала СОДУ
    Route::get('/get_dropbox_sodu_data', [JournalController::class, 'get_dropbox_sodu_data'])->name('get_dropbox_sodu_data');   ///Получить данные в дропбокс
    Route::post('/edit_sodu', [JournalController::class, 'edit_sodu'])->name('edit_sodu');   ///Сохранить изменения в журнале
    Route::post('/delete_sodu', [JournalController::class, 'delete_sodu'])->name('delete_sodu');   ///Удалить запись
    Route::post('/create_sodu', [JournalController::class, 'create_sodu'])->name('create_sodu');   ///Создать запись
    //Журнал событий
    Route::get('/journal_events', [JournalController::class, 'journal_events'])->name('journal_events');   ///Страница журнала событий
    Route::get('/journal_events_data/{date_start}/{date_stop}', [JournalController::class, 'journal_events_data'])->name('journal_events_data');   ///Данные журнала событий
    Route::get('/get_subdivisions', [JournalController::class, 'get_subdivisions'])->name('get_subdivisions');   ///запрос подразделений
    Route::post('/edit_subdivisions', [JournalController::class, 'edit_subdivisions'])->name('edit_subdivisions');   ///изменение подразделений
    Route::post('/create_subdivision', [JournalController::class, 'create_subdivision'])->name('create_subdivision');   ///добавление подразделений
    Route::get('/get_types', [JournalController::class, 'get_types'])->name('get_types');   ///запрос типов
    Route::post('/edit_types', [JournalController::class, 'edit_types'])->name('edit_types');   ///изменение типов
    Route::post('/create_types', [JournalController::class, 'create_types'])->name('create_types');   ///добавление типов
    Route::get('/get_service', [JournalController::class, 'get_service'])->name('get_service');   ///запрос служб
    Route::post('/edit_service', [JournalController::class, 'edit_service'])->name('edit_service');   ///изменение служб
    Route::post('/create_service', [JournalController::class, 'create_service'])->name('create_service');   ///добавление служб
    Route::get('/get_templates/{type_event}', [JournalController::class, 'get_templates'])->name('get_templates');   ///запрос шаблонов
    Route::post('/edit_templates', [JournalController::class, 'edit_templates'])->name('edit_templates');   ///изменение шаблонов
    Route::post('/create_templates', [JournalController::class, 'create_templates'])->name('create_templates');   ///создание шаблонов
    Route::post('/save_event/{id}', [JournalController::class, 'save_event'])->name('save_event');   ///Сохраняем событие
    Route::get('/get_record_event/{id}', [JournalController::class, 'get_record_event'])->name('get_record_event');   ///Читаем событие
    Route::get('/setting_journal_events', [JournalController::class, 'setting_journal_events'])->name('setting_journal_events');   ///Страница настроек журнала событий
    //Режимные листы
    Route::get('/admin_rezhim_lists/{id}', [RezhimController::class, 'admin_rezhim_lists'])->name('admin_rezhim_lists');   ///Страница создания и редактирования режимных листов
    Route::post('/create_new_rezhim', [RezhimController::class, 'create_new_rezhim'])->name('create_new_rezhim');   ///Сохранение нового режимного листа
    Route::get('/create_new_param/{id}', [RezhimController::class, 'create_new_param'])->name('create_new_param');   ///Добавляем параметр в лист
    Route::get('/get_rezhim_params/{id}', [RezhimController::class, 'get_rezhim_params'])->name('get_rezhim_params');   ///Получаем параметры листа
    Route::post('/update_name/{id}', [RezhimController::class, 'update_name'])->name('update_name');   ///Обновляем имя листа
    Route::post('/edit_rezhim/{id_rezhim}', [RezhimController::class, 'edit_rezhim'])->name('edit_rezhim');   ///Изменение режимного листа
    Route::get('/select_param/{id_rezhim}/{id_param}', [RezhimController::class, 'select_param'])->name('select_param');   ///Страница для выбора источника
    Route::get('/save_select_param/{id_param}/{select_id}', [RezhimController::class, 'save_select_param'])->name('save_select_param');   ///Сохранение источника
    Route::get('/delete_rezhim/{id}', [RezhimController::class, 'delete_rezhim'])->name('delete_rezhim');   ///Удалить режимный лист
    Route::post('/delete_rezhim_params/{id_rezhim}', [RezhimController::class, 'delete_rezhim_params'])->name('delete_rezhim_params');   ///Удалить параметры из листа
    Route::get('/rezhim_list/{id}', [RezhimController::class, 'rezhim_list'])->name('rezhim_list');   ///Страница режимного листа
    /////Пробую вывод данных режима
    Route::get('/rezhim_data/{id}/{date}', [RezhimController::class, 'rezhim_data'])->name('rezhim_data');   ///Данные режимного листа
    Route::post('/save_formula', [RezhimController::class, 'save_formula'])->name('save_formula');   ///Сохраняем формулу
    Route::post('/save_hand_param', [RezhimController::class, 'save_hand_param'])->name('save_hand_param');   ///Сохраняем значение ручного ввода
    Route::post('/delete_confirm_rezhim', [RezhimController::class, 'delete_confirm_rezhim'])->name('delete_confirm_rezhim');   ///Снятие достоверности
    Route::post('/confirm_rezhim', [RezhimController::class, 'confirm_rezhim'])->name('confirm_rezhim');   ///Установка достоверности


    //БЛОК общих настроек
    Route::get('/main_setting', [SettingController::class, 'main_setting'])->name('main_setting');   ///Общая страница настроек
    Route::post('/save_main_setting/{param}', [SettingController::class, 'save_main_setting'])->name('save_main_setting');   ///Сохранение настроек
    Route::get('/save_opc/{param}/{value}', [SettingController::class, 'save_opc'])->name('save_opc');   ///Сохранение настроек

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
    Route::get('/xml_journal', [JournalController::class, 'xml_journal']);
    Route::get('/xml_journal_data/{date_start}/{date_stop}', [JournalController::class, 'xml_journal_data']);

    //БЛОК настройки сигналов
    Route::get('/signal_settings/{id_param}', [SettingController::class, 'signal_settings']);
    Route::get('/save_signal_settings/{id}/{name_param}/{new_value}', [SettingController::class, 'save_signal_settings']);
    Route::get('/visible_param/{id}', [SettingController::class, 'visible_param']);
    Route::get('/delete_param/{id}', [SettingController::class, 'delete_param']);
    Route::get('/check_param_in_rezhim/{id}', [SettingController::class, 'check_param_in_rezhim']);

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
    Route::get('/custom_list/{id_list}', [HourController::class, 'custom_list'])->name('custom_list');    ///Страница создания или редактирования своего списка
    Route::post('/save_list', [HourController::class, 'save_list'])->name('save_list');    ///Сохраняем список
    Route::post('/update_list/{id_list}', [HourController::class, 'update_list'])->name('update_list');    ///Обновляем список
    Route::get('/get_user_lists', [HourController::class, 'get_user_lists'])->name('get_user_lists');    ///Получаем списки для пользователя
    Route::get('/get_custom_data/{id_list}/{date}', [HourController::class, 'get_custom_data'])->name('get_custom_data');    ///Получаем данные в часовки для собственных листов
    Route::get('/custom_param_minutes/{id_list}/{date}/{hour}', [HourController::class, 'custom_param_minutes']); //Значения минуток для собственных листов
    Route::get('/delete_list/{id_list}', [HourController::class, 'delete_list']); //Удаление листа для всех
    Route::get('/hide_list/{id_list}', [HourController::class, 'hide_list']); //Удаление листа для пользователя
    Route::get('/get_custom_list', [HourController::class, 'get_custom_list']); //Все списки, которых нет и пользователя
    Route::get('/post_copy_custom_list/{id_list}', [HourController::class, 'post_copy_custom_list']); //Сохранение добавления списка

});

Route::get('/logout',  [Controller::class, 'logout']);
