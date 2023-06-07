@extends('layouts.app')
@section('title')
    Режимный лист
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">{{$name_rezhim}}</p>
        <button id="download_csw" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Загрузить CSV" ><img src="/assets/img/excel.svg"></button>
        <input class="input header_blocks" style="width: 200px" oninput="seach_jsExcel()" type="text" id="search_row" placeholder="Поиск...">
        @include('include.date')
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 110px); margin-top: 10px" id="main_div">

    </div>
    <script>
        $(document).ready(function () {
            get_table_data()
        })
        var changed = function (instance, cell, x, y, value){
            // var column = ['fio', 'event', 'type_event', 'otdel']
            // var id = cell.parentNode.lastElementChild.textContent
            // var arr = new Map()
            // arr.set('column', column[x])
            // arr.set('value', value)
            // arr.set('id', id)
            // $.ajax({
            //     url: '/edit_sodu',
            //     method: 'POST',
            //     data: Object.fromEntries(arr),
            //     success: function (res) {
            //
            //     }
            // })
        }
        function get_table_data(){
            document.getElementById('search_row').value = ''
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-80)/1 + 'px'
            $.ajax({
                url: '/rezhim_data/{{$id_rezhim}}',
                method: 'GET',
                dataType: 'html',
                async: false,
                success: function(res) {
                    res = JSON.parse(res)
                    jsTable = jspreadsheet(document.getElementById('main_div'), {
                        data:res,
                        search:true,
                        tableOverflow: true,
                        filters: false,
                        tableWidth: $('#main_div').width()+'px',
                        tableHeight: $('#main_div').height()+'px',
                        rowResize: false,
                        onchange: changed,
                        allowInsertRow:false,
                        columns: [
                            {type:'hidden',name:'id'},
                            {width:'30px',type:'image',name:'img',title:' ', readOnly: true},
                            {width:'400px',type:'html',name:'full_name',title:'Наименование', readOnly: true},
                            {width:'100px',type:'text',name:'e_unit',title:'Ед. изм.', readOnly: true},
                        ],
                        csvFileName: '{{$name_rezhim}}'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download()
                    })
                    $('.jexcel_column_filter').on('click', function (){
                        document.getElementById('search_row').value = ''
                    })
                    get_data()
                }
            })
        }
        function seach_jsExcel(){
            var input = document.getElementById('search_row')
            jsTable.search(input.value);
        }
        function get_data(){
            $.ajax({
                url: '/rezhim_table_data/{{$id_rezhim}}/'+$("#date_start").val(),
                method: 'GET',
                dataType: 'html',
                async: false,
                success: function(res) {
                    res = JSON.parse(res)
                    var NumColumn = 3
                    for (var time of Object.keys(res)){
                        jsTable.insertColumn(res[time], NumColumn, false, { type:'text', title: time, readOnly: false, width:'150px'})
                        NumColumn++
                    }
                }
            })
        }
    </script>

@endsection

