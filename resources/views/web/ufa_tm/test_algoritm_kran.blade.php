@extends('layouts.app')
@section('title')
    Комплексное опробование кранов
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Комплексное опробование кранов</p>
        <button id="pdf_btn" class="btn header_blocks btn_img" data-toggle="tooltip" title="Загрузить PDF" ><img onclick="printTable()" src="/assets/img/pdf.svg"></button>
        <button id="download_csw" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Загрузить CSV" ><img src="/assets/img/excel.svg"></button>
        <input class="input header_blocks" style="width: 200px" oninput="seach_jsExcel()" type="text" id="search_row" placeholder="Поиск...">
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px)" id="main_div">

    </div>
    <script>
        $(document).ready(function () {
           get_table_data()
        })



        var update_comment = function (instance, cell, x, y, value){
            if (x==='1'){
                var arr = new Map()
                arr.set('comment', value)
                arr.set('id', cell.parentNode.getElementsByTagName('td')[1].textContent)
                $.ajax({
                    url: '/change_comment_ufa_kran',
                    method: 'POST',
                    data: Object.fromEntries(arr),
                    success: function (res) {
                    }
                })
            }
        }
        function get_table_data(){
            document.getElementById('search_row').value = ''
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-430) + 'px'
            $.ajax({
                url: '/get_data_ufa_tm_kran',
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
                        onchange: update_comment,
                        allowInsertRow:false,
                        columns: [
                            {type:'hidden',name:'id',title:'Номер записи'},
                            {width:width,type:'text',name:'comment',title:'Описание события',},
                            {width:'180px',type:'text',name:'date',title:'Дата запроса',readOnly:true,},
                            {width:'180px',type:'checkbox',name:'checked',title:'Данные сохранены',readOnly:true,},
                        ],
                        csvFileName: 'Комплексное опробование кранов'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download(true)
                    })
                    $('.jexcel_column_filter').on('dblclick', function (){
                        document.getElementById('search_row').value = ''
                    })
                    $('#main_content table tbody tr td').on('dblclick', function (){
                        if (this.getAttribute('data-x') !== '1'){
                            window.location.href = '/open_record_ufa_tm_kran/'+this.parentNode.getElementsByTagName('td')[1].textContent
                        }
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

