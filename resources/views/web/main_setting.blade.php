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
                <button class="setlinks active" style="width: 50%" onclick="openSettingBlock(this, 'hour_card')">Часовые показатели</button>
                <button class="setlinks" style="width: 50%" onclick="openSettingBlock(this, 'masdu_card')">М АСДУ</button>
            </div>

            <div id="hour_card" class="settingcontent" style="display: block">
                <div style="overflow-y: auto; height: 100%">
                    <table id="statickTable" class="statickTable" style="width: 800px; direction: ltr; table-layout: auto; white-space: normal; text-align: center">
                        <tbody>
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
                                <td>Время начала смены</td>
                                <td colspan="2">
                                    @include('include.time')
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="masdu_card" class="settingcontent" style="display: none">
                <div style="overflow-y: auto; height: 100%">
                    <p>Раздел в разработке</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            document.getElementById('only_time').value = '@if($setting['start_smena'] < 10) 0{{$setting['start_smena']}} @else {{$setting['start_smena']}} @endif:00'
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
            $.ajax({
                url: '/save_main_setting/'+param+'/'+value,
                method: 'GET',
                async: true,
                success: function(res) {

                }
            })
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
