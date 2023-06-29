@extends('layouts.app')
@section('title')
    Журнал перестановок
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Журнал перестановок</p>
        <button id="pdf_btn" class="btn header_blocks btn_img" data-toggle="tooltip" title="Загрузить PDF" ><img onclick="printTable()" src="/assets/img/pdf.svg"></button>
        <button id="download_csw" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Загрузить CSV" ><img src="/assets/img/excel.svg"></button>
        <button id="update" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Обновить" onclick="get_table_data()"><img src="/assets/img/refresh.svg"></button>
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
            var date_str = $("#period").val().replace(/ /g,'')
            date_str = date_str.split('-')
            var width = ($('#main_div').width()-80)/7 + 'px'
            $.ajax({
                url: '/get_data_journal_perestanovok/'+date_str[0]+'/'+date_str[1],
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    jsTable = jspreadsheet(document.getElementById('main_div'), {
                        data:JSON.parse(res),
                        search:true,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#main_div').width()+'px',
                        tableHeight: $('#main_div').height()+'px',
                        rowResize: false,
                        onchange: false,
                        allowInsertRow:false,
                        columns: [
                            {width:width,type:'text',name:'name_kran',title:'Наименование крана',readOnly:true},
                            {width:width,type:'text',name:'open',title:'Кол-во открытий',readOnly:true},
                            {width:width,type:'text',name:'date_open',title:'Время открытия',readOnly:true},
                            {width:width,type:'text',name:'close',title:'Кол-во закрытий',readOnly:true},
                            {width:width,type:'text',name:'date_close',title:'Время закрытия',readOnly:true},
                            {width:width,type:'text',name:'accident',title:'Кол-во аварий',readOnly:true},
                            {width:width,type:'text',name:'date_accident',title:'Время аварии',readOnly:true},
                        ],
                        csvFileName: 'Журнал перестановок'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download(true)
                    })
                    $('.jexcel_column_filter').on('dblclick', function (){
                        document.getElementById('search_row').value = ''
                    })
                }
            })
        }
        function seach_jsExcel(){
            var input = document.getElementById('search_row')
            jsTable.search(input.value);
        }
        function printTable(){
            var date_str = $("#period").val().replace(/ /g,'')
            date_str = date_str.split('-')
            var start = date_str[0]
            var stop = date_str[1]
            var new_html = document.getElementById('main_div').innerHTML
            document.body.innerText = ''
            document.body.innerHTML = `<h4 style="width:100%; text-align:center">Журнал перестановок с ${start} по ${stop}</h4>`
            document.body.innerHTML += new_html
            window.print()
            $('body').on('click', function (){
                window.location.reload()
            })
        }

    </script>
@endsection

