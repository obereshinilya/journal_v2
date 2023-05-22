@extends('layouts.app')
@section('title')
    Временные показатели
@endsection

@section('side_menu')
    @include('include.side_menu')
@endsection

@section('content')
    <style>
        #period{display: none}
    </style>
    <div id="context_time_params" class="context_menu">
        <a id="open_edit_comment" style="white-space: nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 style="fill: rgba(0, 0, 0, 1);">
                <path d="m16 2.012 3 3L16.713 7.3l-3-3zM4 14v3h3l8.299-8.287-3-3zm0 6h16v2H4z"></path>
            </svg>
            Добавить комментарий
        </a>
        <a id="delete_comment" style="white-space: nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 style="fill: rgba(0, 0, 0, 1);">
                <path
                    d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm4 12H8v-9h2v9zm6 0h-2v-9h2v9zm.618-15L15 2H9L7.382 4H3v2h18V4z"></path>
            </svg>
            Очистить
        </a>
    </div>
    <div id="context_h1" class="context_menu">
        <a id="confirm_hour" style="white-space: nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="m10.933 13.519-2.226-2.226-1.414 1.414 3.774 3.774 5.702-6.84-1.538-1.282z"></path><path d="M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zM5 19V5h14l.002 14H5z"></path></svg>
            Подтвердить/снять достоверность
        </a>
        @if($setting['param_copy'] == 'true')
            <a id="copy_hour" style="white-space: nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M20 2H10c-1.103 0-2 .897-2 2v4H4c-1.103 0-2 .897-2 2v10c0 1.103.897 2 2 2h10c1.103 0 2-.897 2-2v-4h4c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zM4 20V10h10l.002 10H4zm16-6h-4v-4c0-1.103-.897-2-2-2h-4V4h10v10z"></path><path d="M6 12h6v2H6zm0 4h6v2H6z"></path></svg>
                Скопировать сводку
            </a>
        @endif
    </div>
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Часовые показатели.</p>
        <button id="pdf_btn" class="btn header_blocks btn_img" data-toggle="tooltip" title="Загрузить PDF" ><img onclick="print()" src="/assets/img/pdf.svg"></button>
        <button id="excel_btn" class="btn header_blocks btn_img" data-toggle="tooltip" title="Загрузить XLSX" ><img onclick="excel()" src="/assets/img/excel.svg"></button>
        @include('include.search_row')
        @include('include.date')
        @include('include.period_date_time')
    </div>
    <div id="tableDiv" class="tableDiv">
        <div id="statickTableDiv" class="statickTableDiv">
            <table id="statickTable" class="statickTable">
                <thead>
                <tr>
                    <th>Наименование параметра</th>
                    <th>Ед.измерения</th>
                    <th style="padding: 0"><img id="clear_graph" onclick="clear_graph()" src="/assets/img/clear.svg"
                                                class="hover_img" style="display: none"></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div id="dynamicTableDiv" class="dynamicTableDiv">
            <table id="dynamicTable" class="dynamicTable">
                <thead id="thead_dynamic">
                <tr>
                    <th class="sutki" style="z-index: 6">Сутки</th>
                    @for($j=0; $j<24; $j++)
                        @if($j + $setting['start_smena'] < 10)
                            <th onclick="goToHour(this)">0{{$j + $setting['start_smena']}}:00</th>
                        @elseif($j + $setting['start_smena'] > 23)
                            @if($j + $setting['start_smena'] <= 33)
                                <th onclick="goToHour(this)">0{{$j + $setting['start_smena'] - 24}}:00</th>
                            @else
                                <th onclick="goToHour(this)">{{$j + $setting['start_smena'] - 24}}:00</th>
                            @endif
                        @else
                            <th onclick="goToHour(this)">{{$j + $setting['start_smena']}}:00</th>
                        @endif
                    @endfor
                </tr>
                </thead>
                <tbody id="tbody_dynamic">

                </tbody>
            </table>
            <div id="dynamicGraph" style="display: none">
            </div>
        </div>
    </div>

<style>
    .air-datepicker{
        z-index: 905;
    }
</style>
    <script>
        $(document).ready(function () {
            side_menu_obj_click()
            initial_resize()
            get_table_data()
            initial_trigger_on_header()
        })
        function initial_trigger_on_header(){
            $('#thead_dynamic th').on('contextmenu', function (event) {
                var context_menu = document.getElementById('context_h1')
                context_menu.style.display = 'block'
                context_menu.style.top = Number(event.pageY - $('#header_block_param').height()) + 'px'
                context_menu.style.left = Number(event.pageX - $('#side_menu').width()) + 'px'
                document.getElementById('confirm_hour').setAttribute('onclick', `confirm_hour("${this.textContent}")`)
                @if($setting['param_copy'] == 'true')
                document.getElementById('copy_hour').setAttribute('onclick', `copy_hour("${this.textContent}")`)
                @endif
                $('body').on('click', function () {
                    document.getElementById('context_h1').style.display = 'none'
                })
            })
        }
        function copy_hour(hour){
            change_header_modal('Копирование сводки за '+hour.toLowerCase()+' '+$("#date_start").val()+'<br>Существующие данные будут потеряны!')
            $('#text_modal_side_menu').after('<input type="text" id="time_div" placeholder="Укажите время назначения сводки..." class="datepicker-here" style="text-align: center" />')
            var today = new Date();
            if (hour === 'Сутки'){
                new AirDatepicker('#time_div',
                    {
                        timepicker: false,
                        autoClose: true,
                        maxDate: today,
                        keyboardNav: true
                    })
            }else {
                today.setMinutes(0)
                new AirDatepicker('#time_div',
                    {
                        timepicker: true,
                        autoClose: false,
                        maxDate: today,
                        maxHours: 23,
                        maxMinutes:0,
                        keyboardNav: true
                    })
            }
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `confirm_copy_hour('${hour}')`)
        }
        function confirm_copy_hour(hour){
            var arr = new Map()
            arr.set('date_from', $("#date_start").val())
            arr.set('hour_from', hour)
            arr.set('date_to', document.getElementById('time_div').value)
            $.ajax({
                url: '/copy_hour',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    if (res !== 'false'){
                        change_header_modal(res)
                    }else {
                        get_table_data()
                        close_modal_side_menu()
                    }
                }
            })
        }
        function confirm_hour(hour){
            var arr = new Map()
            arr.set('date', $("#date_start").val())
            arr.set('hour', hour)
            $.ajax({
                url: '/confirm_hour',
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    if (res){
                        change_header_modal(res)
                        document.getElementById('submit_button_side_menu').setAttribute('onclick', 'close_modal_side_menu()')
                        open_modal_side_menu()
                    }else {
                        get_table_data()
                    }
                }
            })
        }
        function paint_confirm_hour(){
            $.ajax({
                url: '/get_confirmed_hours/'+$("#date_start").val(),
                method: 'get',
                success: function (res) {
                    var cellIndex = []
                    var ths = document.getElementById('thead_dynamic').getElementsByTagName('th')
                    if (res.indexOf(null) >= 0){
                        ths[0].style.background = '#98FB98'
                        cellIndex.push(0)
                    }else {
                        ths[0].style.background = ''
                    }
                    for(var i = 1; i<=24; i++){
                        if (res.indexOf(ths[i].textContent) >= 0){
                            ths[i].style.background = '#98FB98'
                            cellIndex.push(i)
                        }else{
                            ths[i].style.background = ''
                        }
                    }
                    @if($setting['authenticity_edit'] == 'false')
                    if (cellIndex.length>0){
                        var trs = document.getElementById('tbody_dynamic').getElementsByTagName('tr')
                        for (var tr of trs){
                            var tds = tr.getElementsByTagName('td')
                            for (var index of cellIndex){
                                tds[index].setAttribute('contenteditable', 'false')
                            }
                        }
                    }
                    @endif
                }
            })
        }

        function get_table_data() {
            if (localStorage.getItem('hour')) {
                $("#date_start").val(localStorage.getItem('hour'))
                localStorage.clear()
            }
            delete_min_rows()
            var href = ''
            var custom_list = false
            if (document.getElementById('choiced_id').textContent === 'false'){
                custom_list = true
                href = 'get_custom_data/'+document.getElementsByClassName('choiced')[0].getAttribute('data-id')
            }else{
                href = 'get_hour_data'
            }
            $.ajax({
                url: '/'+href +'/' + $("#date_start").val(),
                method: 'GET',
                dataType: 'html',
                async: true,
                success: function (res) {
                    res = JSON.parse(res)
                    var sutki = ''
                    var static_table_body = document.getElementById('statickTable').getElementsByTagName('tbody')[0]
                    var table_body = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0]
                    table_body.innerText = ''
                    static_table_body.innerHTML = ''
                    for (var row of res) {
                        var tr = document.createElement('tr')
                        var static_tr = document.createElement('tr')
                        tr.setAttribute('data-id', row['id'])
                        static_tr.setAttribute('data-id', row['id'])
                        static_tr.innerHTML += `<td>${row['full_name']}</td>`
                        static_tr.innerHTML += `<td>${row['e_unit']}</td>`
                        static_tr.innerHTML += `<td style="padding: 0"><img class="hover_img" data-id="${row['id']}" data-name="${row['full_name']}" data-unit="${row['e_unit']}" onclick="create_graph(this)" src="/assets/img/chart.svg"></td>`
                        for (var id = 0; id <= 24; id++) {
                            if (id === 0) {
                                sutki = ' sutki'
                            } else {
                                sutki = ''
                            }
                            if (row[id]['id']) {
                                if (Boolean(row[id]['xml_create'] === true)) {
                                    if (Boolean(row[id]['manual']) === true) {
                                        tr.innerHTML += `<td data-toggle="tooltip" data-bs-html="true" title="<b>Изменил:</b> ${row[id]['change_by']} <br> ${row[id]['comment']}" data-id="${row[id]['id']}" contenteditable="{{$setting['hand_edit']}}" class="manual ${sutki}">${row[id]['val']}</td>`
                                    } else {
                                        tr.innerHTML += `<td data-toggle="tooltip" data-bs-html="true" title="${row[id]['comment']}" data-id="${row[id]['id']}" contenteditable="{{$setting['masdu_edit']}}" class="xml ${sutki}">${row[id]['val']}</td>`
                                    }
                                } else {
                                    if (Boolean(row[id]['manual']) === true) {
                                        tr.innerHTML += `<td data-toggle="tooltip" data-bs-html="true" title="<b>Изменил:</b> ${row[id]['change_by']} <br> ${row[id]['comment']}" data-id="${row[id]['id']}" contenteditable="{{$setting['hand_edit']}}" class="manual ${sutki}">${row[id]['val']}</td>`
                                    } else {
                                        tr.innerHTML += `<td data-toggle="tooltip" data-bs-html="true" title="${row[id]['comment']}" data-id="${row[id]['id']}" contenteditable="true" class="usialy ${sutki}">${row[id]['val']}</td>`
                                    }
                                }
                            } else {
                                tr.innerHTML += `<td hour-id="${id}" param-id="${row['id']}" contenteditable="true" class="new_param ${sutki}">...</td>`
                            }
                        }
                        static_table_body.appendChild(static_tr);
                        table_body.appendChild(tr);
                    }
                    $("#dynamicTable td").on('focus', function () {
                        this.setAttribute('data-old', this.textContent)
                    })
                    $("#dynamicTable td").keypress(function (code_btn) {
                        if (code_btn.which === 13) {
                            code_btn.preventDefault();
                            set_new_value(this)
                        }
                    })
                    add_trigger_paint()
                    if(!custom_list){
                        check_choiced_obj()
                    }
                    search_object()
                    create_tooltip_and_comment()
                    go_to_setting()
                    paint_confirm_hour()
                }
            })
        }

        function create_tooltip_and_comment() {   //Создание всплывашек и действий на пкм
            $('[data-toggle="tooltip"]').tooltip({
                content: function () {
                    return $(this).prop('title');
                }
            })
            $('#tbody_dynamic [data-toggle="tooltip"]').on('contextmenu', function (event) {
                var context_menu = document.getElementById('context_time_params')
                context_menu.style.display = 'block'
                context_menu.style.top = Number(event.pageY - $('#header_block_param').height()) + 'px'
                context_menu.style.left = Number(event.pageX - $('#side_menu').width()) + 'px'
                document.getElementById('open_edit_comment').setAttribute('onclick', `open_edit_comment(${this.getAttribute('data-id')}, "${this.classList}")`)
                document.getElementById('delete_comment').setAttribute('onclick', `delete_comment(${this.getAttribute('data-id')}, "${this.classList}")`)
                $('body').on('click', function () {
                    document.getElementById('context_time_params').style.display = 'none'
                })
            })
        }

        function go_to_setting() {
            $('#statickTableDiv tr').on('contextmenu', function () {
                change_header_modal(`Перейти к редактированию параметра "${this.getElementsByTagName('td')[0].textContent}"?`)
                document.getElementById('submit_button_side_menu').setAttribute('onclick', `window.location.href = '/signal_settings/${this.getAttribute('data-id')}'`)
                open_modal_side_menu()
            })
        }

        function open_edit_comment(id, classList) {   //По нажатию на редактирование комментариев
            change_header_modal('Введите текст комментария:')
            var type = false
            if (classList.match(/sutki/g)) {
                type = 'sutki'
            }
            $('#text_modal_side_menu').after(`<input class="text-input" id="new_comment" type="text" placeholder="Текст комментария...">`)
            $('#new_comment').keydown(function (event) {
                if (event.which == 13) {
                    $('#submit_button_side_menu').click()
                }
            })
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `save_comment(${id}, "${type}")`)
        }

        function save_comment(id, type) {   //Сохранание комментариев
            var arr = new Map()
            arr.set('text', document.getElementById('new_comment').value)
            $.ajax({
                url: '/save_comment/' + id + '/' + type,
                method: 'POST',
                data: Object.fromEntries(arr),
                success: function (res) {
                    get_table_data()
                    close_modal_side_menu()
                }
            })
        }

        function delete_comment(id, classList) {
            var type = false
            if (classList.match(/sutki/g)) {
                type = 'sutki'
            }
            $.ajax({
                url: '/delete_comment/' + id + '/' + type,
                method: 'GET',
                success: function (res) {
                    get_table_data()
                }
            })
        }

        function set_new_value(td) {
            change_header_modal('Подтвердить изменения? <br>Будет произведена запись в журнал')
            open_modal_side_menu()
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `confirm_update("${td.textContent}", ${td.parentNode.rowIndex}, ${td.cellIndex})`)
            td.textContent = td.getAttribute('data-old')
        }

        function isNumber(num) {
            return typeof num === 'number' && !isNaN(num);
        }

        function confirm_update(value, row, cell) {
            var td = document.getElementById('dynamicTable').getElementsByTagName('tr')[row].getElementsByTagName('td')[cell]
            try {
                if (isNumber(Number(value.replace(',', '.')))) {   //Если цифра
                    if (td.classList.contains('new_param')) {
                        var timestamp = false
                        if (!td.classList.contains('sutki')) {
                            timestamp = document.getElementById('dynamicTable').getElementsByTagName('tr')[0].getElementsByTagName('th')[cell].textContent.split(':')[0]
                        }
                        $.ajax({
                            url: '/create_param/' + value.replace(',', '.') + '/' + timestamp + '/' + $("#date_start").val() + '/' + td.parentNode.getAttribute('data-id'),
                            method: 'GET',
                            dataType: 'html',
                            async: true,
                            success: function (res) {
                                if (res === 'false') {
                                    change_header_modal('Запись невозможна! <br> Время сводки не наспупило')
                                    document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                                } else {
                                    get_table_data()
                                    close_modal_side_menu()
                                }
                            }
                        })
                    } else {
                        var sutki = false
                        if (td.classList.contains('sutki')) {
                            sutki = true
                        }
                        $.ajax({
                            url: '/update_param/' + value.replace(',', '.') + '/' + td.getAttribute('data-id') + '/' + sutki,
                            method: 'GET',
                            dataType: 'html',
                            async: true,
                            success: function (res) {
                                get_table_data()
                                // td.textContent = value.replace(',', '.')
                                // td.setAttribute('class', 'manual')
                                close_modal_side_menu()
                            }
                        })
                    }
                } else {   //Если не цифра
                    change_header_modal('Неверный формат числа!')
                    open_modal_side_menu()
                    document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                }
            } catch (e) { //Если ввели спец символы
                change_header_modal('Неверный формат числа!')
                open_modal_side_menu()
                document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
            }
        }

        function delete_min_rows() {
            var old_tds = $('[class^="minute-param-"]');
            for (var old_td of old_tds) {
                old_td.parentNode.removeChild(old_td);
            }
        }

        function goToHour(th) {
            var hour_th = th.textContent.split(':')[0]
            var date = $("#date_start").val()
            if (document.getElementsByClassName('minute-param-' + hour_th).length > 0) {
                var old_tds = document.getElementsByClassName('minute-param-' + hour_th);
                while (old_tds[0]) {
                    old_tds[0].parentNode.removeChild(old_tds[0]);
                }
            } else {
                var href = ''
                var custom_list = false
                if (document.getElementById('choiced_id').textContent === 'false'){
                    custom_list = true
                    href = 'custom_param_minutes/'+document.getElementsByClassName('choiced')[0].getAttribute('data-id')
                }else{
                    href = 'hours_param_minutes'
                }
                $.ajax({
                    url: '/'+href+'/' + date + '/' + hour_th,
                    method: 'GET',
                    success: function (res) {
                        var cellIndex = th.cellIndex
                        var minutes = ''
                        //разбираемся с хедером
                        for (var i = 1; i < 12; i++) {
                            if (Number(55 - (i - 1) * 5) < 10) {
                                minutes = '0' + Number(55 - (i - 1) * 5)
                            } else {
                                minutes = Number(55 - (i - 1) * 5)
                            }
                            th.insertAdjacentHTML('afterend', `<th class="minute-param-${hour_th}" style="font-weight: normal">${hour_th + ':' + minutes}</th>`)
                        }
                        //разбираемся с содержимым
                        var trs = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr')
                        var j = 0; //номер строки
                        for (var tr of trs) {
                            for (var td of tr.getElementsByTagName('td')) {
                                if (td.cellIndex === cellIndex) {
                                    for (var i = 11; i >= 1; i--) {
                                        if (res[j][i]) {
                                            td.insertAdjacentHTML('afterend', `<td class="minute-param-${hour_th}">${res[j][i]}</td>`)
                                        } else {
                                            td.insertAdjacentHTML('afterend', `<td class="minute-param-${hour_th}">...</td>`)
                                        }
                                    }
                                }
                            }
                            j++
                        }
                    }, async: false,
                })
            }
            add_trigger_paint()
        }

        function initial_resize() {
            $("#statickTableDiv").resizable({
                handles: 'e'
            });
            $('#statickTableDiv').resize(function () {
                var static_width = ($(this).width() / $("#tableDiv").width()) * 100
                this.style.width = static_width + "%"
                static_width = 100 - static_width - 4000 / $("#tableDiv").width()
                document.getElementById('dynamicTableDiv').style.width = static_width + "%"
            })
            $("#statickTableDiv").scroll(function () {
                $('#dynamicTableDiv').scrollTop($("#statickTableDiv").scrollTop());
            });
            $("#dynamicTableDiv").scroll(function () {
                $('#statickTableDiv').scrollTop($("#dynamicTableDiv").scrollTop());
            });
        }

        function add_trigger_paint() {
            $("#dynamicTable td").on('mouseover', function () {
                make_paint(this)
            })
            $("#dynamicTable td").on('mouseout', function () {
                clear_paint(this)
            })
        }

        function make_paint(td) {
            var rowIndex = td.parentNode.rowIndex
            var cellIndex = td.cellIndex
            var statick_tr = document.getElementById('statickTable').getElementsByTagName('tr')[rowIndex]
            var dynamic_tr = document.getElementById('dynamicTable').getElementsByTagName('tr')[rowIndex]
            for (var td_static of statick_tr.getElementsByTagName('td')) {
                td_static.style.borderColor = 'black'
            }
            for (var td_dynamic of dynamic_tr.getElementsByTagName('td')) {
                td_dynamic.style.borderColor = 'black'
            }
            var dynamic_trs = document.getElementById('dynamicTable').getElementsByTagName('tr')
            for (var i = 1; i < dynamic_trs.length; i++) {
                dynamic_trs[i].getElementsByTagName('td')[cellIndex].style.borderColor = 'black'
            }
            document.getElementById('dynamicTable').getElementsByTagName('tr')[0].getElementsByTagName('th')[cellIndex].style.borderColor = 'black'
        }

        function clear_paint(td) {
            var rowIndex = td.parentNode.rowIndex
            var cellIndex = td.cellIndex
            var statick_tr = document.getElementById('statickTable').getElementsByTagName('tr')[rowIndex]
            var dynamic_tr = document.getElementById('dynamicTable').getElementsByTagName('tr')[rowIndex]
            for (var td_static of statick_tr.getElementsByTagName('td')) {
                td_static.style.borderColor = ''
            }
            for (var td_dynamic of dynamic_tr.getElementsByTagName('td')) {
                td_dynamic.style.borderColor = ''
            }
            var dynamic_trs = document.getElementById('dynamicTable').getElementsByTagName('tr')
            for (var i = 1; i < dynamic_trs.length; i++) {
                dynamic_trs[i].getElementsByTagName('td')[cellIndex].style.borderColor = ''
            }
            document.getElementById('dynamicTable').getElementsByTagName('tr')[0].getElementsByTagName('th')[cellIndex].style.borderColor = ''
        }

        function create_graph(img) {
            if (img.classList.contains('opened_graph')) {
                img.classList.remove('opened_graph')
            } else {
                img.classList.add('opened_graph')
            }
            if ($('.opened_graph')[0]) {
                document.getElementById('clear_graph').style.display = ''
            } else {
                document.getElementById('clear_graph').style.display = 'none'
            }
            render_graph()
        }

        function print() {
            localStorage.setItem('hour', $("#date_start").val())
            window.location.href = '/print_hour/' + $("#date_start").val()
        }

        function excel() {
            window.location.href = '/excel_hour/' + $("#date_start").val()
        }

        function render_graph() {
            if (document.getElementsByClassName('opened_graph').length > 0) {
                document.getElementById('dynamicGraph').innerText = ''
                document.getElementById('dynamicTable').style.display = 'none'
                document.getElementById('dynamicGraph').style.display = 'block'
                document.getElementById('pdf_btn').style.display = 'none'
                document.getElementById('excel_btn').style.display = 'none'
                document.getElementById('date_start').style.display = 'none'
                document.getElementById('period').style.display = 'block'
                var schema = []
                schema[0] = {"name": "Time", "type": "date", "format": "%Y-%m-%d %H:%M:%S"}
                var yaxis = []
                var i = 1
                var param_id = ''
                for (var img of document.getElementsByClassName('opened_graph')) {
                    schema[i] = {"name": img.getAttribute('data-name'), "type": "number"}
                    yaxis[i - 1] = JSON.parse(`{"plot":{"value": "${img.getAttribute('data-name')}", "connectnulldata": true, "type": "smooth-line"},"format": {"suffix": " ${img.getAttribute('data-unit')}"}}`)
                    param_id = param_id + '\'' + img.getAttribute('data-id') + '\'' + ','
                    i++
                }
                var date_str = $("#period").val().replace(/ /g,'')
                date_str = date_str.split('-')
                $.ajax({
                    url: '/get_data_for_graph/' + param_id + '/'+date_str[0]+'/'+date_str[1],
                    method: 'GET',
                    async: false,
                    success: function (res) {
                        res = JSON.parse(res)
                        let fusionDataStore = new FusionCharts.DataStore();
                        let fusionTable = fusionDataStore.createDataTable(res, schema);
                        var height = $('#tableDiv').height()
                        new FusionCharts({
                            type: 'timeseries',
                            renderAt: 'container',
                            width: "99%",
                            height: height,
                            dataSource: {
                                data: fusionTable,
                                chart: {exportenabled: true},
                                caption: {},
                                navigator: {timeFormat: {day: "%-d %b %Y",hour: "%-d %b %Y, %H:%M",minutes: "%-d %b %Y, %H:%M"}},
                                "extensions": {
                                    "customRangeSelector": {
                                        "enabled": "0"
                                    }
                                },
                                "xAxis": {
                                    outputTimeFormat: {day: "%-d %b %Y",hour: "%-d %b %Y, %H:%M",minutes: "%-d %b %Y, %H:%M"},
                                    timemarker: [
                                        {
                                            start: "2023-05-15 16:00:00",
                                            label: "Проведение ТО",
                                            timeformat: "%Y-%m-%d %H:%M:%S",
                                            type: "full",
                                            style: {marker: {fill: "#D0D6F4"}}
                                        },
                                        {
                                            start: "2023-05-12 10:00:00",
                                            label: "Сработал датчик загазованности",
                                            timeformat: "%Y-%m-%d %H:%M:%S",
                                            type: "full",
                                            style: {marker: {fill: "#D0D6F4"}}
                                        },
                                    ]
                                },
                                tooltip: {
                                    outputTimeFormat: {hour: "%-d %b %Y, %H:%M", minutes: "%-d %b %Y, %H:%M"}
                                },
                                yaxis: yaxis
                            }
                        }).render("dynamicGraph")
                    },
                    error: function () {
                        document.getElementById('dynamicGraph').innerHTML='<h1>За выбранный период нет данных!</h1>'
                        document.getElementById('dynamicGraph').style.textAlign='center'
                    }
                })
            } else {
                document.getElementById('dynamicTable').style.display = ''
                document.getElementById('dynamicGraph').style.display = 'none'
                document.getElementById('pdf_btn').style.display = ''
                document.getElementById('excel_btn').style.display = ''
                document.getElementById('date_start').style.display = ''
                document.getElementById('period').style.display = ''
            }
        }

        function clear_graph() {
            $('.opened_graph').removeClass('opened_graph');
            document.getElementById('clear_graph').style.display = 'none'
            render_graph()
        }

        function check_choiced_obj() {
            if ($('.choiced')[0]) {
                hide_rows($('.choiced')[0].getAttribute('data-id'))
            }
        }

        function get_user_custom_list(){
            $.ajax({
                url: '/get_user_lists',
                method: 'GET',
                dataType: 'html',
                success: function(res){
                    var lists = JSON.parse(res)
                    if(lists.length > 0){
                        var side_menu = document.getElementById('side_menu_content')
                        var last_ul = side_menu.lastChild
                        var ul = document.createElement('ul')
                        ul.innerHTML = `<li class="side_obj">Свои списки<img id="custom_img" onclick="open_custom_list(this)" class="plus_icon hide" src="http://127.0.0.1/assets/img/plus.png"></li>`
                        ul.style.paddingLeft = '15px'
                        last_ul.after(ul)
                        var new_ul = document.createElement('ul')
                        new_ul.id = 'custom_list'
                        for(var list of lists){
                            new_ul.innerHTML += `<li onclick="get_custom_params(this)" class="side_obj custom_lists" data-id="${list['id_list']}">${list['name_list']}</li>`
                        }
                        ul.append(new_ul)
                        $('.custom_lists').on('contextmenu', function (){
                            var context_menu = document.getElementById('context_custom_list')
                            context_menu.style.display = 'block'
                            context_menu.style.top = Number(event.pageY)+'px'
                            context_menu.style.left = Number(event.pageX)+'px'
                            $('body').on('click', function (){
                                document.getElementById('context_custom_list').style.display = 'none'
                            })
                            localStorage.setItem('custom_list', this.getAttribute('data-id'))
                        })
                    }
                },
                async: false
            })
        }
        function hide_list(){
            $.ajax({
                url: '/hide_list/'+localStorage.getItem('custom_list'),
                method: 'GET',
                dataType: 'html',
                success: function(res){
                    update_side_object()
                    try{
                        document.getElementById('custom_img').click()
                    }catch (e) {
                        console.log(e)
                    }
                },
                async: false
            })
        }
        function open_custom_list(img){
            if(img.classList.contains('hide')){
                img.classList.remove('hide')
                img.style.transform = 'rotate(45deg)'
                var ul = document.getElementById('custom_list')
                ul.style.display = 'block'
            }else{
                img.classList.add('hide')
                img.style.transform = ''
                document.getElementById('custom_list').style.display = ''
            }
        }
        function get_custom_params(li){
            if (li.classList.contains('choiced')){
                li.classList.remove('choiced')
                document.getElementById('choiced_id').textContent = ''
                document.getElementById('header_doc').textContent = 'Часовые показатели.'
            }else {
                $('.choiced').removeClass('choiced');
                li.classList.add('choiced')
                document.getElementById('choiced_id').textContent = 'false'
                document.getElementById('header_doc').textContent = 'Часовые показатели. '+li.textContent
            }
            get_table_data()
        }
    </script>
@endsection
