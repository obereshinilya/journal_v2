@extends('layouts.app')
@section('title')
    Проверка буферизации
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Проверка буферизации</p>
        <button id="pdf_btn" class="btn header_blocks btn_img" data-toggle="tooltip" title="Загрузить PDF" ><img onclick="printTable()" src="/assets/img/pdf.svg"></button>
        <button id="download_csw" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Загрузить CSV" ><img src="/assets/img/excel.svg"></button>
        <input class="input header_blocks" style="width: 200px" oninput="seach_jsExcel()" type="text" id="search_row" placeholder="Поиск...">
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px)" id="main_div">

    </div>
    <script>
        $(document).ready(function () {
           get_table_data()
            setInterval(get_table_data, 7000)
        })

        function get_table_data(){
            document.getElementById('search_row').value = ''
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-80)/3 + 'px'
            $.ajax({
                url: '/test_bufer_data_discret/{{$type}}',
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
                            {width:width,type:'text',name:'name_param',title:'Наименование',readOnly:true,},
                            {width:width,type:'text',name:'time',title:'Время',readOnly:true,},
                            {width:width,type:'text',name:'status',title:'Состояние',readOnly:true,},
                        ],
                        csvFileName: 'Проверка буферизации'
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

    </script>
@endsection

