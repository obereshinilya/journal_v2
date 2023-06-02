@extends('layouts.app')
@section('title')
    Журнал событий
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Журнал событий</p>
        <button id="download_csw" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Загрузить CSV" ><img src="/assets/img/excel.svg"></button>
        <button onclick="window.location.href = '/setting_journal_events'" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Настройки" ><img src="/assets/img/setting.svg"></button>
        <button onclick="create_record()" class="btn header_blocks btn_img" data-toggle="tooltip" title="Создать запись"><img src="/assets/img/add_plus_icon.svg"></button>
        <input class="input header_blocks" style="width: 200px" oninput="seach_jsExcel()" type="text" id="search_row" placeholder="Поиск...">
        @include('include.period_date_time')
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px)" id="main_div">

    </div>
    <script>
        $(document).ready(function () {
           get_table_data()
        })
        function edit_record(id){
            if (id){
                change_header_modal('Редактирование записи в журнале событий')
                create_table_in_window()
                $.ajax({
                    url: '/get_record_event/'+id,
                    method: 'GET',
                    success: function(res){
                        document.getElementById('timestamp').value = res['timestamp']
                        document.getElementById('ingener').value = res['ingener']
                        document.getElementById('subdivision').value = res['subdivision_id']
                        document.getElementById('service').value = res['service_id']
                        document.getElementById('type_id').value = res['type_id']
                        get_comment()
                        document.getElementById('description').value = res['description']
                        var lastRow = $('#table_modal_side_menu tbody tr:last-child')
                        var newRow = ``
                        if (res['accept']){
                            newRow = `<tr><td colspan="2">Принято: ${res['display_name']} в ${res['time_accept']}</td></tr>`
                            document.getElementById('timestamp').setAttribute('disabled', true)
                            document.getElementById('ingener').setAttribute('disabled', true)
                            document.getElementById('subdivision').setAttribute('disabled', true)
                            document.getElementById('service').setAttribute('disabled', true)
                            document.getElementById('type_id').setAttribute('disabled', true)
                            document.getElementById('description').setAttribute('disabled', true)
                            document.getElementById('templates').setAttribute('disabled', true)
                            document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                        }else {
                            newRow = `<tr><td>Принять:</td><td style="text-align: left"><input style="width: 50px" type="checkbox" id="accept"></td></tr>`
                            document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_record(${id})`)
                        }
                        lastRow.after(newRow)
                    },
                    async: false
                })
            }
        }
        function create_record(){
            change_header_modal('Добавление записи в журнал событий')
            create_table_in_window()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_record()`)
        }
        function create_table_in_window(){
            var table = `
            <table id="table_modal_side_menu" class="table_modal">
                <tbody>
                    <tr>
                        <td style="width: 250px">Дата события</td>
                        <td><input type="text" id="timestamp" placeholder="Дата события..." class="datepicker-here" style="width: calc(100% - 50px)"/></td>
                    </tr>
                    <tr>
                        <td>Инженер</td>
                        <td><input class="input" id="ingener" type="text" style="width: calc(100% - 50px)" placeholder="Инженер..."></td>
                    </tr>
                    <tr>
                        <td>Подразделение</td>
                        <td><select class="text-input" style="width: calc(100% - 25px)" id="subdivision"><option value="false" disabled selected>Выберите подразделение...</option>`
            $.ajax({
                url: '/get_subdivisions',
                method: 'GET',
                success: function(res){
                    for (var sub of res){
                        table += `<option value="${sub['id']}">${sub['subdivision']}</option>`
                    }
                    table += `</select></td></tr>`
                },
                async: false
            })
            table += `<tr><td>Служба</td><td><select class="text-input" style="width: calc(100% - 25px)" id="service"><option value="false" disabled selected>Выберите службу...</option>`
            $.ajax({
                url: '/get_service',
                method: 'GET',
                success: function(res){
                    for (var service of res){
                        table += `<option value="${service['id']}">${service['service']}</option>`
                    }
                    table += `</select></td></tr>`
                },
                async: false
            })
            table += `<tr><td>Тип события</td><td><select onchange="get_comment()" class="text-input" style="width: calc(100% - 25px)" id="type_id"><option value="false" disabled selected>Выберите тип события...</option>`
            $.ajax({
                url: '/get_types',
                method: 'GET',
                success: function(res){
                    for (var type of res){
                        table += `<option value="${type['id']}">${type['event']}</option>`
                    }
                    table += `</select></td></tr>`
                },
                async: false
            })
            table += `</tr>
                </tbody>
            </table>`
            $('#text_modal_side_menu').after(table)
            ////Для выбора времени
            var today = new Date();
            new AirDatepicker('#timestamp',
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
        function get_comment(){
            if(!document.getElementById('templates')){ ///если нет еще шаблонов
                $('#type_id').parent().parent().after(`<tr>
                        <td>Описание</td>
                        <td><textarea class="text-input" id="description" style="width: calc(100% - 50px)" placeholder="Описание..."></textarea></td>
                    </tr>`)

                var new_tr = '<tr>'
                new_tr += `<td>Шаблон описания</td><td><select onchange="document.getElementById('description').value = this.value" class="text-input" style="width: calc(100% - 25px)" id="templates"><option value="false" disabled selected>Выберите шаблон описания...</option>`
                $.ajax({
                    url: '/get_templates/'+document.getElementById('type_id').value,
                    method: 'GET',
                    success: function(res){
                        console.log(res)
                        for (var template of res){
                            new_tr += `<option value="${template['template']}">${template['template']}</option>`
                        }
                        new_tr += `</select></td></tr>`
                    },
                    async: false
                })
                $('#type_id').parent().parent().after(new_tr)
            }else{
                var select = document.getElementById('templates')
                select.innerText = ''
                $.ajax({
                    url: '/get_templates/'+document.getElementById('type_id').value,
                    method: 'GET',
                    success: function(res){
                        select.innerHTML += `<option disabled selected>Выберите шаблон описания...</option>`
                        for (var template of res){
                            select.innerHTML += `<option value="${template['template']}">${template['template']}</option>`
                        }
                    },
                    async: false
                })
            }
        }
        function store_new_record(id){
            var send = false
            var arr = new Map()
            try {
                arr.set('description', document.getElementById('description').value)
                arr.set('timestamp', document.getElementById('timestamp').value)
                arr.set('type_id', document.getElementById('type_id').value)
                arr.set('ingener', document.getElementById('ingener').value)
                arr.set('subdivision_id', document.getElementById('subdivision').value)
                arr.set('service_id', document.getElementById('service').value)
                send = true
                arr.forEach(function(value1, value2, map){
                    if (!value1 || value1 === 'false'){
                        send = false
                    }
                })
                var url = ''
                if(id){
                    url = '/save_event/'+id
                    arr.set('accept', document.getElementById('accept').checked)
                }else {
                    url = '/save_event/false'
                }
            }catch (e) {
                send = false
            }
            if(send){
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: Object.fromEntries(arr),
                    success: function (res) {
                        // console.log(res)
                        close_modal_side_menu()
                        get_table_data()
                    },
                    async: false
                })

            }else {
                change_header_modal('Добавление записи в журнал событий<br>Заполнены не все данные!')
            }
        }
        function get_table_data(){
            document.getElementById('search_row').value = ''
            document.getElementById('main_div').innerText = ''
            var width = ($('#main_div').width()-115)/8 + 'px'
            var date_str = $("#period").val().replace(/ /g,'')
            date_str = date_str.split('-')
            $.ajax({
                url: '/journal_events_data/'+date_str[0]+'/'+date_str[1],
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
                        onchange: false,
                        allowInsertRow:false,
                        columns: [
                            {type:'hidden',name:'id'},
                            {width:width,type:'text',name:'timestamp',title:'Дата создания',readOnly:true,},
                            {width:width,type:'text',name:'display_name',title:'Диспетчер',readOnly:true,},
                            {width:width,type:'text',name:'ingener',title:'Инженер',readOnly:true,},
                            {width:width,type:'text',name:'subdivision',title:'Подразделение',readOnly:true,},
                            {width:width,type:'text',name:'description',title:'Описание',readOnly:true,},
                            {width:width,type:'text',name:'event',title:'Тип сообщения',readOnly:true,},
                            {width:width,type:'text',name:'service',title:'Служба',readOnly:true,},
                            {width:width,type:'checkbox',name:'accept',title:'Принято',readOnly:true,},
                            {width:'45px',type:'image',name:'img',title:'Изм.',readOnly:true,},
                        ],
                        csvFileName: 'Журнал_событий'
                    });
                    $('#download_csw').on('click', function (){
                        jsTable.download()
                    })
                    $('.jexcel_column_filter').on('dblclick', function (){
                        document.getElementById('search_row').value = ''
                    })
                    $('#main_content table tr td:last-child').on('dblclick', function (){
                        edit_record(this.parentNode.getElementsByTagName('td')[1].textContent)
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

