@extends('layouts.app')
@section('title')
    Создание нового списка
@endsection
@section('side_menu')
    @include('include.side_menu')
@endsection
@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Выбор параметров. </p>
        <button onclick="save_list()" class="btn header_blocks btn_img" data-toggle="tooltip" title="Сохранить список"><img src="/assets/img/save.svg"></button>
        <button onclick="delete_list()" class="btn header_blocks btn_img"><img src="/assets/img/trash.svg"></button>
        @include('include.search_row')
        <input data-toggle="tooltip" class="header_blocks" title="Укажите название списка..." type="text" id="name_list" style="" placeholder="Укажите название списка...">
    </div>

    <div id="statickTableDiv"  class="statickTableDiv" style="width: 100%; height: calc(100% - 75px); direction: ltr">
        <table id="statickTable" class="statickTable" style="width: 100%; direction: ltr; table-layout: auto; white-space: normal">

            <thead style="position: sticky; top: 0; z-index: 2">
            <tr>
                <th>Наименование</th>
                <th>Ед.изм</th>
                <th>Порядок отображения</th>
                <th>Дабавить в список</th>
            </tr>
            </thead>
            <tbody>
            @for($i=0; $i<count($data); $i++)
                    <tr data-id="{{$data[$i]['id']}}">
                        <td>{{$data[$i]['full_name']}}</td>
                        <td>{{$data[$i]['e_unit']}}</td>
                        <td data-id="{{$data[$i]['id']}}"></td>
                        <td style="text-align: center">
                            <label class="switch">
                                <input data-id="{{$data[$i]['id']}}" id="param_{{$data[$i]['id']}}" type="checkbox" onclick="param_in_list(this)">
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
            @endfor
            </tbody>
        </table>
    </div>
    <style>
        .statickTable tbody tr td{
            max-width: 500px;
            overflow: hidden;
            text-overflow: inherit;
            white-space: normal;
        }
        .statickTable tbody tr:hover{
            font-weight: bolder;
        }
        .statickTable tbody tr:hover svg, .statickTable tbody tr:hover img{
            opacity: 1;
        }

    </style>
    <script>
        @if($param_from_list == 'false')
            $(document).ready(function () {
                side_menu_obj_click()
            })
        function delete_list(){
                window.location.href = '/'
        }
            function param_in_list(input){
                var td = input.parentNode.parentNode.parentNode.getElementsByTagName('td')[2]
                if(td.textContent){
                    var num_param = td.textContent
                    td.textContent = ''
                    td.classList.remove('choiced_param')
                    for(var one_td of document.getElementsByClassName('choiced_param')){
                        if(Number(one_td.textContent) > num_param){
                            one_td.textContent = Number(one_td.textContent) - 1
                        }
                    }
                }else {
                    td.textContent = document.getElementsByClassName('choiced_param').length + 1
                    td.classList.add('choiced_param')
                }
            }
            function save_list(){
                var name_list = document.getElementById('name_list')
                if(name_list.value){
                    var choiced_params = document.getElementsByClassName('choiced_param')
                    var result = []
                    for(var one_param of choiced_params){
                        result[one_param.textContent - 1] = one_param.getAttribute('data-id')
                    }
                    var arr = new Map()
                    arr.set('name_list', name_list.value)
                    arr.set('param_ids', result)
                    $.ajax({
                        url: '/save_list',
                        method: 'POST',
                        data: Object.fromEntries(arr),
                        success: function (res) {
                            if(res === 'false'){
                                change_header_modal('Список с таким именем уже существует!')
                                open_modal_side_menu()
                                document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                            }else{
                                window.location.href = '/'
                            }
                        }
                    })
                }else {
                    change_header_modal('Не указано название списка!')
                    open_modal_side_menu()
                    document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                }
            }
        @else
        $(document).ready(function () {
            side_menu_obj_click()
            mark_checked_param()
        })
        function mark_checked_param(){
            document.getElementById('name_list').value = "{{$param_from_list->name_list}}"
            var params_id = "{{$param_from_list->param_id}}"
            params_id = params_id.split(',')
            for (var param_id of params_id){
                try {
                    document.getElementById('param_'+param_id).click()
                }catch (e) {

                }
            }

        }
        function param_in_list(input){
            var td = input.parentNode.parentNode.parentNode.getElementsByTagName('td')[2]
            if(td.textContent){
                var num_param = td.textContent
                td.textContent = ''
                td.classList.remove('choiced_param')
                for(var one_td of document.getElementsByClassName('choiced_param')){
                    if(Number(one_td.textContent) > num_param){
                        one_td.textContent = Number(one_td.textContent) - 1
                    }
                }
            }else {
                td.textContent = document.getElementsByClassName('choiced_param').length + 1
                td.classList.add('choiced_param')
            }
        }
        function save_list(){
            var name_list = document.getElementById('name_list')
            if(name_list.value){
                var choiced_params = document.getElementsByClassName('choiced_param')
                var result = []
                for(var one_param of choiced_params){
                    result[one_param.textContent - 1] = one_param.getAttribute('data-id')
                }
                var arr = new Map()
                arr.set('name_list', name_list.value)
                arr.set('param_ids', result)
                $.ajax({
                    url: '/update_list/{{$param_from_list->id}}',
                    method: 'POST',
                    data: Object.fromEntries(arr),
                    success: function (res) {
                        if(res === 'false'){
                            change_header_modal('Список с таким именем уже существует!')
                            open_modal_side_menu()
                            document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                        }else{
                            window.location.href = '/'
                        }
                    }
                })
            }else {
                change_header_modal('Не указано название списка!')
                open_modal_side_menu()
                document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
            }
        }
        function delete_list(){
            change_header_modal('Удалить список для всех пользователей?')
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `confirm_delete_list()`)
        }
        function confirm_delete_list(){
            $.ajax({
                url: '/delete_list/{{$param_from_list->id}}',
                method: 'GET',
                success: function(res){
                    window.location.href = '/'
                },
                async: false
            })
        }
        @endif

    </script>



@endsection
