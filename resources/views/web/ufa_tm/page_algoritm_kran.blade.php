@extends('layouts.app')
@section('title')
    Комплексное опробование кранов
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Комплексное опробование кранов от {{date('Y-m-d H:i', strtotime($result['date']))}}</p>
        @if(!$result['checked'])
            <button class="btn header_blocks btn_img" onclick="get_data_from_opc()" data-toggle="tooltip" title="Загрузить результаты" ><img src="/assets/img/save.svg"></button>
        @endif
        <button id="download_csw" class="btn header_blocks btn_img" onclick="printPage()" data-toggle="tooltip" title="Загрузить PDF" ><img src="/assets/img/pdf.svg"></button>
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px)" id="main_div">
        <table style="table-layout: fixed" class="statickTable">
            <colgroup>
                <col style="width: 400px">

            </colgroup>
            <thead>
                <tr>
                    <th style="width: 450px">Наименование</th>
                    <th>Время запуска</th>
                    <th>Длительность</th>
                    <th>Результат</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <th style="text-align: left">Охранный кран ГРС. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_1']}}</td>
                <td style="text-align: center">{{$result['duration_kran_1']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_1']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Охранный кран ГРС. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_2']}}</td>
                <td style="text-align: center">{{$result['duration_kran_2']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_2']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Входной кран ПГ. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_3']}}</td>
                <td style="text-align: center">{{$result['duration_kran_3']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_3']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Входной кран ПГ. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_4']}}</td>
                <td style="text-align: center">{{$result['duration_kran_4']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_4']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Байпасный кран ПГ. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_5']}}</td>
                <td style="text-align: center">{{$result['duration_kran_5']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_5']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Байпасный кран ПГ. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_6']}}</td>
                <td style="text-align: center">{{$result['duration_kran_6']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_6']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Выходной кран ПГ. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_7']}}</td>
                <td style="text-align: center">{{$result['duration_kran_7']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_7']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Выходной кран ПГ. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_8']}}</td>
                <td style="text-align: center">{{$result['duration_kran_8']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_8']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Входной кран нит ки 1 редуцирования. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_9']}}</td>
                <td style="text-align: center">{{$result['duration_kran_9']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_9']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Входной кран нит ки 1 редуцирования. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_10']}}</td>
                <td style="text-align: center">{{$result['duration_kran_10']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_10']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Входной кран нит ки 2 редуцирования. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_11']}}</td>
                <td style="text-align: center">{{$result['duration_kran_11']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_11']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Входной кран нит ки 2 редуцирования. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_12']}}</td>
                <td style="text-align: center">{{$result['duration_kran_12']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_12']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Входной кран ГРС. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_13']}}</td>
                <td style="text-align: center">{{$result['duration_kran_13']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_13']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Входной кран ГРС. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_14']}}</td>
                <td style="text-align: center">{{$result['duration_kran_14']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_14']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Свечной выс кран ГРС. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_15']}}</td>
                <td style="text-align: center">{{$result['duration_kran_15']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_15']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Свечной выс кран ГРС. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_16']}}</td>
                <td style="text-align: center">{{$result['duration_kran_16']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_16']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Выходной кран ГРС. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_17']}}</td>
                <td style="text-align: center">{{$result['duration_kran_17']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_17']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Выходной кран ГРС. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_18']}}</td>
                <td style="text-align: center">{{$result['duration_kran_18']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_18']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Байпасный кран ГРС. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_19']}}</td>
                <td style="text-align: center">{{$result['duration_kran_19']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_19']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Байпасный кран ГРС. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_20']}}</td>
                <td style="text-align: center">{{$result['duration_kran_20']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_20']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Свечной низ кран ГРС. Открытие</th>
                <td style="text-align: center">{{$result['date_kran_21']}}</td>
                <td style="text-align: center">{{$result['duration_kran_21']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_21']}}</td>
            </tr>
            <tr>
                <th style="text-align: left">Свечной низ кран ГРС. Закрытие</th>
                <td style="text-align: center">{{$result['date_kran_22']}}</td>
                <td style="text-align: center">{{$result['duration_kran_22']}} сек.</td>
                <td style="text-align: center">{{$result['result_kran_22']}}</td>
            </tr>

            </tbody>
        </table>
    </div>
    <style>
        .statickTable th{border: 1px solid #ddd;}
        th, td{font-size: 16px; padding: 5px 10px}
        tr:hover td, tr:hover th{
            background: lightgrey;
        }
    </style>
    <script>
        $(document).ready(function () {

        })
        function printPage(){
            var new_html = document.getElementById('main_div').innerHTML
            document.body.innerText = ''
            document.body.innerHTML = '<h4 style="width:100%; text-align:center">Опробование кранов от {{date('Y-m-d H:i', strtotime($result['date']))}}</h4>'
            document.body.innerHTML += '<style>.statickTable th{border: 1px solid #ddd;}</style>'
            document.body.innerHTML += new_html
            window.print()
            $('body').on('click', function (){
                window.location.reload()
            })
        }
        function get_data_from_opc(){
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "http://192.168.1.3:8000/read_opc_ua_kran", false);
            setTimeout(reload, 2000)
            xhr.send()
        }
        function reload(){window.location.reload()}
    </script>
@endsection

