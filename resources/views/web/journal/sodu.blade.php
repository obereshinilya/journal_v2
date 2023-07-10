@extends('layouts.app')
@section('title')
    Журнал СОДУ
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Журнал СОДУ</p>
        <button id="pdf_btn" class="btn header_blocks btn_img" data-toggle="tooltip" title="Загрузить PDF" ><img onclick="printTable()" src="/assets/img/pdf.svg"></button>
        <button id="download_csw" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Загрузить CSV" ><img src="/assets/img/excel.svg"></button>
        <button onclick="create_sodu()" class="btn header_blocks btn_img" data-toggle="tooltip" title="Создать запись"><img src="/assets/img/add_plus_icon.svg"></button>
        <input class="input header_blocks" style="width: 200px" oninput="seach_jsExcel()" type="text" id="search_row" placeholder="Поиск...">
        @include('include.period_date_time')
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px)" id="main_div">

    </div>
    <script>
        $(document).ready(function () {
            get_table_data()
        })
        var changed = function (instance, cell, x, y, value){
            var column = ['fio', 'event', 'type_event', 'otdel']
            var id = cell.parentNode.lastElementChild.textContent
            var arr = new Map()
            arr.set('column', column[x])
            arr.set('value', value)
            arr.set('id', id)
            $.ajax({
                url: '/edit_sodu',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {

                }
            })
        }
        function printTable(){
            var date_str = $("#period").val().replace(/ /g,'')
            date_str = date_str.split('-')
            var start = date_str[0]
            var stop = date_str[1]
            var new_html = document.getElementById('main_div').innerHTML
            document.body.innerText = ''
            document.body.innerHTML = `<h4 style="width:100%; text-align:center">Журнал СОДУ с ${start} по ${stop}</h4>`
            // document.body.innerHTML += '<style>.statickTable th{border: 1px solid #ddd;}</style>'
            document.body.innerHTML += new_html
            window.print()
            $('body').on('click', function (){
                window.location.reload()
            })
        }
        function get_table_data(){
            document.getElementById('search_row').value = ''
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-920)/1 + 'px'
            var date_str = $("#period").val().replace(/ /g,'')
            date_str = date_str.split('-')
            $.ajax({
                url: '/journal_sodu_data/'+date_str[0]+'/'+date_str[1],
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    res = JSON.parse(res)
                    var type_event = res['type_event']
                    type_event[type_event.length] = 'Прочее..'
                    delete res['type_event']
                    var otdel = res['otdel']
                    otdel[otdel.length] = 'Прочее..'
                    delete res['otdel']
                    res = Object.values(res)
                    jsTable = jspreadsheet(document.getElementById('main_div'), {
                        data:res,
                        search:true,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#main_div').width()+'px',
                        tableHeight: $('#main_div').height()+'px',
                        rowResize: false,
                        onchange: changed,
                        allowInsertRow:false,
                        columns: [
                            {width:'150px',type:'dropdown',name:'fio',title:'Пользователь',source: ['Иванов И.И.','Сидоров С.С.','Петров П.П.','Прочее...']},
                            {width:width,type:'text',name:'event',title:'Событие'},
                            {width:'300px',type:'dropdown',name:'type_event',title:'Тип события',source: type_event},
                            {width:'250px',type:'dropdown',name:'otdel',title:'Структурное подразделение',source: otdel},
                            {width:'150px',type:'calendar',name:'date',title:'Дата', options: { format:'DD.MM.YYYY HH:mm'},readOnly:true,},
                            {type:'hidden',name:'id',title:'Номер записи'},
                        ],
                        csvFileName: 'Журнал_СОДУ'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download(true)
                    })
                    $('.jexcel_column_filter').on('click', function (){
                        document.getElementById('search_row').value = ''
                    })
                    jsTable.deleteRow = function(numOfRows) {
                        var number = jsTable.getSelectedRows();
                        var ids = ''
                        for (var i = 0; i<number.length; i++){
                            ids+=number[i].getElementsByTagName('td')[6].textContent+','
                        }
                        confirm_delete_sodu(ids)
                    }
                }
            })
        }
        function seach_jsExcel(){
            var input = document.getElementById('search_row')
            jsTable.search(input.value);
        }
        function confirm_delete_sodu(id){
            change_header_modal('Удалить запись?<br>Будет произведена запись в журнал!')
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `delete_sodu('${id}')`)
        }
        function delete_sodu(ids){
            var arr = new Map()
            arr.set('id', ids)
            $.ajax({
                url: '/delete_sodu',
                method: 'post',
                data: Object.fromEntries(arr),
                success: function (res) {
                    console.log(res)
                    get_table_data()
                    close_modal_side_menu()
                }
            })
        }
        function create_sodu(){
            change_header_modal('Добавление записи в журнал СОДУ')
            create_table_in_window()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_record()`)
        }
        function create_table_in_window(){
            var table = `<table id="table_modal_side_menu" class="table_modal">
                <tbody>
                    <tr>
                        <td style="width: 250px">Дата события</td>
                        <td><input type="text" id="date" placeholder="Дата события..." class="datepicker-here" style="width: calc(100% - 50px)"/></td>
                    </tr>
                    <tr>
                        <td>Пользователь</td>
                        <td>
                            <select class="text-input" style="width: calc(100% - 25px)" id="fio">
                                <option>Иванов И.И.</option>
                                <option>Сидоров С.С.</option>
                                <option>Петров П.П.</option>
                                <option>Прочее...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Событие</td>
                        <td>
                            <textarea class="text-input" id="event" style="width: calc(100% - 50px)" placeholder="Описание..."></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Тип события</td>
                        <td>
                            <select class="text-input" style="width: calc(100% - 25px)" id="type_event" onchange="change_select(this)">`
            $.ajax({
                url: '/get_dropbox_sodu_data',
                method: 'get',
                success: function (res) {
                    table += `<option disabled selected>Выберите тип события</option>`
                    for(var event of res['type_event']){
                        table += `<option>${event}</option>`
                    }
                    table += `<option>Прочее...</option>`
                    table += `</select>
                        </td>
                    </tr>
                    <tr>
                        <td>Структурное подразделение</td>
                        <td>
                            <select class="text-input" style="width: calc(100% - 25px)" id="otdel" onchange="change_select(this)">`
                    table += `<option disabled selected>Выберите подразделение</option>`
                    for(var otdel of res['otdel']){
                        table += `<option>${otdel}</option>`
                    }
                    table += `<option>Прочее...</option>`
                         table += `</select>
                        </td>
                    </tr>`
                }, async: false
            })

            $('#text_modal_side_menu').after(table)
            ////Для выбора времени
            var today = new Date();
            new AirDatepicker('#date',
                {
                    timepicker: true,
                    autoClose: false,
                    // startDate: new Date(),
                    // maxDate: today,
                    maxHours: 23,
                    maxMinutes:59,
                    keyboardNav: true
                })
            var D = new Date()
            document.getElementById('date').value = ('0' + D.getDate()).slice(-2) + '.' + ('0' + (D.getMonth() + 1)).slice(-2) + '.' + D.getFullYear() + ' '+ D.getHours()+':'+D.getMinutes()
            //обозначаем селект
            // select_initial()
            open_modal_side_menu()
            document.getElementById('content_modal_side_menu').style.width = '80%'
            document.getElementById('content_modal_side_menu').style.marginLeft = '-40%'
        }
        function change_select(select){
            if (select.value === 'Прочее...'){
                var id = select.id
                var parent = select.parentNode
                parent.removeChild(select)
                parent.innerHTML = `<input type="text" id="${id}" placeholder="Введите значение..." style="width: calc(100% - 50px)"/>`
            }
        }
        function store_new_record(){
            var arr = new Map()
            arr.set('date', document.getElementById('date').value)
            arr.set('fio', document.getElementById('fio').value)
            arr.set('event', document.getElementById('event').value)
            arr.set('type_event', document.getElementById('type_event').value)
            arr.set('otdel', document.getElementById('otdel').value)
            $.ajax({
                url: '/create_sodu',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    close_modal_side_menu()
                    get_table_data()
                },
                async: false
            })

        }
    </script>
@endsection

