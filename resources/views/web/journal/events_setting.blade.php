@extends('layouts.app')
@section('title')
    Настройка журнала событий
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Настройка журнала событий</p>
        <button onclick="window.location.href = '/journal_events'" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Вернуться к отчету" ><img src="/assets/img/back.svg"></button>
    </div>
    <div style="" id="main_div">
        <div class="two_part">
            <div class="one_four">
                <button class="btn" style="margin: 0 0 0 15px; padding: 0 5px" data-toggle="tooltip" title="Создать запись" onclick="create_service()"><img style="height: 14px; margin-top: 2px" src="/assets/img/add_plus_icon.svg"></button>
                <h4 style="display: inline-block">Список служб</h4>
                <div class="part" id="service_table" >

                </div>
            </div>
            <div class="one_four">
                <button class="btn" style="margin: 0 0 0 15px; padding: 0 5px" data-toggle="tooltip" title="Создать запись" onclick="create_subdivision()"><img style="height: 14px; margin-top: 2px" src="/assets/img/add_plus_icon.svg"></button>
                <h4 style="display: inline-block">Список подразделений</h4>
                <div class="part" id="subdivision_table">

                </div>
            </div>
        </div>
        <div class="two_part">
            <div class="one_four">
                <button class="btn" style="margin: 0 0 0 15px; padding: 0 5px" data-toggle="tooltip" title="Создать запись" onclick="create_types()"><img style="height: 14px; margin-top: 2px" src="/assets/img/add_plus_icon.svg"></button>
                <h4 style="display: inline-block">Типы событий</h4>
                <div class="part" id="type_table">
                </div>
            </div>
            <div class="one_four">
                <button class="btn" style="margin: 0 0 0 15px; padding: 0 5px" data-toggle="tooltip" title="Создать запись" onclick="create_template()"><img style="height: 14px; margin-top: 2px" src="/assets/img/add_plus_icon.svg"></button>
                <h4 style="display: inline-block">Шаблоны описаний</h4>
                <div class="part" id="template_table">

                </div>
            </div>
        </div>






    </div>
    <script>
        $(document).ready(function () {
           get_service()
           get_subdivision()
           get_types()
           get_template()
        })
        var changed = function (instance, cell, x, y, value){
            var id = cell.parentNode.getElementsByTagName('td')[1].textContent
            var arr = new Map()
            arr.set('column', 'service')
            arr.set('value', value)
            arr.set('id', id)
            $.ajax({
                url: '/edit_service',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_service()
                }
            })
        }
        function get_service(){
            $.ajax({
                url: '/get_service',
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    document.getElementById('service_table').innerText = ''
                    var width = $('#service_table').width() - 70
                    jsTable = jspreadsheet(document.getElementById('service_table'), {
                        data:JSON.parse(res),
                        search:true,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#service_table').width()+'px',
                        tableHeight: $('#service_table').height()+'px',
                        rowResize: false,
                        onchange: changed,
                        allowInsertRow:false,
                        columns: [
                            {type:'hidden',name:'id',title:'Номер записи'},
                            {width:width,type:'text',name:'service',title:'Наименование службы',readOnly:false,},
                        ],
                    });
                    jsTable.deleteRow = function(numOfRows) {
                        var number = jsTable.getSelectedRows();
                        var ids = ''
                        for (var i = 0; i<number.length; i++){
                            ids+=number[i].getElementsByTagName('td')[1].textContent+','
                        }
                        var arr = new Map()
                        arr.set('column', 'visible')
                        arr.set('value', 'false')
                        arr.set('id', ids)
                        $.ajax({
                            url: '/edit_service',
                            method: 'POST',
                            data: Object.fromEntries(arr),
                            success: function (res) {
                                get_service()
                            }
                        })
                    }
                }
            })
        }
        function create_service(){
            change_header_modal('Добавление службы')
            $('#text_modal_side_menu').after(`<input class="text-input" id="new_service" type="text" placeholder="Введите наименование службы...">`)
            $('#new_service').keydown(function (event) {
                if (event.which == 13){
                    $('#submit_button_side_menu').click()
                }
            })
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_service()`)
        }
        function store_new_service(){
            var arr = new Map()
            arr.set('value', document.getElementById('new_service').value)
            $.ajax({
                url: '/create_service',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_service()
                    close_modal_side_menu()
                }
            })
        }
        var changed_subdivision = function (instance, cell, x, y, value){
            var id = cell.parentNode.getElementsByTagName('td')[1].textContent
            var arr = new Map()
            arr.set('column', 'subdivision')
            arr.set('value', value)
            arr.set('id', id)
            $.ajax({
                url: '/edit_subdivisions',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_subdivision()
                }
            })
        }
        function get_subdivision(){
            $.ajax({
                url: '/get_subdivisions',
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    document.getElementById('subdivision_table').innerText = ''
                    var width = $('#subdivision_table').width() - 180
                    jsTable_sub = jspreadsheet(document.getElementById('subdivision_table'), {
                        data:JSON.parse(res),
                        search:true,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#subdivision_table').width()+'px',
                        tableHeight: $('#subdivision_table').height()+'px',
                        rowResize: false,
                        onchange: changed_subdivision,
                        allowInsertRow:false,
                        columns: [
                            {type:'hidden',name:'id',title:'Номер записи'},
                            {width:width,type:'text',name:'subdivision',title:'Наименование подразделения',readOnly:false,},
                        ],
                    });
                    jsTable_sub.deleteRow = function(numOfRows) {
                        var number = jsTable_sub.getSelectedRows();
                        var ids = ''
                        for (var i = 0; i<number.length; i++){
                            ids+=number[i].getElementsByTagName('td')[1].textContent+','
                        }
                        var arr = new Map()
                        arr.set('column', 'visible')
                        arr.set('value', 'false')
                        arr.set('id', ids)
                        $.ajax({
                            url: '/edit_subdivisions',
                            method: 'POST',
                            data: Object.fromEntries(arr),
                            success: function (res) {
                                get_subdivision()
                            }
                        })
                    }
                }
            })
        }
        function create_subdivision(){
            change_header_modal('Добавление подразделения')
            $('#text_modal_side_menu').after(`<input class="text-input" id="new_service" type="text" placeholder="Введите наименование подразделения...">`)
            $('#new_service').keydown(function (event) {
                if (event.which == 13){
                    $('#submit_button_side_menu').click()
                }
            })
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_subdivision()`)
        }
        function store_new_subdivision(){
            var arr = new Map()
            arr.set('value', document.getElementById('new_service').value)
            $.ajax({
                url: '/create_subdivision',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_subdivision()
                    close_modal_side_menu()
                }
            })
        }
        var changed_type = function (instance, cell, x, y, value){
            var id = cell.parentNode.getElementsByTagName('td')[1].textContent
            var column = ['event', 'on_graph', 'color']
            var arr = new Map()
            arr.set('column', column[x-1])
            arr.set('value', value)
            arr.set('id', id)
            console.log(arr)
            $.ajax({
                url: '/edit_types',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_types()
                    get_template()
                }
            })
        }
        function get_types(){
            $.ajax({
                url: '/get_types',
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    document.getElementById('type_table').innerText = ''
                    var width = $('#type_table').width() - 230
                    jsTable_type = jspreadsheet(document.getElementById('type_table'), {
                        data:JSON.parse(res),
                        search:true,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#type_table').width()+'px',
                        tableHeight: $('#type_table').height()+'px',
                        rowResize: false,
                        onchange: changed_type,
                        allowInsertRow:false,
                        columns: [
                            {type:'hidden',name:'id',title:'Номер записи'},
                            {width:width,type:'text',name:'event',title:'Тип события',readOnly:false,},
                            {width:80,type:'checkbox',name:'on_graph',title:'На график',readOnly:false,},
                            {width:80,type:'color',name:'color',title:'Цвет',readOnly:false,render:'square',},
                        ],
                    });
                    jsTable_type.deleteRow = function(numOfRows) {
                        var number = jsTable_type.getSelectedRows();
                        var ids = ''
                        for (var i = 0; i<number.length; i++){
                            ids+=number[i].getElementsByTagName('td')[1].textContent+','
                        }
                        var arr = new Map()
                        arr.set('column', 'visible')
                        arr.set('value', 'false')
                        arr.set('id', ids)
                        $.ajax({
                            url: '/edit_types',
                            method: 'POST',
                            data: Object.fromEntries(arr),
                            success: function (res) {
                                get_types()
                                get_template()
                            }
                        })
                    }
                }
            })
        }
        function create_types(){
            change_header_modal('Добавление типа событий')
            $('#text_modal_side_menu').after(`<input class="text-input" id="new_service" type="text" placeholder="Введите тип события...">`)
            $('#new_service').keydown(function (event) {
                if (event.which == 13){
                    $('#submit_button_side_menu').click()
                }
            })
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_types()`)
        }
        function store_new_types(){
            var arr = new Map()
            arr.set('value', document.getElementById('new_service').value)
            $.ajax({
                url: '/create_types',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_types()
                    close_modal_side_menu()
                }
            })
        }

        var changed_template = function (instance, cell, x, y, value){
            var id = cell.parentNode.getElementsByTagName('td')[1].textContent
            var column = ['template', 'event']
            var arr = new Map()
            arr.set('column', column[x-1])
            arr.set('value', value)
            arr.set('id', id)
            $.ajax({
                url: '/edit_templates',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_template()
                }
            })
        }
        function get_template(){
            $.ajax({
                url: '/get_templates/false',
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    res = JSON.parse(res)
                    var dropbox = res['dropbox']
                    delete res['dropbox']
                    res = Object.values(res)
                    document.getElementById('template_table').innerText = ''
                    var width = ($('#template_table').width() - 180)/2
                    jsTable_temp = jspreadsheet(document.getElementById('template_table'), {
                        data:res,
                        search:true,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#template_table').width()+'px',
                        tableHeight: $('#template_table').height()+'px',
                        rowResize: false,
                        onchange: changed_template,
                        allowInsertRow:false,
                        columns: [
                            {type:'hidden',name:'id',title:'Номер записи'},
                            {width:width,type:'text',name:'template',title:'Текст шаблона',readOnly:false,},
                            {width:width,type:'dropdown',name:'event',source:dropbox,title:'Тип события',readOnly:false,},
                        ],
                    });
                    jsTable_temp.deleteRow = function(numOfRows) {
                        var number = jsTable_temp.getSelectedRows();
                        var ids = ''
                        for (var i = 0; i<number.length; i++){
                            ids+=number[i].getElementsByTagName('td')[1].textContent+','
                        }
                        var arr = new Map()
                        arr.set('column', 'visible')
                        arr.set('value', 'false')
                        arr.set('id', ids)
                        $.ajax({
                            url: '/edit_templates',
                            method: 'POST',
                            data: Object.fromEntries(arr),
                            success: function (res) {
                                get_template()
                            }
                        })
                    }
                }
            })
        }
        function create_template(){
            change_header_modal('Добавление шаблона')
            var table = `<table id="table_modal_side_menu" style="width: 100%"><tbody><tr><td>`
            table += `<select class="text-input" style="width: calc(100% - 25px)" id="type_event"><option value="false" disabled selected>Выберите тип события...</option>`
            $.ajax({
                url: '/get_types',
                method: 'GET',
                success: function(res){
                    for (var row of res){
                        table += `<option value="${row['id']}">${row['event']}</option>`
                    }
                    table += `</select>`
                },
                async: false
            })
            table += `<textarea style="width: calc(100% - 50px); margin-top: 5px" class="text-input" id="new_service" type="text" placeholder="Введите текст шаблона..."></textarea>`
            table += `</td></tr></tbody></table>`
            $('#text_modal_side_menu').after(table)


            $('#new_service').keydown(function (event) {
                if (event.which == 13){
                    $('#submit_button_side_menu').click()
                }
            })
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_template()`)
        }
        function store_new_template(){
            var arr = new Map()
            if (document.getElementById('type_event').value === 'false'){
                change_header_modal('Добавление шаблона<br>Не выбран тип события!')
            }else if (document.getElementById('new_service').value === ''){
                change_header_modal('Добавление шаблона<br>Не заполнен шаблон!')
            }else {
                arr.set('type_event', document.getElementById('type_event').value)
                arr.set('template', document.getElementById('new_service').value)
                $.ajax({
                    url: '/create_templates',
                    method: 'POST',
                    data: Object.fromEntries(arr),
                    success: function (res) {
                        get_template()
                        close_modal_side_menu()
                    }
                })
            }
        }
    </script>
    <style>
        h4{
            padding-left: 15px;
            margin: 10px 0;
        }
        #main_div{
            width: 100%; height: calc(100% - 80px);
            /*display: inline-block;*/
        }
        .one_four{
            width: calc(50% - 5px);
            height: 100%;
            display: inline-block;

        }
        .two_part{
            display: inline-block;
            width: 100%;
            height: 50%;
        }
        .two_part .one_four:last-of-type{
            position: fixed;
        }
        .part{
            height: calc(100% - 40px);
            width: 100%;
        }
    </style>
@endsection

