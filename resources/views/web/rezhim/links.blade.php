@extends('layouts.app')
@section('title')
    Выбор источника
@endsection
@section('side_menu')
    @include('include.side_menu')
@endsection
@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Выбор источника. </p>
        @include('include.search_row')
    </div>

    <div id="statickTableDiv"  class="statickTableDiv" style="width: 100%; height: calc(100% - 75px); direction: ltr">
        <table id="statickTable" class="statickTable" style="width: 100%; direction: ltr; table-layout: auto; white-space: normal">

            <thead style="position: sticky; top: 0; z-index: 2">
            <tr>
                <th>Наименование</th>
                <th>Ед.изм</th>
                <th style="width: 100px"></th>
            </tr>
            </thead>
            <tbody>
            @for($i=0; $i<count($data); $i++)
                    <tr data-id="{{$data[$i]['id']}}">
                        <td class="full_name">{{$data[$i]['full_name']}}</td>
                        <td class="e_unit">{{$data[$i]['e_unit']}}</td>
                        <td style="text-align: center">
                            <svg class="hover_img" data-id="{{$data[$i]['id']}}" data-name="{{$data[$i]['full_name']}}" onclick="check_param(this)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M5 21h14a2 2 0 0 0 2-2V8a1 1 0 0 0-.29-.71l-4-4A1 1 0 0 0 16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2zm10-2H9v-5h6zM13 7h-2V5h2zM5 5h2v4h8V5h.59L19 8.41V19h-2v-5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v5H5z"></path></svg>
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
        $(document).ready(function () {
            side_menu_obj_click()
        })

        function check_param(img){
            change_header_modal(`Выбрать сигнал<br>"${img.getAttribute('data-name')}"<br>в качестве источника?`)
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `confirm_select(${img.getAttribute('data-id')})`)
        }

        function confirm_select(id){
            $.ajax({
                url: '/save_select_param/{{$id_param}}/'+id,
                method: 'GET',
                async: true,
                success: function (res) {
                    window.location.href = '/admin_rezhim_lists/{{$id_rezhim}}'
                }
            })
        }


    </script>



@endsection
