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
            if(Number(cell.getAttribute('data-y'))>0){
                var arr = new Map()
                arr.set('date', $(`table thead tr td[data-x="${x}"]`)[0].textContent)
                arr.set('numRow', Number(cell.getAttribute('data-y'))+1)
                arr.set('rezhim', {{$id_rezhim}})
                arr.set('value', cell.textContent)
                $.ajax({
                    url: '/save_hand_param',
                    method: 'POST',
                    data: Object.fromEntries(arr),
                    success: function (res) {
                        console.log(res)
                    }
                })
            }
        }
        function get_table_data(){
            document.getElementById('search_row').value = ''
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-80)/1 + 'px'
            $.ajax({
                url: '/rezhim_data/{{$id_rezhim}}/'+$("#date_start").val(),
                method: 'GET',
                dataType: 'html',
                async: false,
                success: function(res) {
                    res = JSON.parse(res)
                    var hiddenRows = res['hidden_rows']
                    delete res['hidden_rows']
                    var hiddenColumn = res['hidden_column']
                    delete res['hidden_column']
                    var column_array = [
                        {type:'hidden',name:'id'},
                        {width:'30px',type:'image',name:'img',title:' ', readOnly: true},
                        {width:'400px',type:'html',name:'full_name',title:'Наименование', readOnly: true},
                        {width:'100px',type:'text',name:'e_unit',title:'Ед. изм.', readOnly: true}
                    ]
                    var keys = Object.keys(res[0])
                    for (var i=4; i<keys.length; i++){
                        column_array.push({type:'text', width: '150px', title: keys[i], name: keys[i]})
                    }
                    jsTable = jspreadsheet(document.getElementById('main_div'), {
                        data:Object.values(res),
                        search:true,
                        tableOverflow: true,
                        filters: false,
                        tableWidth: $('#main_div').width()+'px',
                        tableHeight: $('#main_div').height()+'px',
                        rowResize: false,
                        onchange: changed,
                        allowInsertRow:false,
                        columns: column_array,
                        updateTable: function(el, cell, x, y, source, value, id) {
                            if (hiddenColumn.indexOf(x) !== -1){
                                cell.classList.add('readonly')
                                cell.classList.add('confirmed')
                            }else if((hiddenRows.indexOf(y) !== -1 && x!==1 && x!==2 && x!==3)){
                                cell.classList.add('readonly')
                                cell.classList.add('unvisible')
                            }
                        },
                        csvFileName: '{{$name_rezhim}}'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download()
                    })
                    $('.jexcel_column_filter').on('click', function (){
                        document.getElementById('search_row').value = ''
                    })
                    $('table thead tr td[data-x]').on('contextmenu', function (){
                        var x = Number(this.getAttribute('data-x'))
                        if (x>3){
                            if ($(`[data-x="${x}"][data-y="0"]`)[0].classList.contains('confirmed')){
                                change_header_modal('Снять отметку достоверности?')
                                document.getElementById('submit_button_side_menu').setAttribute('onclick', `delete_confirm('${this.textContent}')`)
                            }else {
                                change_header_modal('Подтвердить достоверность?')
                                document.getElementById('submit_button_side_menu').setAttribute('onclick', `confirm('${this.textContent}', ${x})`)
                            }
                            open_modal_side_menu()
                        }
                    })
                }
            })
        }
        function confirm(time, x){
            var data = []
            var column = $(`table tbody td[data-x="${x}"]`)
            for(var cell of column){
                if(cell.classList.contains('unvisible')){
                    data[`${Number(cell.getAttribute('data-y'))+1}`] = cell.textContent
                }
            }
            var arr = new Map()
            arr.set('date', time)
            arr.set('rezhim', {{$id_rezhim}})
            arr.set('data', data)
            $.ajax({
                url: '/confirm_rezhim',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    console.log(res)
                    get_table_data()
                    close_modal_side_menu()
                }
            })
        }
        function delete_confirm(time){
            var arr = new Map()
            arr.set('date', time)
            arr.set('rezhim', {{$id_rezhim}})
            $.ajax({
                url: '/delete_confirm_rezhim',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_table_data()
                    close_modal_side_menu()
                }
            })
        }
        function seach_jsExcel(){
            var input = document.getElementById('search_row')
            jsTable.search(input.value);
        }
    </script>

@endsection

