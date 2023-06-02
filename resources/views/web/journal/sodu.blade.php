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
                    jsTable = jspreadsheet(document.getElementById('main_div'), {
                        data:JSON.parse(res),
                        search:true,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#main_div').width()+'px',
                        tableHeight: $('#main_div').height()+'px',
                        rowResize: false,
                        onchange: changed,
                        allowInsertRow:false,
                        columns: [
                            {width:'150px',type:'dropdown',name:'fio',title:'Пользователь',source: ['Бадиков Н.А.','Кирдянов Д.А.','Коваленко А.А.','Козлов А.А.','Парфенов Н.В.','Черкасов В.Н.','Прочее...']},
                            {width:width,type:'text',name:'event',title:'Событие'},
                            {width:'300px',type:'dropdown',name:'type_event',title:'Тип события',source: ['Аварийная ситуация','Ввод в эксплуатацию','Вынужденный останов','Для информации','Изменение режима','Лесные пожары','Метеопредупреждение','Остановка и запуск скважин','Отгрузка ЖУВ','Плановый (внеплановый) остановочный комплекс','Происшествия','Профилактические работы','Распоряжения ПДС','Ремонтные работы','Телефонограммы','Учебные тренировки','Технологические переключения','Прием смены','Перекачка СК на ТОК','Прием СК на ТОК','Перекачка СК (циркуляция)','Отклонение от технологического режима','ДТП','Прочее...']},
                            {width:'250px',type:'dropdown',name:'otdel',title:'Структурное подразделение (филиал,цех)',source: ['КГПУ, 1','КГПУ, 2','КГПУ, 3','КГПУ, 45','УМТСиК, ТОК','ПДС Администрации Общества','"п/б Нючакан, ГПУ"','Администрация общества','ПДС КГПУ','Автодорога','Прочее...']},
                            {width:'150px',type:'calendar',name:'date',title:'Дата', options: { format:'DD.MM.YYYY HH:mm'},readOnly:true,},
                            {type:'hidden',name:'id'},
                        ],
                        csvFileName: 'Журнал_СОДУ'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download()
                    })
                    $('.jexcel_column_filter').on('click', function (){
                        document.getElementById('search_row').value = ''
                    })
                    jsTable.deleteRow = function(numOfRows) {
                        var number = jsTable.getSelectedRows();
                        var ids = ''
                        for (var i = 0; i<number.length; i++){
                            ids+=number[i].lastElementChild.textContent+','
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
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `delete_sodu(${id})`)
        }
        function delete_sodu(ids){
            $.ajax({
                url: '/delete_sodu/'+ids,
                method: 'get',
                success: function (res) {
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
            var table = `
            <table id="table_modal_side_menu" class="table_modal">
                <tbody>
                    <tr>
                        <td style="width: 250px">Дата события</td>
                        <td><input type="text" id="date" placeholder="Дата события..." class="datepicker-here" style="width: calc(100% - 50px)"/></td>
                    </tr>
                    <tr>
                        <td>Пользователь</td>
                        <td>
                            <select class="text-input" style="width: calc(100% - 25px)" id="fio">
                                <option>Бадиков Н.А.</option>
                                <option>Кирдянов Д.А.</option>
                                <option>Коваленко А.А.</option>
                                <option>Козлов А.А.</option>
                                <option>Парфенов Н.В.</option>
                                <option>Черкасов В.Н.</option>
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
                            <select class="text-input" style="width: calc(100% - 25px)" id="type_event">
                                <option>Аварийная ситуация</option>
                                <option>Ввод в эксплуатацию</option>
                                <option>Вынужденный останов</option>
                                <option>Для информации</option>
                                <option>ДТП</option>
                                <option>Изменение режима</option>
                                <option>Лесные пожары</option>
                                <option>Метеопредупреждение</option>
                                <option>Остановка и запуск скважин</option>
                                <option>Отгрузка ЖУВ</option>
                                <option>Отклонение от технологического режима</option>
                                <option>Плановый (внеплановый) остановочный комплекс</option>
                                <option>Происшествия</option>
                                <option>Профилактические работы</option>
                                <option>Прием смены</option>
                                <option>Перекачка СК на ТОК</option>
                                <option>Прием СК на ТОК</option>
                                <option>Перекачка СК (циркуляция)</option>
                                <option>Распоряжения ПДС</option>
                                <option>Ремонтные работы</option>
                                <option>Телефонограммы</option>
                                <option>Технологические переключения</option>
                                <option>Учебные тренировки</option>
                                <option>Прочее...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Структурное подразделение</td>
                        <td>
                            <select class="text-input" style="width: calc(100% - 25px)" id="otdel">
                                <option>Администрация общества</option>
                                <option>Автодорога</option>
                                <option>КГПУ, 1</option>
                                <option>КГПУ, 2</option>
                                <option>КГПУ, 3</option>
                                <option>КГПУ, 45</option>
                                <option>УМТСиК, ТОК</option>
                                <option>ПДС Администрации Общества</option>
                                <option>ПДС КГПУ</option>
                                <option>"п/б Нючакан, ГПУ"</option>
                                <option>Прочее...</option>
                            </select>
                        </td>
                    </tr>`
            $('#text_modal_side_menu').after(table)
            ////Для выбора времени
            var today = new Date();
            new AirDatepicker('#date',
                {
                    timepicker: true,
                    autoClose: false,
                    maxDate: today,
                    maxHours: 23,
                    maxMinutes:59,
                    keyboardNav: true
                })
            //обозначаем селект
            // select_initial()
            open_modal_side_menu()
            document.getElementById('content_modal_side_menu').style.width = '80%'
            document.getElementById('content_modal_side_menu').style.marginLeft = '-40%'
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

