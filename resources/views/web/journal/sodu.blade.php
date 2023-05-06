@extends('layouts.app')
@section('title')
    Отчеты
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Журнал действий оператора</p>
        <button id="download_csw" class="btn header_blocks btn_img"><img src="/assets/img/excel.svg"></button>
        @include('include.period_date_time')
        <input class="input header_blocks" style="width: 200px" oninput="seach_jsExcel()" type="text" id="search_row" placeholder="Поиск...">
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px)" id="main_div">

    </div>
    <script>
        $(document).ready(function () {
            get_table_data()
        })
        var deletedRow = function(instance) {
            console.log('Row deleted');
            console.log(instance);
        }
        function get_table_data(){
            document.getElementById('search_row').value = ''
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-270)/4 + 'px'
            var date_str = $("#period").val().replace(/ /g,'')
            date_str = date_str.split('-')
            $.ajax({
                url: '/journal_sodu_data/'+date_str[0]+'/'+date_str[1],
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function(res) {
                    console.log(JSON.parse(res))
                    jsTable = jspreadsheet(document.getElementById('main_div'), {
                        data:JSON.parse(res),
                        search:true,
                        tableOverflow: true,
                        filters: true,
                        tableWidth: $('#main_div').width()+'px',
                        tableHeight: $('#main_div').height()+'px',
                        rowResize: false,
                        ondeleterow: deletedRow,
                        columns: [
                            {width:width,type:'dropdown',name:'fio',title:'Пользователь',source: ['Бадиков Н.А.','Кирдянов Д.А.','Коваленко А.А.','Козлов А.А.','Парфенов Н.В.','Черкасов В.Н.','Прочее...']},
                            {width:width,type:'text',name:'event',title:'Событие'},
                            {width:width,type:'dropdown',name:'type_event',title:'Тип события',source: ['Аварийная ситуация','Ввод в эксплуатацию','Вынужденный останов','Для информации','Изменение режима','Лесные пожары','Метеопредупреждение','Остановка и запуск скважин','Отгрузка ЖУВ','Плановый (внеплановый) остановочный комплекс','Происшествия','Профилактические работы','Распоряжения ПДС','Ремонтные работы','Телефонограммы','Учебные тренировки','Технологические переключения','Прием смены','Перекачка СК на ТОК','Прием СК на ТОК','Перекачка СК (циркуляция)','Отклонение от технологического режима','ДТП','Прочее...']},
                            {width:width,type:'dropdown',name:'otdel',title:'Структурное подразделение (филиал,цех)',source: ['КГПУ, 1','КГПУ, 2','КГПУ, 3','КГПУ, 45','УМТСиК, ТОК','ПДС Администрации Общества','"п/б Нючакан, ГПУ"','Администрация общества','ПДС КГПУ','Автодорога','Прочее...']},
                            {width:'200px',type:'calendar',name:'date',title:'Дата', options: { format:'DD.MM.YYYY HH:mm'},readOnly:true,},
                        ],
                        csvFileName: 'Журнал_СОДУ'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download()
                    })
                    $('.jexcel_column_filter').on('click', function (){
                        document.getElementById('search_row').value = ''
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

