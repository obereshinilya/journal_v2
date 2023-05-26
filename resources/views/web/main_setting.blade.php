@extends('layouts.app')
@section('title')
    Общие настройки
@endsection
@section('content')
    <style>
        .content{width: 100%;}
        #main_div{width: 100%; height: calc(100% - 70px)}
        .container_card{width: 100%; overflow-y: hidden; height: 100%}
    </style>
    <p id="time_from_db" style="display: none">{{$setting['start_smena']}}</p>
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 90%">Общие настройки оперативного журнала диспетчера</p>
    </div>
    <div id="main_div">
        <div class="container_card">
            <div class="tab">
                <button class="setlinks active" style="width: 33%" onclick="openSettingBlock(this, 'hour_card')">Сеансовые данные</button>
                <button class="setlinks" style="width: 33%" onclick="openSettingBlock(this, 'masdu_card')">М АСДУ</button>
                <button class="setlinks" style="width: 33%" onclick="openSettingBlock(this, 'other_card')">Прочее</button>
            </div>

            <div id="hour_card" class="settingcontent" style="display: block">
                <div style="overflow-y: auto; height: 100%">
                    <table class="statickTable" style="width: 800px; direction: ltr; table-layout: auto; white-space: normal; text-align: center">
                        <tbody>
                        <tr><td colspan="3"><b>Отображение сеансовых данных</b></td></tr>
                            <tr>
                               <td rowspan="3">Разрешить редактирование</td>
                                <td>Данных, отправленных в М АСДУ</td>
                                <td style="width: 50px">
                                    <label class="switch">
                                        <input type="checkbox" @if($setting['masdu_edit'] == 'true') checked @endif onclick="save_setting('masdu_edit')">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Данных, введенных вручную</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" @if($setting['hand_edit'] == 'true') checked @endif onclick="save_setting('hand_edit')">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Данных, отмеченых как достоверные</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" @if($setting['authenticity_edit'] == 'true') checked @endif onclick="save_setting('authenticity_edit')">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Разрешить снятие отметки достоверности</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" @if($setting['authenticity_remove'] == 'true') checked @endif onclick="save_setting('authenticity_remove')">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Разрешить копирование сводок</td>
                                <td style="width: 50px">
                                    <label class="switch">
                                        <input type="checkbox" @if($setting['param_copy'] == 'true') checked @endif onclick="save_setting('param_copy')">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Разрешить копирование в достоверную сводку</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" @if($setting['authenticity_copy'] == 'true') checked @endif onclick="save_setting('authenticity_copy')">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">Время начала смены в @include('include.time')</td>
                            </tr>
                            <tr>
                                <td colspan="2">Отображение резкого изменения параметров</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" @if($setting['visible_risk'] == 'true') checked @endif onclick="save_setting('visible_risk')">
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">Отображение предупредительной отметки на уровне <input type="number" onchange="save_setting('percent_middle_risk', this.value)" style="text-align: center; margin: 0 10px" placeholder="" min="1" max="99" value="{{$setting['percent_middle_risk']}}"> %, отображая цветом <input type="color" style="margin-left: 10px" onchange="save_setting('color_middle_risk', this.value)" value="{{$setting['color_middle_risk']}}"</td>
                            </tr>
                            <tr>
                                <td colspan="3">Отображение критической отметки на уровне <input type="number" onchange="save_setting('percent_hight_risk', this.value)" style="text-align: center; margin: 0 10px" placeholder="" min="1" max="99" value="{{$setting['percent_hight_risk']}}"> %, отображая цветом <input type="color" style="margin-left: 10px" onchange="save_setting('color_hight_risk', this.value)" value="{{$setting['color_hight_risk']}}"></td>
                            </tr>

                        <tr><td colspan="2" style=""><b>Запись данных с OPC UA</b></td>
                            <td><button style="float: right; margin: 0 5px 0 0" class="btn btn_img" data-toggle="tooltip" title="Сохранить" ><img onclick="save_parser()" src="/assets/img/save.svg"></button></td>
                        </tr>
                            <tr>
                                <td colspan="3">Формирование данных за <input id="before_hour_start_read" type="number" style="text-align: center; margin: 0 10px" placeholder="" min="1" max="59" value="{{$setting['before_hour_start_read']}}"> минут до окончания часа</td>
                            </tr>
                            <tr>
                                <td colspan="3">Формирование суточных данных в <input type='text' id="day_write" placeholder="Выберите время..." class='datepicker-here' style="text-align: center; margin: 0 10px" value="{{$setting['day_write']}}" /></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div id="masdu_card" class="settingcontent" style="display: none">
                <div style="overflow-y: auto; height: 100%">
                    <table class="statickTable" style="width: 800px; direction: ltr; table-layout: auto; white-space: normal; text-align: center">
                        <tbody>
                        <tr>
                            <td rowspan="3">Основной сервер</td>
                            <td>Логин</td>
                            <td>
                                <input type="text" class="form-control" placeholder="Логин" value="sftp_main">
                            </td>
                        </tr>
                        <tr>
                            <td>Пароль</td>
                            <td><input type="password" class="form-control" placeholder="Пароль" value="sftp_main"></td>
                        </tr>
                        <tr>
                            <td>Папка назначения</td>
                            <td><input type="text" class="form-control" placeholder="Папка назначения" value="/input/"></td>
                        </tr>
                        <tr>
                            <td rowspan="3">Резервный сервер</td>
                            <td>Логин</td>
                            <td>
                                <input type="text" class="form-control" placeholder="Логин" value="sftp_reserv">
                            </td>
                        </tr>
                        <tr>
                            <td>Пароль</td>
                            <td><input type="password" class="form-control" placeholder="Пароль" value="sftp_main"></td>
                        </tr>
                        <tr>
                            <td>Папка назначения</td>
                            <td><input type="text" class="form-control" placeholder="Папка назначения" value="/input/"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Адрес почты для резервной отправки</td>
                            <td><input type="text" class="form-control" placeholder="Адрес почты" value="mail_reserv@mail.ru"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="other_card" class="settingcontent" style="display: none">
                <div style="overflow-y: auto; height: 100%">
                    <p>Раздел будет наполнен в ходе разработки</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            document.getElementById('only_time').value = '@if($setting['start_smena'] < 10) 0{{$setting['start_smena']}} @else {{$setting['start_smena']}} @endif:00'
            var today = new Date();
            today.setMinutes(0)
            new AirDatepicker('#day_write',
                {
                    timepicker: true,
                    onlyTimepicker: true,
                    maxHours: 23,
                    maxMinutes:59
                })
        })
        function openSettingBlock(evt, typeInfo) {
            var i, settingcontent, setlinks;
            settingcontent = document.getElementsByClassName("settingcontent");
            for (i = 0; i < settingcontent.length; i++) {
                settingcontent[i].style.display = "none";
            }
            setlinks = document.getElementsByClassName("setlinks");
            for (i = 0; i < setlinks.length; i++) {
                setlinks[i].className = setlinks[i].className.replace(" active", "");
            }
            document.getElementById(typeInfo).style.display = "block";
            evt.className += " active";
        }
        function save_setting(param, value){
            if (!value){
                value = 'false'
            }
            var arr = new Map()
            arr.set('value', value)
            $.ajax({
                url: '/save_main_setting/'+param,
                method: 'POST',
                async: true,
                data: Object.fromEntries(arr),
                success: function(res) {

                }
            })
        }
        function save_parser(){
            var data = {
                "db_name": 'journal',
                "db_user": 'postgres',
                "db_password": 'Potok-DU',
                "db_host": '127.0.0.1',
                "db_port": '5432',
                "tb_name": 'app_info.main_table',
                "tb_column_tag": 'tag_name',
                "tb_column_id_tag": 'id',
                "tb_insert_id_tag": 'param_id',
                "tb_insert_value": 'val',
                "tb_insert_timestamp": 'timestamp',
                "name_space" : 'ns=1;s=',
                "opc_master_host": 'opc.tcp://10.93.63.10:62544',
                "opc_slave_host": 'opc.tcp://10.93.63.10:62544',
                "rate_5_min_cl_table": 'app_info.min_params',
                "rate_5_min_cl_rate": '5',
                "rate_1_hour_cl_table": 'app_info.hour_params',
                "rate_1_hour_cl_rate": ':'+String(60 - Number(document.getElementById('before_hour_start_read').value)),
                "rate_1_day_cl_table": 'app_info.sut_params',
                "rate_1_day_cl_rate": document.getElementById('day_write').value+':00',
                "path_log_file": '/Logs/'
            };
            var xhr = new XMLHttpRequest();
            xhr.open('POST', "http://127.0.0.1:5002/conf_converter", true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    change_header_modal('Настройка записи данных выполнена!')
                    open_modal_side_menu()
                    document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                    var params = ['before_hour_start_read', 'day_write']
                    for(var param of params){
                        $.ajax({
                            url: '/save_opc/'+param+'/'+document.getElementById(param).value,
                            method: 'GET',
                            async: true,
                            success: function(res) {

                            }
                        })
                    }
                }else {
                    change_header_modal('Настройка записи данных не выполнена!<br>Ошибка!')
                    open_modal_side_menu()
                    document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                }
            };
            xhr.send(JSON.stringify(data));
        }
    </script>
<style>
    .settingcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
        overflow-y: hidden;
        height: calc(100% - 3em);
    }
</style>
@endsection
