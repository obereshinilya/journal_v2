@extends('layouts.app')
@section('title')
    Журнал действий оператора
@endsection
@section('content')
    <style>
        .content{
            width: 100%;
        }
    </style>
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Журнал действий оператора</p>
        <button id="download_csw" class="btn header_blocks btn_img" data-toggle="tooltip" title="Загрузить CSV"><img src="/assets/img/excel.svg"></button>
        <input class="input header_blocks" style="width: 200px" oninput="seach_jsExcel()" type="text" id="search_row" placeholder="Поиск...">
        @include('include.period_date_time')

    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px)" id="main_div">

    </div>
    <script>
        $(document).ready(function () {
            get_table_data()
        })

        function get_table_data(){
            document.getElementById('search_row').value = ''
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-270)/3 + 'px'
            var date_str = $("#period").val().replace(/ /g,'')
            date_str = date_str.split('-')
            $.ajax({
                url: '/admin_journal_data/'+date_str[0]+'/'+date_str[1],
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    jsTable = jspreadsheet(document.getElementById('main_div'), {
                        data:JSON.parse(res),
                        search:true,
                        // pagination:10,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#main_div').width()+'px',
                        tableHeight: $('#main_div').height()+'px',
                        rowResize: false,
                        columns: [
                            {width:width,type:'text',name:'username',title:'Пользователь',readOnly:true,},
                            {width:width,type:'text',name:'event',title:'Действие',readOnly:true,},
                            {width:width,type:'text',name:'comment',title:'Описание',readOnly:true,},
                            {width:'200px',type:'calendar',name:'date',title:'Дата', options: { format:'DD.MM.YYYY HH:mm' },readOnly:true,},
                        ],
                        csvFileName: 'Журнал_действий_оператора'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download()
                    })
                    $('.jexcel_column_filter').on('click', function (){
                        document.getElementById('search_row').value = ''
                        // seach_jsExcel()
                    })
                }
            })
        }
        function seach_jsExcel(){
            var input = document.getElementById('search_row')
            jsTable.search(input.value);
        }

    </script>

@endsection
