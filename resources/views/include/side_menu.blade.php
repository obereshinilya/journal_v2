<div class="side_menu" id="side_menu">
    <p id="choiced_id" style="display: none"></p>
    <p id="choiced_object" style="display: none"></p>
    <div class="show_hide_side_menu" id="show_hide_side_menu">
        <img id="show_hide_side_menu_btn" class="hide" src="/assets/img/arrow_right.svg">
    </div>
</div>
<div id="context_side_menu" class="context_menu">
    <a onclick="rename_object(this.parentNode.getAttribute('data-id'),this.parentNode.getAttribute('full-name'))">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="m16 2.012 3 3L16.713 7.3l-3-3zM4 14v3h3l8.299-8.287-3-3zm0 6h16v2H4z"></path></svg>
        Переименовать объект
    </a>
    <a onclick="add_new_object(this.parentNode.getAttribute('data-id'),this.parentNode.getAttribute('full-name'))">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"></path><path d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"></path></svg>
        Добавить объект
    </a>
    <a onclick="add_new_signal(this.parentNode.getAttribute('data-id'),this.parentNode.getAttribute('full-name'))">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
        Добавить сигнал
    </a>
    <a onclick="delete_this_object(this.parentNode.getAttribute('data-id'),this.parentNode.getAttribute('full-name'))">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm4 12H8v-9h2v9zm6 0h-2v-9h2v9zm.618-15L15 2H9L7.382 4H3v2h18V4z"></path></svg>
        Удалить объект
    </a>
</div>
<div id="context_main_side_menu" class="context_menu">
    <a onclick="add_new_object()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M13 9h-2v3H8v2h3v3h2v-3h3v-2h-3z"></path><path d="M20 5h-8.586L9.707 3.293A.996.996 0 0 0 9 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM4 19V7h16l.002 12H4z"></path></svg>
        Добавить филиал ДО
    </a>
    <a class="custom_list_visible" onclick="window.location = '/custom_list/false'">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
        Создать свой список
    </a>
    <a class="custom_list_visible" onclick="copy_custom_list()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M20 2H10c-1.103 0-2 .897-2 2v4H4c-1.103 0-2 .897-2 2v10c0 1.103.897 2 2 2h10c1.103 0 2-.897 2-2v-4h4c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zM4 20V10h10l.002 10H4zm16-6h-4v-4c0-1.103-.897-2-2-2h-4V4h10v10z"></path><path d="M6 12h6v2H6zm0 4h6v2H6z"></path></svg>
        Скопировать список другого пользователя
    </a>
</div>
<div id="context_custom_list" class="context_menu">
    <a onclick="window.location.href = '/custom_list/'+localStorage.getItem('custom_list')">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="m16 2.012 3 3L16.713 7.3l-3-3zM4 14v3h3l8.299-8.287-3-3zm0 6h16v2H4z"></path></svg>
        Редактировать список
    </a>
    <a onclick="hide_list()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm4 12H8v-9h2v9zm6 0h-2v-9h2v9zm.618-15L15 2H9L7.382 4H3v2h18V4z"></path></svg>
        Скрыть список
    </a>
</div>


<script>
    $(document).ready(function () {
        get_side_object()
        $('#main_content').width($(document.body).width()-$('#side_menu').width())
        $( "#side_menu" ).resizable({
            handles: 'e'
        });
        $('#side_menu').resize(function(){
            change_width_side_menu()
        })
        $('#show_hide_side_menu_btn').on('click', function (){
            if (this.classList.contains('hide')){
                this.classList.remove('hide')
                document.getElementById('side_menu').style.width = '50px'
                document.getElementById('show_hide_side_menu_btn').style.transform = 'rotate(0deg)'
                $( "#side_menu_content" ).css("display", "none");
                $( "#side_menu" ).resizable('disable');
                change_width_side_menu()
            }else {
                this.classList.add('hide')
                document.getElementById('side_menu').style.width = ''
                document.getElementById('show_hide_side_menu_btn').style.transform = ''
                $( "#side_menu" ).resizable('enable');
                $( "#side_menu" ).resizable({
                    handles: 'e'
                });
                change_width_side_menu()
            }
        })
        $(window).on('resize', function(){
            $( "#main_content" ).width($(window).width() - $("#side_menu").width())
        });
    })

    function change_width_side_menu(){
        setTimeout(function (){
            if ($('#side_menu').width() < 80){
                var btn = document.getElementById('show_hide_side_menu_btn')
                if (btn.classList.contains('hide')){
                    btn.click()
                }else {
                    $('#main_content').width($(document.body).width()-$('#side_menu').width())
                    $('#side_menu_content').width($('#side_menu').width()-15)
                }
            }else {
                $('#main_content').width($(document.body).width()-$('#side_menu').width())
                $('#side_menu_content').width($('#side_menu').width()-15)
                $( "#side_menu_content" ).css("display", "");
            }
        }, 300)
    }

    function get_side_object(){
        $.ajax({
            url: '/get_side_object',
            method: 'GET',
            dataType: 'html',
            success: function(data){
                var side_menu = document.getElementById('side_menu')
                try {
                    var old_tree = document.getElementById('side_menu_content')
                    old_tree.parentNode.removeChild(old_tree)
                }catch (e){

                }
                var side_tree=document.createElement('div');
                side_tree.innerHTML=data;
                side_tree.classList.add('side_menu_content')
                side_tree.id = 'side_menu_content'
                side_menu.insertBefore(side_tree, document.getElementById('show_hide_side_menu'))
                ///Прописываем действия с деревом
                $('.plus_icon').on('click', function (){
                    if (this.classList.contains('hide')){
                        this.classList.remove('hide')
                        this.style.transform = 'rotate(45deg)'
                        var ul = document.getElementById(this.getAttribute('data-ul'))
                        ul.style.display = 'block'
                    }else {
                        this.classList.add('hide')
                        this.style.transform = ''
                        document.getElementById(this.getAttribute('data-ul')).style.display = ''
                    }
                })
                $('li').on('contextmenu', function (event){
                    var li = this
                    $('.bordered').removeClass('bordered')
                    li.classList.add('bordered')
                    var context_menu = document.getElementById('context_side_menu')
                    context_menu.style.display = 'block'
                    context_menu.style.top = Number(event.pageY)+'px'
                    context_menu.style.left = Number(event.pageX)+'px'
                    context_menu.setAttribute('data-id', li.getAttribute('data-id'))
                    context_menu.setAttribute('full-name', li.textContent)
                    $('body').on('click', function (){
                        li.classList.remove('bordered')
                        document.getElementById('context_side_menu').style.display = 'none'
                    })
                })
                $('#side_menu_content').on('contextmenu', function (event){
                    if (event.target.id === 'side_menu_content'){
                        var context_menu = document.getElementById('context_main_side_menu')
                        context_menu.style.display = 'block'
                        context_menu.style.top = Number(event.pageY)+'px'
                        context_menu.style.left = Number(event.pageX)+'px'
                        if(window.location.pathname !== '/'){
                            $('.custom_list_visible').hide()
                        }
                    }
                    $('body').on('click', function (){
                        document.getElementById('context_main_side_menu').style.display = 'none'
                    })
                })
                $('li').on('click', function (event){
                    if (!$(event.target.closest("img")).hasClass("plus_icon")) {
                        var choiced_id = document.getElementById('choiced_id')
                        var choiced_object = document.getElementById('choiced_object')
                        if (choiced_id.textContent === 'custom_list' || choiced_id.textContent === 'rezhim_list'){
                            $('.choiced').removeClass('choiced');
                            this.classList.add('choiced')
                            choiced_id.textContent = this.getAttribute('data-id')
                            choiced_object.textContent = this.textContent
                            get_table_data()
                            choiced_object.click()
                        }else if (this.classList.contains('choiced')){
                            this.classList.remove('choiced')
                            choiced_id.textContent = ''
                            choiced_object.textContent = ''
                            choiced_object.click()
                        }else {
                            $('.choiced').removeClass('choiced');
                            this.classList.add('choiced')
                            choiced_id.textContent = this.getAttribute('data-id')
                            choiced_object.textContent = this.textContent
                            choiced_object.click()
                        }
                    }
                })
            },
                async: false
        })
        try{
            get_user_custom_list()
        }catch (e) {

        }
        try{
            get_rezhim_list()
        }catch (e) {

        }
    }
    function update_side_object(){
        var open_object = []
        for (var img of document.getElementsByClassName('plus_icon')){
            if (!img.classList.contains('hide')){
                open_object.push(img.getAttribute('data-ul'))
            }
        }
        try {
            var choiced_object = document.getElementById('side_menu_content').getElementsByClassName('choiced')[0].getAttribute('data-id')
        }catch (e){
            var choiced_object = false
        }
        get_side_object()
        for (var opened_img of open_object){
            $(`[data-ul="${opened_img}"]`).click()
        }
        if (choiced_object){
            $(`li[data-id="${choiced_object}"]`).click()
        }
    }


    function rename_object(id, full_name){
        change_header_modal('Переименовать объект "'+full_name+'"')
        $('#text_modal_side_menu').after(`<input class="text-input" id="new_name_object" type="text" placeholder="Введите новое имя..." value="${full_name}">`)
        $('#new_name_object').keydown(function (event) {
            if (event.which == 13){
                $('#submit_button_side_menu').click()
            }
        })
        open_modal_side_menu()
        document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_name(${id})`)
    }
    function store_new_name(id){
        var new_name = document.getElementById('new_name_object').value
        if (!new_name){
            new_name = 'Новый объект'
        }
        $.ajax({
            url: '/store_new_name/'+id+'/'+new_name,
            method: 'GET',
            dataType: 'html',
            success: function(res){
                update_side_object()
                close_modal_side_menu()
            },
            async: false
        })
    }

    function add_new_object(id, full_name){
        if(full_name){
            change_header_modal('Добавление дочернего объекта для "'+full_name+'"')
            document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_object(${id})`)
        }else {
            change_header_modal('Добавление филиала ДО')
            document.getElementById('submit_button_side_menu').setAttribute('onclick', 'store_new_object("false")')
        }
        $('#text_modal_side_menu').after('<input class="text-input" id="name_new_object" type="text" placeholder="Наименование нового объекта...">')
        $('#name_new_object').keydown(function (event) {
            if (event.which == 13){
                $('#submit_button_side_menu').click()
            }
        })
        open_modal_side_menu()
    }
    function store_new_object(parent_id){
        var new_name = document.getElementById('name_new_object').value
        if (!new_name){
            new_name = 'Новый объект'
        }
        $.ajax({
            url: '/store_new_object/'+parent_id+'/'+new_name,
            method: 'GET',
            dataType: 'html',
            success: function(res){
                close_modal_side_menu()
                update_side_object()
            },
            async: false
        })
    }

    function add_new_signal(id, full_name){
        change_header_modal('Добавление сигнала в объект "'+full_name+'"')
        var table = `
        <table id="table_modal_side_menu" class="table_modal">
            <thead>
                <tr>
                    <th rowspan="2">Наименование параметра</th>
                    <th rowspan="2">Ед. изм.</th>
                    <th rowspan="2">OPC-тег</th>
                    <th colspan="3">М АСДУ</th>
                    <th rowspan="2">Отображение в ОЖД</th>
                    <th rowspan="2"><button class="btn" onclick="add_row_new_signal()">Добавить</button></th>
                </tr>
                <tr>
                    <th>РВ</th>
                    <th>2 часа</th>
                    <th>Сутки</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>`
        $('#text_modal_side_menu').after(table)
        open_modal_side_menu()
        document.getElementById('content_modal_side_menu').style.width = '80%'
        document.getElementById('content_modal_side_menu').style.marginLeft = '-40%'
        document.getElementById('submit_button_side_menu').setAttribute('onclick', `store_new_signal(${id})`)
    }
    function add_row_new_signal(){
        var tbody = document.getElementById('table_modal_side_menu').getElementsByTagName('tbody')[0]
        tbody.innerHTML = `<tr>
            <td class="full_name" contenteditable="true"></td>
            <td class="e_unit" contenteditable="true"></td>
            <td class="tag_name" contenteditable="true"></td>
            <td class="guid_masdu_min" contenteditable="true"></td>
            <td class="guid_masdu_hour" contenteditable="true"></td>
            <td class="guid_masdu_day" contenteditable="true"></td>
            <td>
                <label class="switch"><input class="hour_param" type="checkbox"><span class="slider"></span></label>
            </td>
            <td>
                <svg onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm4 12H8v-9h2v9zm6 0h-2v-9h2v9zm.618-15L15 2H9L7.382 4H3v2h18V4z"></path></svg>
            </td>
        </tr>` + tbody.innerHTML
        // $('input[type="checkbox"]').on('click', function (){
        //     if(this.checked){
        //         this.setAttribute('checked', false)
        //     }else {
        //         this.removeAttribute('checked')
        //     }
        // })
    }
    function store_new_signal(parent_id){
        var keys_text = ['full_name', 'e_unit', 'tag_name', 'guid_masdu_min', 'guid_masdu_hour', 'guid_masdu_day']
        var keys_input = ['hour_param']
        var out_data = []
        var arr = new Map()
        for(var key_text of keys_text){
            out_data[key_text] = []
            for (var td of document.getElementById('table_modal_side_menu').getElementsByClassName(key_text)){
                if(key_text !== 'guid_masdu_min' && key_text !== 'guid_masdu_hour' && key_text !== 'guid_masdu_day'){
                    if (td.textContent){
                        out_data[key_text].push(td.textContent)
                    }else {
                        td.style.background = 'red'
                        td.onfocus = function (){
                            this.style.background = ''
                        }
                        return true;
                    }
                }else {
                    out_data[key_text].push(td.textContent)
                }
            }
            arr.set(key_text, out_data[key_text])
        }
        for(var key_input of keys_input){
            out_data[key_input] = []
            for (var td of document.getElementById('table_modal_side_menu').getElementsByClassName(key_input)){
                if (td.checked){
                    out_data[key_input].push(true)
                }else {
                    out_data[key_input].push(false)
                }
            }
            arr.set(key_input, out_data[key_input])
        }
        var data = Object.fromEntries(arr)
        $.ajax({
            url: '/store_new_signal/'+parent_id,
            data: data,
            method: 'POST',
            success: function (res) {
                close_modal_side_menu()
                try {
                    get_table_data()
                }catch (e){

                }
            },
            async: false
        })
    }

    function delete_this_object(id, full_name){
        change_header_modal('Удалить объект "'+full_name+'"?')
        open_modal_side_menu()
        document.getElementById('submit_button_side_menu').setAttribute('onclick', `confirm_delete_object(${id})`)
    }
    function confirm_delete_object(id){
        change_header_modal(`Данная операция влечет за собой удаление дочерних объектов и сигналов.<br> Продолжить?`)
        document.getElementById('submit_button_side_menu').setAttribute('onclick', `delete_object(${id})`)
    }
    function delete_object(parent_id){
        $.ajax({
            url: '/delete_object/'+parent_id,
            method: 'GET',
            dataType: 'html',
            success: function(res){
                close_modal_side_menu()
                update_side_object()
                try {
                    get_table_data()
                }catch (e){

                }
            },
            async: false
        })
    }

    function side_menu_obj_click(){
        var header = document.getElementById('header_doc')
        $('#choiced_object').on('click', function (){ ///Обработка выбора элемента в side menu
            if ($(this).text() !== ''){
                try{
                    header.textContent = header.textContent.split('.')[0] + '. '+ $(this).text()
                    hide_rows($("#choiced_id").text())
                }catch (e){
                    console.log('проверь функцию side_menu_obj_click')
                }
            }else {
                try{
                    header.textContent = header.textContent.split('.')[0] + '.'
                    show_all_rows()
                }catch (e){
                    console.log('проверь функцию side_menu_obj_click')
                }
            }
        })
    }
    function hide_rows(parent_id){
        $.ajax({
            url:'/get_hide_id/'+parent_id,
            type:'GET',
            success:(res)=>{
                var un_visible_rows = Object.values(res)
                var all_trs = document.querySelectorAll('tbody tr')
                for (var one_tr of all_trs){
                    if (un_visible_rows.includes(Number (one_tr.getAttribute('data-id'))) ){ //если данную строку надо скрыть
                        one_tr.classList.remove('hidden_rows')
                    } else {    //если строку надо показать
                        one_tr.classList.add('hidden_rows')
                    }
                }
            }, async: false
        })
        search_object()
    }
    function show_all_rows(){
        $('.hidden_rows').removeClass('hidden_rows');
        search_object()
    }
    function copy_custom_list(){
        change_header_modal('Копирование списков')
        $.ajax({
            url:'/get_custom_list',
            type:'GET',
            success:(res)=>{
                var table = `<table id="table_modal_side_menu" class="table_modal">
                                <thead>
                                   <tr>
                                        <th>Наименование списка</th>
                                        <th>Создатель</th>
                                        <th></th>
                                    </tr>
                                </thead><tbody>`
                for (var list of res){
                    table += `<tr><td>${list['name_list']}</td><td>${list['display_name']}</td><td><button class="btn" data-id="${list['id']}" onclick="post_copy_custom_list(this)">Копировать</button></td></tr>`
                }
                table += `</tbody></table>`

                $('#text_modal_side_menu').after(table)
                open_modal_side_menu()
                document.getElementById('content_modal_side_menu').style.width = '80%'
                document.getElementById('content_modal_side_menu').style.marginLeft = '-40%'
                document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                }, async: false
        })
    }
    function post_copy_custom_list(btn){
        $.ajax({
            url:'/post_copy_custom_list/'+btn.getAttribute('data-id'),
            type:'GET',
            success:(res)=>{
                btn.parentNode.parentNode.parentNode.removeChild(btn.parentNode.parentNode)
                update_side_object()
                try{
                    document.getElementById('custom_img').click()
                }catch (e) {
                    console.log(e)
                }
            }, async: false
        })
    }
</script>
