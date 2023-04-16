@extends('layouts.app')
@section('title')
    Редактирование сигналов ОЖД
@endsection
@section('side_menu')
    @include('include.side_menu')
@endsection
@section('content')
    <p id="selected_td" style="display: none">{{$id_param}}</p>
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Редактирование параметров. </p>
        @include('include.search_row')
    </div>

    <div id="statickTableDiv"  class="statickTableDiv" style="width: 100%; height: calc(100% - 75px); direction: ltr">
        <table id="statickTable" class="statickTable" style="width: 100%; direction: ltr; table-layout: auto; white-space: normal">

            <thead style="position: sticky; top: 0; z-index: 2">
            <tr>
                <th rowspan="2">Наименование</th>
                <th rowspan="2">Ед.изм</th>
                <th rowspan="2">Имя тега</th>
                <th rowspan="2">Отображение в ОЖД</th>
                <th style="padding: 5px 1px" colspan="3">М АСДУ ЕСГ</th>
            </tr>
            <tr style="position:sticky">
                <th style="padding: 5px 1px">РВ</th>
                <th style="padding: 5px 1px">2 часа</th>
                <th style="padding: 5px 1px">Сутки</th>
            </tr>
            </thead>
            <tbody>
            @for($i=0; $i<count($data); $i++)
                    <tr data-id="{{$data[$i]['id']}}">
                        <td class="full_name" contenteditable="true">{{$data[$i]['full_name']}}</td>
                        <td class="e_unit" contenteditable="true">{{$data[$i]['e_unit']}}</td>
                        <td class="tag_name" contenteditable="true">{{$data[$i]['tag_name']}}</td>
                        <td style="text-align: center"><label class="switch"><input onclick="visible_param({{$data[$i]['id']}})" type="checkbox" @if ($data[$i]['hour_param']) checked @endif><span class="slider"></span></label></td>
                        @if ($data[$i]['guid_masdu_min'])
                            <td style="text-align: center">
                                <label class="switch">
                                    <input id="input_min_{{$data[$i]['id']}}" data-column="guid_masdu_min" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_min']}}" data-name="{{$data[$i]['full_name']}}" type="checkbox" checked onclick="delete_guid(this)">
                                    <span class="slider"></span>
                                </label>
                                <img id="guid_min_{{$data[$i]['id']}}" data-column="guid_masdu_min" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_min']}}" data-name="{{$data[$i]['full_name']}}" onclick="open_edit_guid(this)" class="hover_img" src="/assets/img/edit.svg">
                            </td>
                        @else
                            <td style="text-align: center">
                                <label class="switch">
                                    <input id="input_min_{{$data[$i]['id']}}" data-column="guid_masdu_min" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_min']}}" data-name="{{$data[$i]['full_name']}}" type="checkbox" onclick="create_guid(this)">
                                    <span class="slider"></span>
                                </label>
                            </td>
                        @endif
                        @if ($data[$i]['guid_masdu_hour'])
                            <td style="text-align: center">
                                <label class="switch">
                                    <input id="input_hour_{{$data[$i]['id']}}" data-column="guid_masdu_hour" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_hour']}}" data-name="{{$data[$i]['full_name']}}" type="checkbox" checked onclick="delete_guid(this)">
                                    <span class="slider"></span>
                                </label>
                                <img id="guid_hour_{{$data[$i]['id']}}" data-column="guid_masdu_hour" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_hour']}}" data-name="{{$data[$i]['full_name']}}" onclick="open_edit_guid(this)" class="hover_img" src="/assets/img/edit.svg">
                            </td>
                        @else
                            <td style="text-align: center">
                                <label class="switch">
                                    <input id="input_hour_{{$data[$i]['id']}}" data-column="guid_masdu_hour" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_hour']}}" data-name="{{$data[$i]['full_name']}}" type="checkbox" onclick="create_guid(this)">
                                    <span class="slider"></span>
                                </label>
                            </td>
                        @endif
                        @if ($data[$i]['guid_masdu_day'])
                            <td style="text-align: center">
                                <label class="switch">
                                    <input id="input_day_{{$data[$i]['id']}}" data-column="guid_masdu_day" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_day']}}" data-name="{{$data[$i]['full_name']}}" type="checkbox" checked onclick="delete_guid(this)">
                                    <span class="slider"></span>
                                </label>
                                <img id="guid_day_{{$data[$i]['id']}}" data-column="guid_masdu_day" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_day']}}" data-name="{{$data[$i]['full_name']}}" onclick="open_edit_guid(this)" class="hover_img" src="/assets/img/edit.svg">
                            </td>
                        @else
                            <td style="text-align: center">
                                <label class="switch">
                                    <input id="input_day_{{$data[$i]['id']}}" data-column="guid_masdu_day" data-id="{{$data[$i]['id']}}" data-guid="{{$data[$i]['guid_masdu_day']}}" data-name="{{$data[$i]['full_name']}}" type="checkbox" onclick="create_guid(this)">
                                    <span class="slider"></span>
                                </label>
                            </td>
                        @endif
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
    </style>
    <script>
        $(document).ready(function () {
            $("#statickTable td").keypress(function (code_btn){
                if (code_btn.which === 13){
                    code_btn.preventDefault();
                    this.blur()
                }
            })
            $("#statickTable td").blur(function (){
                var id = this.parentNode.getAttribute('data-id')
                var name_param = this.classList[0]
                var new_value = this.textContent
                save_param(id, name_param, new_value)
            })
            side_menu_obj_click()
            if (document.getElementById('selected_td').textContent !== 'false'){
                $(`tr[data-id=${document.getElementById('selected_td').textContent}]`)[0].getElementsByTagName('td')[0].focus()
            }
        })
        function save_param(id, name_param, new_value){
            $.ajax({
                url: '/save_signal_settings/'+id+'/'+name_param+'/'+new_value,
                method: 'GET',
                async: true,
                success: function (res) {

                }
            })
        }
        function visible_param(id){
            $.ajax({
                url: '/visible_param/'+id,
                method: 'GET',
                async: true,
                success: function (res) {

                }
            })
        }
        function open_edit_guid(input){
            change_header_modal('Редактирование идентификатора для параметра: <br> "'+input.getAttribute('data-name')+'"')
            $('#text_modal_side_menu').after(`<input class="text-input" id="masdu_data" type="text" placeholder="Введите идентификатор..." value="${input.getAttribute('data-guid')}">`)
            $('#masdu_data').keydown(function (event) {
                if (event.which == 13){
                    $('#submit_button_side_menu').click()
                }
            })
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `save_new_guid('${input.id}')`)
        }
        function save_new_guid(input_id){
            if (document.getElementById('masdu_data').value !== ''){
                var input = document.getElementById(input_id)
                save_param(input.getAttribute('data-id'), input.getAttribute('data-column'), document.getElementById('masdu_data').value)
                input.setAttribute('data-guid', document.getElementById('masdu_data').value)
                close_modal_side_menu()
            }else {
                change_header_modal('Идентификатор не может быть пустым!')
            }
        }
        function delete_guid(input){
            if (input.getAttribute('data-guid') !== ''){
                change_header_modal(`Удалить идентификатор параметра: <br>"${input.getAttribute('data-name')}" ?`)
                open_modal_side_menu()
                document.getElementById('submit_button_side_menu').setAttribute('onclick', `confirm_delete('${input.id}')`)
                var td = input.parentNode.parentNode
                var old = td.innerHTML
                td.innerText = ''
                td.innerHTML = old
            }else {
                create_guid(input)
            }
        }
        function confirm_delete(id){
            var input = document.getElementById(id)
            save_param(input.getAttribute('data-id'), input.getAttribute('data-column'), 'false')
            input.setAttribute('data-guid', '')
            input.removeAttribute('checked')
            var td = document.getElementById(id.replace('input', 'guid')).parentNode
            td.removeChild(td.getElementsByTagName('img')[0])
            close_modal_side_menu()
        }
        function create_guid(input){
            if (input.getAttribute('data-guid') === ''){
                change_header_modal(`Добавление идентификатора параметра: <br>"${input.getAttribute('data-name')}"`)
                $('#text_modal_side_menu').after(`<input class="text-input" id="masdu_data" type="text" placeholder="Введите идентификатор...">`)
                $('#masdu_data').keydown(function (event) {
                    if (event.which == 13){
                        $('#submit_button_side_menu').click()
                    }
                })
                open_modal_side_menu()
                var td = input.parentNode.parentNode
                var old = td.innerHTML
                td.innerText = ''
                td.innerHTML = old
                document.getElementById('submit_button_side_menu').setAttribute('onclick', `create_new_guid('${input.id}')`)
            }else {
                delete_guid(input)
            }
        }
        function create_new_guid(id){
            var input = document.getElementById(id)
            var td =input.parentNode.parentNode
            var data_id = input.getAttribute('data-id')
            var data_name = input.getAttribute('data-name')
            input.setAttribute('data-guid', document.getElementById('masdu_data').value)
            input.setAttribute('checked', 'true')
            if (input.id.split('input_min')[1]){
                var data_column = 'guid_masdu_min'
                var new_id = 'guid_min_'
            }else if(input.id.split('input_hour')[1]){
                var data_column = 'guid_masdu_hour'
                var new_id = 'guid_hour_'
            }else {
                var data_column = 'guid_masdu_day'
                var new_id = 'guid_day_'
            }
            td.innerHTML += `<img id="${new_id}${data_id}" data-column="${data_column}" data-id="${data_id}" data-guid="${document.getElementById('masdu_data').value}" data-name="${data_name}" onclick="open_edit_guid(this)" class="hover_img" src="/assets/img/edit.svg">`
            save_param(data_id, data_column, document.getElementById('masdu_data').value)
            close_modal_side_menu()
        }

    </script>



@endsection
