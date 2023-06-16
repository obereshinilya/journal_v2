@extends('layouts.app')
@section('title')
    Опробование алгоритнов/проведение регламентных работ
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Опробование алгоритнов/проведение регламентных работ от {{date('Y-m-d H:i', strtotime($result['date']))}}</p>
        @if(!$result['checked'])
            <button class="btn header_blocks btn_img"  data-toggle="tooltip" title="Загрузить результаты" ><img src="/assets/img/save.svg"></button>
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
                <td>{{$result['p_low_date']}}</td>
                <td>{{$result['p_low_interval']}}</td>
                <td>{{$result['p_low_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">По высокому давлению</th>
                <td>{{$result['p_hi_date']}}</td>
                <td>{{$result['p_hi_interval']}}</td>
                <td>{{$result['p_hi_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">По пожару <br>(БР, топочная, расходомерная)</th>
                <td>{{$result['fire_date']}}</td>
                <td>{{$result['fire_interval']}}</td>
                <td>{{$result['fire_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">По кнопке</th>
                <td>{{$result['button_date']}}</td>
                <td>{{$result['button_interval']}}</td>
                <td>{{$result['button_result']}}</td>
            </tr>
            <tr>
                <th rowspan="2">Аварийный останов ГРС</th>
                <th style="text-align: left">По пожару <br>(блок перекл. или операторная)</th>
                <td>{{$result['fire_two_date']}}</td>
                <td>{{$result['fire_two_interval']}}</td>
                <td>{{$result['fire_two_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">По кнопке</th>
                <td>{{$result['button_two_date']}}</td>
                <td>{{$result['button_two_interval']}}</td>
                <td>{{$result['button_two_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Проверка пожарной сигнализации</th>
                <td>{{$result['fire_alarm_date']}}</td>
                <td>{{$result['fire_alarm_interval']}}</td>
                <td>{{$result['fire_alarm_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Проверка систем контроля загазованности</th>
                <td>{{$result['gas_alarm_date']}}</td>
                <td>{{$result['gas_alarm_interval']}}</td>
                <td>{{$result['gas_alarm_result']}}</td>
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
                <td>{{$result['filter_date']}}</td>
                <td>{{$result['filter_interval']}}</td>
                <td>{{$result['filter_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Ревизия подогревателя газа</th>
                <td>{{$result['heat_date']}}</td>
                <td>{{$result['heat_interval']}}</td>
                <td>{{$result['heat_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Ревизия регуляторов давления</th>
                <td>{{$result['regulator_date']}}</td>
                <td>{{$result['regulator_interval']}}</td>
                <td>{{$result['regulator_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Опробование системы резервного электроснабжения</th>
                <td>{{$result['electro_date']}}</td>
                <td>{{$result['electro_interval']}}</td>
                <td>{{$result['electro_result']}}</td>
            </tr>
            <tr>
                <th style="text-align: left" colspan="2">Проверка автоматики подогревателя</th>
                <td>{{$result['auto_heat_date']}}</td>
                <td>{{$result['auto_heat_interval']}}</td>
                <td>{{$result['auto_heat_result']}}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <style>
        .statickTable th{border: 1px solid #ddd;}
        th, td{font-size: 16px; padding: 5px 10px}
    </style>
    <script>
        $(document).ready(function () {

        })
        function printPage(){
            var new_html = document.getElementById('main_div').innerHTML
            document.body.innerText = ''
            document.body.innerHTML = '<h4 style="width:100%; text-align:center">Опробование алгоритнов/проведение регламентных работ от {{date('Y-m-d H:i', strtotime($result['date']))}}</h4>'
            document.body.innerHTML += '<style>.statickTable th{border: 1px solid #ddd;}</style>'
            document.body.innerHTML += new_html
            window.print()
            $('body').on('click', function (){
                window.location.reload()
            })
        }
    </script>
@endsection

