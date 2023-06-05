@extends('layouts.app')
@section('title')
    Режимный лист
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">
            @if($id != 'false')Редактирование режимного листа @else Создание режимного листа @endif</p>
    </div>
    <input id="name_rezhim" @if($id != 'false') onblur="update_name(this.value)" @endif type='text' placeholder="Укажите название режимного листа..." class="header_blocks" style="margin-left: 20px; min-width: 200px; float: left" />
    <button onclick="create_param()" class="btn header_blocks btn_img" style="float: left" data-toggle="tooltip" title="Добавить объект"><img src="/assets/img/add_plus_icon.svg"></button>
    <div style="width: calc(100% - 10px); height: calc(100% - 110px); margin-top: 10px" id="main_div">

    </div>
        @if($id != 'false')
    <script>
        $(document).ready(function () {
            document.getElementById('name_rezhim').value = '{{$name_rezhim}}'
            get_table_data()
        })
        var changed = function (instance, cell, x, y, value){
            var column = ['name', 'e_unit', 'num_row', 'level_row', 'folder','hand','calc', 'from_hour_params']
            var id_param = cell.parentNode.lastElementChild.textContent
            var arr = new Map()
            if (column[x] === 'level_row'){
                if (Number(value) < 1){
                    value = 1
                }
            }
            arr.set('column', column[x])
            arr.set('value', value)
            arr.set('id', id_param)
            $.ajax({
                url: '/edit_rezhim/{{$id}}',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    if(res){
                        window.location.href = '/select_param/{{$id}}/'+res
                    }else{
                        get_table_data()
                    }
                }
            })
        }
        function get_table_data(){
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-680) + 'px'
            $.ajax({
                url: '/get_rezhim_params/{{$id}}',
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    jsTable = jspreadsheet(document.getElementById('main_div'), {
                        data:JSON.parse(res),
                        search:false,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#main_div').width()+'px',
                        tableHeight: $('#main_div').height()+'px',
                        rowResize: false,
                        onchange: changed,
                        allowInsertRow:false,
                        columns: [
                            {width:width,type:'text',name:'name',title:'Наименование',},
                            {width:'100px',type:'text',name:'e_unit',title:'Ед.изм',},
                            {width:'115px',type:'numeric',name:'num_row',title:'Номер строки',},
                            {width:'90px',type:'numeric',name:'level_row',title:'Уровень',},
                            {width:'75px',type:'checkbox',name:'folder',title:'Каталог',},
                            {width:'75px',type:'checkbox',name:'hand',title:'Ручной',},
                            {width:'75px',type:'checkbox',name:'calc',title:'Расчетный',},
                            {width:'75px',type:'checkbox',name:'from_hour_params',title:'Ссылка',},
                            {type:'hidden',name:'id'},
                        ],
                        updateTable: function(el, cell, x, y, source, value, id) {
                            if (source === true && x !== 4) {
                                cell.getElementsByTagName('input')[0].setAttribute('disabled', true);
                                cell.classList.add('readonly')
                            }
                        }
                    });
                }
            })
        }
        function update_name(value){
            var arr = new Map()
            arr.set('name_rezhim', value)
            $.ajax({
                url: '/update_name/{{$id}}',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    window.location.href = '/admin_rezhim_lists/{{$id}}'
                }
            })
        }
        function create_param(){
            $.ajax({
                url: '/create_new_param/{{$id}}',
                method: 'get',
                success: function (res) {
                    window.location.href = '/admin_rezhim_lists/{{$id}}'
                }
            })
        }
    </script>

        @else
    <script>
        function create_param(){
            var name_rezhim = document.getElementById('name_rezhim').value
            if (name_rezhim == ''){
                change_header_modal('Укажите наименование режимного листа!')
                document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                open_modal_side_menu()
            }else{
                var arr = new Map()
                arr.set('name_rezhim', name_rezhim)
                $.ajax({
                    url: '/create_new_rezhim',
                    method: 'POST',
                    data: Object.fromEntries(arr),
                    success: function (res) {
                        window.location.href = '/admin_rezhim_lists/'+res
                    }
                })
            }
        }
    </script>
        @endif
    <style>
        .header_blocks{margin: 2px}
        .btn{padding: 2px 10px; margin-left: 20px}
        .btn_img:hover{
            transition: 0s;
            padding: 0px 8px;
        }
        .btn_img:hover img{
            height: 28px;
        }
    </style>
@endsection

