@extends('layouts.app')
@section('title')
    Опробование алгоритмов/проведение регламентных работ
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Опробование алгоритмов/проведение регламентных работ от {{date('Y-m-d H:i', strtotime($result['date']))}}</p>
        @if(!$result['checked'])
            <button class="btn header_blocks btn_img" onclick="get_data_from_opc()" data-toggle="tooltip" title="Загрузить результаты" ><img src="/assets/img/save.svg"></button>
        @endif
        <button id="download_csw" class="btn header_blocks btn_img" onclick="printPage()" data-toggle="tooltip" title="Загрузить PDF" ><img src="/assets/img/pdf.svg"></button>
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px)" id="main_div">
        <table style="table-layout: fixed" class="statickTable">
            <colgroup>
                <col style="width: 170px">
                <col style="width: 270px">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="5">Комплексное опробование алгоритмов и сигнализации</th>
                </tr>
                <tr>
                    <th colspan="2">Перечень алгоритмов</th>
                    <th>Время запуска</th>
                    <th>Длительность</th>
                    <th>Результат</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <th rowspan="4">Автоматический переход на байпасную линию</th>
                <th style="text-align: left">По низкому давлению</th>
                <td style="text-align: center">{{$result['p_low_date']}}</td>
                <td style="text-align: center">{{$result['p_low_interval']}} сек.</td>
                <td style="text-align: center">{{$result['p_low_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">По высокому давлению</th>
                <td style="text-align: center">{{$result['p_hi_date']}}</td>
                <td style="text-align: center">{{$result['p_hi_interval']}} сек.</td>
                <td style="text-align: center">{{$result['p_hi_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">По пожару <br>(БР, топочная, расходомерная)</th>
                <td style="text-align: center">{{$result['fire_date']}}</td>
                <td style="text-align: center">{{$result['fire_interval']}} сек.</td>
                <td style="text-align: center">{{$result['fire_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">По кнопке</th>
                <td style="text-align: center">{{$result['button_date']}}</td>
                <td style="text-align: center">{{$result['button_interval']}} сек.</td>
                <td style="text-align: center">{{$result['button_result']}}</td>
            </tr>
            <tr>
                <th rowspan="2">Аварийный останов ГРС</th>
                <th style="text-align: left">По пожару <br>(блок перекл. или операторная)</th>
                <td style="text-align: center">{{$result['fire_two_date']}}</td>
                <td style="text-align: center">{{$result['fire_two_interval']}} сек.</td>
                <td style="text-align: center">{{$result['fire_two_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">По кнопке</th>
                <td style="text-align: center">{{$result['button_two_date']}}</td>
                <td style="text-align: center">{{$result['button_two_interval']}} сек.</td>
                <td style="text-align: center">{{$result['button_two_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Проверка пожарной сигнализации</th>
                <td style="text-align: center">{{$result['fire_alarm_date']}}</td>
                <td style="text-align: center">{{$result['fire_alarm_interval']}} сек.</td>
                <td style="text-align: center">{{$result['fire_alarm_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Проверка систем контроля загазованности</th>
                <td style="text-align: center">{{$result['gas_alarm_date']}}</td>
                <td style="text-align: center">{{$result['gas_alarm_interval']}} сек.</td>
                <td style="text-align: center" style="text-align: center">{{$result['gas_alarm_result']}}</td>
            </tr>
            </tbody>
            <thead>
            <tr>
                <th colspan="5">Выполнение регламентных работ на ГРС</th>
            </tr>
            <tr>
                <th colspan="2">Перечень алгоритмов</th>
                <th>Время запуска</th>
                <th>Длительность</th>
                <th>Результат</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th style="text-align: left" colspan="2">Ревизия фильтров очистки газа</th>
                <td style="text-align: center">{{$result['filter_date']}}</td>
                <td style="text-align: center">{{$result['filter_interval']}} сек.</td>
                <td style="text-align: center">{{$result['filter_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Ревизия подогревателя газа</th>
                <td style="text-align: center">{{$result['heat_date']}}</td>
                <td style="text-align: center">{{$result['heat_interval']}} сек.</td>
                <td style="text-align: center">{{$result['heat_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Ревизия регуляторов давления</th>
                <td style="text-align: center">{{$result['regulator_date']}}</td>
                <td style="text-align: center">{{$result['regulator_interval']}} сек.</td>
                <td style="text-align: center">{{$result['regulator_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Опробование системы резервного электроснабжения</th>
                <td style="text-align: center">{{$result['electro_date']}}</td>
                <td style="text-align: center">{{$result['electro_interval']}} сек.</td>
                <td style="text-align: center">{{$result['electro_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Проверка автоматики подогревателя</th>
                <td style="text-align: center">{{$result['auto_heat_date']}}</td>
                <td style="text-align: center">{{$result['auto_heat_interval']}} сек.</td>
                <td style="text-align: center">{{$result['auto_heat_result']}}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <style>
        .statickTable th{border: 1px solid #ddd;}
        th, td{font-size: 16px; padding: 5px 10px}
        tr:hover td{
            background: lightgrey;
        }
    </style>
    <script>
        $(document).ready(function () {

        })
        function printPage(){
            var new_html = document.getElementById('main_div').innerHTML
            document.body.innerText = ''
            document.body.innerHTML = '<h4 style="width:100%; text-align:center">Опробование алгоритмов/проведение регламентных работ от {{date('Y-m-d H:i', strtotime($result['date']))}}</h4>'
            document.body.innerHTML += '<style>.statickTable th{border: 1px solid #ddd;}</style>'
            document.body.innerHTML += new_html
            window.print()
            $('body').on('click', function (){
                window.location.reload()
            })
        }
        function get_data_from_opc(){
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "http://192.168.1.3:8000/read_opc_ua", false);
            setTimeout(reload, 2000)
            xhr.send()
        }
        function reload(){window.location.reload()}
    </script>
@endsection

