<div class="messenger_mini" id="messenger_mini" onclick="open_messenger()">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 121, 194, 1);transform: scaleX(-1);msFilter:progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1);"><path d="M12 2C6.486 2 2 5.589 2 10c0 2.908 1.898 5.515 5 6.934V22l5.34-4.005C17.697 17.852 22 14.32 22 10c0-4.411-4.486-8-10-8zm0 14h-.333L9 18v-2.417l-.641-.247C5.67 14.301 4 12.256 4 10c0-3.309 3.589-6 8-6s8 2.691 8 6-3.589 6-8 6z"></path><path d="M7 7h10v2H7zm0 4h7v2H7z"></path></svg>
</div>
<div class="new_group" id="new_group">
    <h3 id="header_message" style="text-align: center; margin: 10px" >Создание группы</h3>
        <input id="group_name" class="input" type="text" style="width: 80%; margin-left: 2%" placeholder="Наименование группы...">
        <input id="search_people_group" oninput="search_people_group(this.value)" class="input" type="text" style="width: 40%; margin-left: 2%; margin-top: 2%" placeholder="Поиск...">
        <div style="width: 100%; overflow-y: auto; height: calc(100% - 7em - 70px); margin-top: 20px">
            <table id="table_for_group" class="dynamicTable" style="table-layout: fixed">
                <thead>
                <tr>
                    <th>Имя пользователя</th>
                    <th style="width: 65px"></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div style="margin-top: 5px; text-align: center">
            <button onclick="save_group()" style="margin: 0" class="btn">Сохранить</button>
            <button onclick="document.getElementById('new_group').style.display = 'none'" style="margin: 0; margin-left: 20px" class="btn">Отменить</button>
        </div>
</div>
<div class="new_group" id="user_info">
    <h3 style="text-align: center; margin: 10px" >Информация</h3>
    <div style="width: 100%; overflow-y: hidden; height: calc(100% - 100px); margin-top: 20px">
        <div class="tab">
            <button class="tablinks" style="width: 50%" onclick="openBlock(this, 'userInfo')">Общие сведения</button>
            <button class="tablinks" style="width: 50%" onclick="openBlock(this, 'userFiles')">Вложения</button>
        </div>

        <div id="userInfo" class="tabcontent" style="display: block">
            <div style="overflow-y: auto; height: 100%">
                <table id="user_info_tab" class="dynamicTable" style="table-layout: auto">
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div id="userFiles" class="tabcontent" style="display: block">
            <div style="overflow-y: auto; height: 100%">
                <table id="user_files_tab" class="dynamicTable" style="table-layout: auto">
                    <thead>
                        <tr>
                            <th>Наименование</th>
                            <th>Дата отправки</th>
                            <th>Размер</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-top: 5px; text-align: center">
        <button onclick="document.getElementById('user_info').style.display = 'none'" style="margin: 0; margin-left: 20px" class="btn">Закрыть</button>
    </div>
</div>
<div class="new_group" id="group_info">
    <h3 style="text-align: center; margin: 10px" >Информация</h3>
    <div style="width: 100%; overflow-y: hidden; height: calc(100% - 100px); margin-top: 20px">
        <div class="tab">
            <button class="tablinks" style="width: 33%" onclick="openBlock(this, 'groupInfo')">Общие сведения</button>
            <button class="tablinks" style="width: 33%" onclick="openBlock(this, 'groupFiles')">Вложения</button>
            <button class="tablinks" style="width: 33%" onclick="openBlock(this, 'groupNewPeople')">Приглашение</button>
        </div>
        <div id="groupInfo" class="tabcontent" style="display: block">
            <div style="overflow-y: auto; height: 100%">
                <table id="group_info_tab" class="dynamicTable" style="table-layout: auto">
                    <thead>
                        <tr>
                            <th>ФИО</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div id="groupFiles" class="tabcontent" style="display: block">
            <div style="overflow-y: auto; height: 100%">
                <table id="group_files_tab" class="dynamicTable" style="table-layout: auto">
                    <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Дата отправки</th>
                        <th>Размер</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div id="groupNewPeople" class="tabcontent" style="display: block">
            <div style="overflow-y: auto; height: 100%">
                <table id="group_files_tab" class="dynamicTable" style="table-layout: auto">
                    <thead>
                    <tr>
                        <th>ФИО</th>
                        <th style="width: 60px"></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-top: 5px; text-align: center">
        <button onclick="document.getElementById('group_info').style.display = 'none'" style="margin: 0; margin-left: 20px" class="btn">Закрыть</button>
    </div>
</div>
<div class="new_group" id="new_message">
    <h3 style="text-align: center; margin: 10px" >Новое сообщение</h3>
    <input oninput="search_new_message(this.value)" class="input" type="text" style="width: 40%; margin-left: 2%; margin-top: 2%" placeholder="Поиск...">
    <div style="width: 100%; overflow-y: auto; height: calc(100% - 5em - 70px); margin-top: 20px">
        <table id="table_for_users" class="dynamicTable" style="table-layout: fixed">
            <thead>
            <tr>
                <th>Имя пользователя</th>
                <th style="width: 65px"></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div style="margin-top: 5px; text-align: center">
        <button onclick="document.getElementById('new_message').style.display = 'none'" style="margin: 0; margin-left: 20px" class="btn">Отменить</button>
    </div>
</div>
<div class="messenger" id="messenger">
    <div class="people_block">
        <div class="search_block">
            <div class="search_input">
                <input class="input" type="text" style="width: calc(100% - 120px)" id="search_people" oninput="search_people(this.value)" placeholder="Поиск пользователя...">
                <img onclick="create_new_msg()" data-toggle="tooltip" title="Новое сообщение" class="hover_img" src="/assets/img/edit.svg">
                <img onclick="create_group()" data-toggle="tooltip" title="Новая группа" class="hover_img" src="/assets/img/add_group.svg">
            </div>
        </div>
        <div class="people" id="people">

        </div>
    </div>
    <div id="name_recipient_div" class="name_recipient">
        <div class="close_messenger" data-toggle="tooltip" title="Закрыть" onclick="close_messenger()">
            <p style="color: white; text-align: center; vertical-align: center; font-weight: bold">&#10006;</p>
        </div>
        <div class="name" style="text-align: center">
            <p style="color: white; width: auto; margin: 5px; font-size: 14px" id="name_people"></p>
            <p style="color: white; width: auto; margin: 0px; font-size: 14px" id="last_visit"></p>
        </div>
        <div class="question">
            <img class="hover_img" onclick="open_info_chat()" data-toggle="tooltip" title="Сведения" src="/assets/img/question.svg">
        </div>
    </div>
    <div class="chat_div">
        <div class="text_chat" id="chat_window">
            <table style="width: 100%">
                <tbody class="body_chat" id="body_chat">

                </tbody>
            </table>
        </div>
        <div class="input_message">
            <div style="width: 70%; float: left; height: 100%; position:relative; vertical-align: center">
                <input class="input" type="text" id="sender_text" style="width: 100%; float: left; margin-left: 2%; position: absolute; top: 50%; transform: translate(0, -50%)" placeholder="Новое сообщение...">
            </div>
            <div class="img_message" style="width: 10%; float: right; height: 100%; text-align: center" onclick="send_messege()">
                <svg xmlns="http://www.w3.org/2000/svg" width="60%" height="80%" viewBox="0 0 24 24" style="fill: rgba(186, 196, 245, 1);transform: ;msFilter:; margin-top: 10%"><path d="m21.426 11.095-17-8A.999.999 0 0 0 3.03 4.242L4.969 12 3.03 19.758a.998.998 0 0 0 1.396 1.147l17-8a1 1 0 0 0 0-1.81zM5.481 18.197l.839-3.357L12 12 6.32 9.16l-.839-3.357L18.651 12l-13.17 6.197z"></path></svg>
            </div>
            <div class="img_message" style="width: 10%; float: right; height: 100%; text-align: center" onclick="file_open()">
                <svg xmlns="http://www.w3.org/2000/svg" width="60%" height="80%" viewBox="0 0 24 24" style="fill: rgba(186, 196, 245, 1); margin-top: 10%;transform: rotate(90deg);msFilter:progid:DXImageTransform.Microsoft.BasicImage(rotation=1);"><path d="M17.004 5H9c-1.838 0-3.586.737-4.924 2.076C2.737 8.415 2 10.163 2 12c0 1.838.737 3.586 2.076 4.924C5.414 18.263 7.162 19 9 19h8v-2H9c-1.303 0-2.55-.529-3.51-1.49C4.529 14.55 4 13.303 4 12c0-1.302.529-2.549 1.49-3.51C6.45 7.529 7.697 7 9 7h8V6l.001 1h.003c.79 0 1.539.314 2.109.886.571.571.886 1.322.887 2.116a2.966 2.966 0 0 1-.884 2.11A2.988 2.988 0 0 1 17 13H9a.99.99 0 0 1-.698-.3A.991.991 0 0 1 8 12c0-.252.11-.507.301-.698A.987.987 0 0 1 9 11h8V9H9c-.79 0-1.541.315-2.114.889C6.314 10.461 6 11.211 6 12s.314 1.54.888 2.114A2.974 2.974 0 0 0 9 15h8.001a4.97 4.97 0 0 0 3.528-1.473 4.967 4.967 0 0 0-.001-7.055A4.95 4.95 0 0 0 17.004 5z"></path></svg>
            </div>
{{--            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('Пожар', this, 'rgba(228, 8, 8, 1)')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(228, 8, 8, 1);transform: ;msFilter:;"><path d="M16.5 8c0 1.5-.5 3.5-2.9 4.3.7-1.7.8-3.4.3-5-.7-2.1-3-3.7-4.6-4.6-.4-.3-1.1.1-1 .7 0 1.1-.3 2.7-2 4.4C4.1 10 3 12.3 3 14.5 3 17.4 5 21 9 21c-4-4-1-7.5-1-7.5.8 5.9 5 7.5 7 7.5 1.7 0 5-1.2 5-6.4 0-3.1-1.3-5.5-2.4-6.9-.3-.5-1-.2-1.1.3"></path></svg></button>--}}
{{--            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('Тренировка', this, 'rgba(186, 196, 245, 1)')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(186, 196, 245, 1);transform: ;msFilter:;"><circle cx="17" cy="4" r="2"></circle><path d="M15.777 10.969a2.007 2.007 0 0 0 2.148.83l3.316-.829-.483-1.94-3.316.829-1.379-2.067a2.01 2.01 0 0 0-1.272-.854l-3.846-.77a1.998 1.998 0 0 0-2.181 1.067l-1.658 3.316 1.789.895 1.658-3.317 1.967.394L7.434 17H3v2h4.434c.698 0 1.355-.372 1.715-.971l1.918-3.196 5.169 1.034 1.816 5.449 1.896-.633-1.815-5.448a2.007 2.007 0 0 0-1.506-1.33l-3.039-.607 1.772-2.954.417.625z"></path></svg></button>--}}
{{--            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('Происшествие', this, 'rgba(110, 251, 97, 1)')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(110, 251, 97, 1);transform: ;msFilter:;"><path d="M9.912 8.531 7.121 3.877a.501.501 0 0 0-.704-.166 9.982 9.982 0 0 0-4.396 7.604.505.505 0 0 0 .497.528l5.421.09a4.042 4.042 0 0 1 1.973-3.402zm8.109-4.51a.504.504 0 0 0-.729.151L14.499 8.83a4.03 4.03 0 0 1 1.546 3.112l5.419-.09a.507.507 0 0 0 .499-.53 9.986 9.986 0 0 0-3.942-7.301zm-4.067 11.511a4.015 4.015 0 0 1-1.962.526 4.016 4.016 0 0 1-1.963-.526l-2.642 4.755a.5.5 0 0 0 .207.692A9.948 9.948 0 0 0 11.992 22a9.94 9.94 0 0 0 4.396-1.021.5.5 0 0 0 .207-.692l-2.641-4.755z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>--}}
{{--            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('Дисп. задание', this, 'rgb(34, 139, 34)')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgb(34, 139, 34);transform: ;msFilter:;"><path d="M19 2.01H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.998 5 19.815 5 19.01c0-.101.009-.191.024-.273.112-.575.583-.717.987-.727H20c.018 0 .031-.009.049-.01H21V4.01c0-1.103-.897-2-2-2zm0 14H5v-11c0-.806.55-.988 1-1h7v7l2-1 2 1v-7h2v12z"></path></svg></button>--}}
{{--            <button class="button button1" style="margin-left: 15px; display: none" onclick="set_type_messege('-', this)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(12, 12, 12, 1);transform: ;msFilter:;"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg></button>--}}
        </div>
    </div>
</div>

<form id="form_upload_chat" method="POST" enctype="multipart/form-data" style="display: none">
    <input type="file" onchange="upload_file()" name="myfile" id="button_form_file" multiple="multiple">
</form>
<div style="display: none">
    <iframe id="iframe_message"></iframe>
</div>

<script>

    $(document).ready(function() {
        //Переместил в функцию open_messenger чтоб не тормозить
        // $('#sender_text').keydown(function(e) {if(e.keyCode === 13) {send_messege()}});
        // $('#chat_window').scroll(function (){if (this.scrollTop == 0){old_message()}})
    });

        /////Часть отображения панели юзеров
    function get_user_block(){
        $.ajax({
            url: '/get_user_block',
            method: 'GET',
            async: false,
            success: function(data){
                var selected_people, group_sel, id_sel
                try{
                    selected_people = $('.selected_people')[0]
                    group_sel = selected_people.getAttribute('data-group')
                    id_sel = selected_people.getAttribute('data-id')
                }catch (e) {
                    selected_people = document.getElementById('name_recipient_div')
                    group_sel = selected_people.getAttribute('data-group')
                    id_sel = selected_people.getAttribute('data-id')
                }
                var people_main = document.getElementById('people')
                people_main.innerText = ''
                var today = data['today'];
                data = Object.entries(data)
                for (var i=0; i<data.length-1; i++){
                    var div = document.createElement('div')
                    div.setAttribute('data-id', data[i][1]['recipient_id'])
                    div.setAttribute('data-group', data[i][1]['is_group'])
                    div.classList.add('one_people')
                    if (data[i][1]['create_date'].split(' ')[0] === today){
                        data[i][1]['create_date'] = data[i][1]['create_date'].split(' ')[1].substr(0, 5)
                    }else {
                        var date = data[i][1]['create_date'].split(' ')[0].split('-')
                        data[i][1]['create_date'] = date[2]+'.'+date[1]
                    }
                    if (data[i][1]['is_group'] === 'true'){
                        data[i][1]['display_name'] = `<img src="/assets/img/group_img.svg">${data[i][1]['display_name']}`
                    }else {
                        data[i][1]['display_name'] = `${data[i][1]['display_name']}`
                    }
                    if (Number(data[i][1]['sum_unread'])>0){
                        data[i][1]['sum_unread'] = `<p class="unread">${data[i][1]['sum_unread']}</p>`
                    }else{
                        data[i][1]['sum_unread'] = ''
                    }
                    div.innerHTML = `<table class="table_one_people">
                            <tbody>
                            <tr>
                                <td class="nick_name">${data[i][1]['display_name']}</td>
                                <td>${data[i][1]['create_date']}</td>
                            </tr>
                            <tr>
                                <td>${data[i][1]['message_body']}</td>
                                <td>${data[i][1]['sum_unread']}</td>
                            </tr>
                            </tbody></table>`
                    people_main.appendChild(div)
                }
                $('.one_people').on('click', function (){open_chat(this)})
                try{
                    $(`.one_people[data-group = "${group_sel}"][data-id = "${id_sel}"]`).addClass('selected_people')
                }catch (e) {
                }
                search_people(document.getElementById('search_people').value)
            }
        })
    }

    /////Поиск пользователя
    function search_people(search_text){
        search_text = new RegExp(search_text, 'i');
        var tablePeople = $('.nick_name')
        for(var i=0; i<tablePeople.length; i++){
            if (!tablePeople[i].classList.contains('hidden_rows')){
                if (tablePeople[i].textContent.match(search_text)){
                    tablePeople[i].parentNode.parentNode.parentNode.parentNode.style.display = ''
                }else {
                    tablePeople[i].parentNode.parentNode.parentNode.parentNode.style.display = 'none'
                }
            }
        }
    }

    /////Часть по информации
    function open_info_chat(){
        var group = document.getElementById('name_recipient_div').getAttribute('data-group')
        if (group === 'true'){
            document.getElementById('group_info').style.display = 'block'
            openBlock($('#group_info .tablinks')[0], 'groupInfo')
        }else{
            document.getElementById('user_info').style.display = 'block'
            openBlock($('#user_info .tablinks')[0], 'userInfo')
        }
    }
    function openBlock(evt, typeInfo) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(typeInfo).style.display = "block";
        evt.className += " active";
        var name_div = document.getElementById('name_recipient_div')
        switch (typeInfo){
            case 'userInfo':
                $.ajax({
                    url: '/get_user_info/'+name_div.getAttribute('data-id'),
                    method: 'GET',
                    success: function(data){
                        // data = Object.entries(data)
                        var body = document.getElementById('userInfo').getElementsByTagName('tbody')[0]
                        body.innerText = ''
                        var name_param = ['ФИО', 'Подразделение', 'Должность', 'e-mail', 'Телефон']
                        var variable = ['displayname', 'departmentnumber', 'employeetype', 'mail', 'telephonenumber']
                        for (var i = 0; i<5; i++){
                            var tr = document.createElement('tr')
                            tr.innerHTML += `<td>${name_param[i]}</td>`
                            try{
                                tr.innerHTML += `<td>${data[variable[i]][0]}</td>`
                            }catch (e) {
                                tr.innerHTML += `<td>н/д</td>`
                            }
                            body.appendChild(tr)
                        }
                    }
                })
                break;
            case 'userFiles':
                $.ajax({
                    url: '/get_user_files/'+name_div.getAttribute('data-id'),
                    method: 'GET',
                    success: function(data){
                        var body = document.getElementById('userFiles').getElementsByTagName('tbody')[0]
                        body.innerText = ''
                        for (var row of data){
                            var tr = document.createElement('tr')
                            tr.innerHTML += `<td>${row['filename']}</td>`
                            tr.innerHTML += `<td>${row['time']}</td>`
                            if (Number(row['size']) > 1000000){
                                row['size'] = (Number(row['size'])/1000000).toFixed(2) + 'MB'
                            }else if(Number(row['size']) > 1000){
                                row['size'] = (Number(row['size'])/1000).toFixed(2) + ' KB'
                            }else {
                                row['size'] = Number(row['size']) + ' B'
                            }
                            tr.innerHTML += `<td>${row['size']}</td>`
                            tr.innerHTML += `<td onclick="download_file_chat('${row['uid']}')"><img class="hover_img" src="/assets/img/download.svg"></td>`
                            body.appendChild(tr)
                        }
                    }
                })
                break;
            case 'groupFiles':
                $.ajax({
                    url: '/get_group_files/'+name_div.getAttribute('data-id'),
                    method: 'GET',
                    success: function(data){
                        var body = document.getElementById('groupFiles').getElementsByTagName('tbody')[0]
                        body.innerText = ''
                        for (var row of data){
                            var tr = document.createElement('tr')
                            tr.innerHTML += `<td>${row['filename']}</td>`
                            tr.innerHTML += `<td>${row['time']}</td>`
                            if (Number(row['size']) > 1000000){
                                row['size'] = (Number(row['size'])/1000000).toFixed(2) + 'MB'
                            }else if(Number(row['size']) > 1000){
                                row['size'] = (Number(row['size'])/1000).toFixed(2) + ' KB'
                            }else {
                                row['size'] = Number(row['size']) + ' B'
                            }
                            tr.innerHTML += `<td>${row['size']}</td>`
                            tr.innerHTML += `<td onclick="download_file_chat('${row['uid']}')"><img class="hover_img" src="/assets/img/download.svg"></td>`
                            body.appendChild(tr)
                        }
                    }
                })
                break;
            case 'groupInfo':
                $.ajax({
                    url: '/get_group_info/'+name_div.getAttribute('data-id'),
                    method: 'GET',
                    success: function(data){
                        var body = document.getElementById('groupInfo').getElementsByTagName('tbody')[0]
                        body.innerText = ''
                        for (var row of data){
                            var tr = document.createElement('tr')
                            tr.setAttribute('data-id', row['user_id'])
                            tr.innerHTML += `<td>${row['nameuser']}</td>`
                            if (row['text']){
                                tr.innerHTML += `<td>Создатель</td>`
                            }else{
                                tr.innerHTML += `<td></td>`
                            }
                            if (row['delete']){
                                tr.innerHTML += `<td><img onclick="delete_from_group(this.parentNode.parentNode)" class="hover_img" src="/assets/img/trash.svg"></td>`
                            }else {
                                tr.innerHTML += `<td><img style="opacity: 0.5" src="/assets/img/trash.svg"></td>`
                            }
                            body.appendChild(tr)
                        }
                        if (row['delete']){
                            var tr = document.createElement('tr')
                            tr.innerHTML += `<td colspan="2"><b>Удалить группу</b></td>`
                            tr.innerHTML += `<td><img onclick="delete_group()" class="hover_img" src="/assets/img/trash.svg"></td>`
                            body.appendChild(tr)
                        }
                    }
                })
                break;
            case 'groupNewPeople':
                $.ajax({
                    url: '/add_user_to_group/'+name_div.getAttribute('data-id'),
                    method: 'GET',
                    success: function(data){
                        var body = document.getElementById('groupNewPeople').getElementsByTagName('tbody')[0]
                        body.innerText = ''
                        if (data === 'false'){
                            var tr = document.createElement('tr')
                            tr.innerHTML += `<td colspan="2">Добавление доступно только создателю</td>`
                            body.appendChild(tr)
                        }else {
                            for (var row of data){
                                var tr = document.createElement('tr')
                                tr.innerHTML += `<td>${row['display_name']}</td>`
                                tr.innerHTML += `<td><img onclick="add_user_to_group(${row['id']})" class="hover_img" src="/assets/img/add_plus_icon.svg"></td>`
                                body.appendChild(tr)
                            }
                        }
                    }
                })
                break;
        }
    }
    function delete_from_group(tr){
        var id = tr.getAttribute('data-id')
        if (tr.getElementsByTagName('td')[1].textContent !== 'Создатель'){
            $.ajax({
                url: '/delete_user_from_group/'+id+'/'+document.getElementById('name_recipient_div').getAttribute('data-id'),
                method: 'get',
                success: function (res) {
                    openBlock($('#group_info .tablinks')[0], 'groupInfo')
                }
            })
        }
    }
    function delete_group(){
        $.ajax({
            url: '/delete_group/'+document.getElementById('name_recipient_div').getAttribute('data-id'),
            method: 'get',
            success: function (res) {
                get_user_block()
                $('.one_people')[0].click()
            }
        })
    }
    /////Часть по созданию группы
    function search_people_group(search_text){
        search_text = new RegExp(search_text, 'i');
        var tablePeople = document.getElementById('table_for_group').getElementsByTagName('tbody')[0]
        var allTableRow = tablePeople.getElementsByTagName('tr')
        for(var i=0; i<allTableRow.length; i++){
            if (!allTableRow[i].classList.contains('hidden_rows')){
                if (allTableRow[i].getElementsByTagName('td')[0].textContent.match(search_text)){
                    allTableRow[i].style.display = ''
                }else {
                    allTableRow[i].style.display = 'none'
                }
            }
        }
    }
    function create_group(){
        $.ajax({
            url: '/get_all_users',
            method: 'GET',
            success: function(res){
                var tableBody = document.getElementById('table_for_group').getElementsByTagName('tbody')[0]
                tableBody.innerText = ''
                for (var row of res){
                    var tr = document.createElement('tr')
                    tr.innerHTML = `<td data-id="${row['id']}">${row['display_name']}</td>`
                    tr.innerHTML += `<td><label class="switch"><input data-id="${row['id']}" type="checkbox"><span class="slider"></span></label></td>`
                    tableBody.appendChild(tr)
                }
            }
        })
        document.getElementById('group_name').value = ''
        document.getElementById('search_people_group').value = ''
        document.getElementById('header_message').textContent = 'Создание группы'
        document.getElementById('new_group').style.display = 'block'
        document.getElementById('user_info').style.display = 'none'
        document.getElementById('group_info').style.display = 'none'
        document.getElementById('new_message').style.display = 'none'
    }
    function save_group(){
        var arr = new Map()
        arr.set('name_group', document.getElementById('group_name').value)
        var ids = []
        for (var checkbox of $('#table_for_group input:checkbox:checked')){
            ids.push(checkbox.getAttribute('data-id'))
        }
        arr.set('users', ids)
        $.ajax({
            url: '/save_group',
            method: 'POST',
            data: Object.fromEntries(arr),
            success: function (res) {
                if (res !== 'false'){
                    document.getElementById('header_message').textContent = res
                }else {
                    get_user_block()
                    $('.one_people')[0].click()
                }
            }
        })
    }
    function add_user_to_group(id_user){
        $.ajax({
            url: '/save_new_member/'+id_user+'/'+document.getElementById('name_recipient_div').getAttribute('data-id'),
            method: 'GET',
            success: function(res){
                openBlock($('#group_info .tablinks')[2], 'groupNewPeople')
            }
        })
    }
    /////Часть по созданию нового чата
    function create_new_msg(){
        $.ajax({
            url: '/get_all_users',
            method: 'GET',
            success: function(res){
                var tableBody = document.getElementById('table_for_users').getElementsByTagName('tbody')[0]
                tableBody.innerText = ''
                for (var row of res){
                    var tr = document.createElement('tr')
                    tr.innerHTML = `<td>${row['display_name']}</td>`
                    tr.innerHTML += `<td><img class="hover_img" onclick="open_new_chat(${row['id']}, '${row['display_name']}')" src="/assets/img/edit.svg"></td>`
                    tableBody.appendChild(tr)
                }
            }
        })
        document.getElementById('search_people_group').value = ''
        document.getElementById('new_group').style.display = 'none'
        document.getElementById('user_info').style.display = 'none'
        document.getElementById('group_info').style.display = 'none'
        document.getElementById('new_message').style.display = 'block'
    }
    function open_new_chat(id, name){
        $('.selected_people').removeClass('selected_people')
        var name_block = document.getElementById('name_recipient_div')
        name_block.setAttribute('data-id', id)
        name_block.setAttribute('data-group', 'false')
        document.getElementById('name_people').textContent = name
        document.getElementById('new_message').style.display = 'none'
        document.getElementById('search_people').value = ''
        search_people('')
        create_chat()
        try{
            $(`.one_people[data-group = "false"][data-id = "${id}"]`).addClass('selected_people')
        }catch (e) {

        }
    }
    function search_new_message(search_text){
        search_text = new RegExp(search_text, 'i');
        var tablePeople = document.getElementById('table_for_users').getElementsByTagName('tbody')[0]
        var allTableRow = tablePeople.getElementsByTagName('tr')
        for(var i=0; i<allTableRow.length; i++){
            if (!allTableRow[i].classList.contains('hidden_rows')){
                if (allTableRow[i].getElementsByTagName('td')[0].textContent.match(search_text)){
                    allTableRow[i].style.display = ''
                }else {
                    allTableRow[i].style.display = 'none'
                }
            }
        }
    }
    /////Отправка сообщения
    function send_messege(){
        if (document.getElementById('sender_text').value !== ''){
            var name_div = document.getElementById('name_recipient_div')
            var arr = new Map()
            arr.set('id', name_div.getAttribute('data-id'))
            arr.set('group', name_div.getAttribute('data-group'))
            arr.set('text', document.getElementById('sender_text').value)
            $.ajax({
                url: '/new_message',
                method: 'POST',
                data: Object.fromEntries(arr),
                async: false,
                success: function (res) {
                    document.getElementById('sender_text').value = ''
                    new_message(false)
                    var chat_window = document.getElementById('chat_window')
                    chat_window.scrollTo(0, chat_window.scrollHeight)
                }
            })
        }
    }

    /////Непосредственно открытие чата, обновления и получения исторических данных
    function open_chat(one_people_block){
        $('.selected_people').removeClass('selected_people')
        one_people_block.classList.add('selected_people')
        document.getElementById('name_people').textContent = one_people_block.getElementsByClassName('nick_name')[0].textContent
        var name_block = document.getElementById('name_recipient_div')
        name_block.setAttribute('data-id', one_people_block.getAttribute('data-id'))
        name_block.setAttribute('data-group', one_people_block.getAttribute('data-group'))
        create_chat()
        document.getElementById('user_info').style.display = 'none'
        document.getElementById('new_group').style.display = 'none'
        document.getElementById('group_info').style.display = 'none'
        document.getElementById('new_message').style.display = 'none'
    }
    function create_chat(){
        var name_div = document.getElementById('name_recipient_div')
        $.ajax({
            url: '/get_chat/'+name_div.getAttribute('data-id')+'/'+name_div.getAttribute('data-group'),
            method: 'GET',
            success: function(data){
                var body_chat = document.getElementById('body_chat')
                body_chat.innerText = ''
                for(var day of Object.keys(data)){
                    var date_tr = document.createElement('tr')
                    date_tr.setAttribute('data-day', day)
                    date_tr.style.fontSize = '12px'
                    date_tr.style.fontWeight = 'bolder'
                    date_tr.style.textAlign = 'center'
                    date_tr.innerHTML = `<td>${day}</td>`
                    body_chat.appendChild(date_tr)
                    for (var row of data[day]){
                        var tr = document.createElement('tr')
                        tr.setAttribute('data-id', row['message_id'])
                        if (row['mine_message']){
                            if (!row['filename']){
                                tr.innerHTML = `<td class="mine_td">
                                        <p class="info_mine_p">${row['time']}</p>
                                        <p class="mine_text" data-id="${row['message_id']}">${row['message']}</p>
                                    </td>`
                            }else{
                                if (Number(row['filesize']) > 1000000){
                                    row['filesize'] = (Number(row['filesize'])/1000000).toFixed(2) + 'MB'
                                }else if(Number(row['filesize']) > 1000){
                                    row['filesize'] = (Number(row['filesize'])/1000).toFixed(2) + ' KB'
                                }else {
                                    row['filesize'] = Number(row['filesize']) + ' B'
                                }
                                tr.innerHTML = `<td class="mine_td">
                                                    <p class="info_mine_p">${row['time']}</p>
                                                    <div class="mine_text" onclick="download_file_chat('${row['fileuid']}')">
                                                        <div class="messege_img">
                                                            <img class="hover_img" src="/assets/img/doc.svg">
                                                        </div>
                                                        <div class="img_name">
                                                            <b>${row['filename']}</b><br>
                                                            <i>${row['filesize']}</i>
                                                        </div>
                                                    </div>
                                                </td>`
                            }
                        }else {
                            if (!row['filename']){
                                tr.innerHTML = `<td class="other_td">
                                        <p class="other_text" data-id="${row['message_id']}">${row['message']}</p>
                                        <p class="info_other_p">${row['time']}</p>
                                        <p class="info_other_p name_hidden">${row['creator']}</p>
                                    </td>`
                            }else {
                                if (Number(row['filesize']) > 1000000){
                                    row['filesize'] = (Number(row['filesize'])/1000000).toFixed(2) + 'MB'
                                }else if(Number(row['filesize']) > 1000){
                                    row['filesize'] = (Number(row['filesize'])/1000).toFixed(2) + ' KB'
                                }else {
                                    row['filesize'] = Number(row['filesize']) + ' B'
                                }
                                tr.innerHTML = `<td class="other_td">
                                                    <div class="mine_text" onclick="download_file_chat('${row['fileuid']}')">
                                                        <div class="messege_img">
                                                            <img class="hover_img" src="/assets/img/doc.svg">
                                                        </div>
                                                        <div class="img_name">
                                                            <b>${row['filename']}</b><br>
                                                            <i>${row['filesize']}</i>
                                                        </div>
                                                    </div>
                                                    <p class="info_other_p">${row['time']}</p>
                                                    <p class="info_other_p name_hidden">${row['creator']}</p>
                                                </td>`
                            }

                        }
                        body_chat.appendChild(tr)
                    }
                }
                var chat_window = document.getElementById('chat_window')
                chat_window.scrollTo(0, chat_window.scrollHeight)
            }
        })
    }
    function old_message(){
        var name_div = document.getElementById('name_recipient_div')
        var body_chat = document.getElementById('body_chat')
        var last_row = body_chat.getElementsByTagName('tr')[1]
        $.ajax({
            url: '/get_old_chat/'+name_div.getAttribute('data-id')+'/'+name_div.getAttribute('data-group')+'/'+last_row.getAttribute('data-id'),
            method: 'GET',
            async: false,
            success: function(data){
                var begin_position_last_row = last_row.getBoundingClientRect()
                for(var day of Object.keys(data)){
                    $(`#chat_window [data-day = ${day}]`).remove()
                    var date_tr = document.createElement('tr')
                    date_tr.setAttribute('data-day', day)
                    date_tr.style.fontSize = '12px'
                    date_tr.style.fontWeight = 'bolder'
                    date_tr.style.textAlign = 'center'
                    date_tr.innerHTML = `<td>${day}</td>`
                    body_chat.insertBefore(date_tr, last_row)
                    for (var row of data[day]){
                        var tr = document.createElement('tr')
                        tr.setAttribute('data-id', row['message_id'])
                        if (row['mine_message']){
                            if (!row['filename']){
                                tr.innerHTML = `<td class="mine_td">
                                        <p class="info_mine_p">${row['time']}</p>
                                        <p class="mine_text" data-id="${row['message_id']}">${row['message']}</p>
                                    </td>`
                            }else{
                                if (Number(row['filesize']) > 1000000){
                                    row['filesize'] = (Number(row['filesize'])/1000000).toFixed(2) + 'MB'
                                }else if(Number(row['filesize']) > 1000){
                                    row['filesize'] = (Number(row['filesize'])/1000).toFixed(2) + ' KB'
                                }else {
                                    row['filesize'] = Number(row['filesize']) + ' B'
                                }
                                tr.innerHTML = `<td class="mine_td">
                                                    <p class="info_mine_p">${row['time']}</p>
                                                    <div class="mine_text" onclick="download_file_chat('${row['fileuid']}')">
                                                        <div class="messege_img">
                                                            <img class="hover_img" src="/assets/img/doc.svg">
                                                        </div>
                                                        <div class="img_name">
                                                            <b>${row['filename']}</b><br>
                                                            <i>${row['filesize']}</i>
                                                        </div>
                                                    </div>
                                                </td>`
                            }
                        }else {
                            if (!row['filename']){
                                tr.innerHTML = `<td class="other_td">
                                        <p class="other_text" data-id="${row['message_id']}">${row['message']}</p>
                                        <p class="info_other_p">${row['time']}</p>
                                        <p class="info_other_p name_hidden">${row['creator']}</p>
                                    </td>`
                            }else {
                                if (Number(row['filesize']) > 1000000){
                                    row['filesize'] = (Number(row['filesize'])/1000000).toFixed(2) + 'MB'
                                }else if(Number(row['filesize']) > 1000){
                                    row['filesize'] = (Number(row['filesize'])/1000).toFixed(2) + ' KB'
                                }else {
                                    row['filesize'] = Number(row['filesize']) + ' B'
                                }
                                tr.innerHTML = `<td class="other_td">
                                                    <div class="mine_text" onclick="download_file_chat('${row['fileuid']}')">
                                                        <div class="messege_img">
                                                            <img class="hover_img" src="/assets/img/doc.svg">
                                                        </div>
                                                        <div class="img_name">
                                                            <b>${row['filename']}</b><br>
                                                            <i>${row['filesize']}</i>
                                                        </div>
                                                    </div>
                                                    <p class="info_other_p">${row['time']}</p>
                                                    <p class="info_other_p name_hidden">${row['creator']}</p>
                                                </td>`
                            }

                        }
                        body_chat.insertBefore(tr, last_row)
                    }
                }
                var end_position_last_row = last_row.getBoundingClientRect()
                var chat_window = document.getElementById('chat_window')
                chat_window.scrollTo(0, end_position_last_row.y - begin_position_last_row.y)
            }
        })
    }
    function new_message(type_sync){
        var name_div = document.getElementById('name_recipient_div')
        var body_chat = document.getElementById('body_chat')
        var first_row = body_chat.getElementsByTagName('tr')[body_chat.getElementsByTagName('tr').length - 1]
        try{
            var start_id = first_row.getAttribute('data-id')
        }catch (e){
            var start_id = 0
        }
        $.ajax({
            url: '/get_new_chat/'+name_div.getAttribute('data-id')+'/'+name_div.getAttribute('data-group')+'/'+start_id,
            method: 'GET',
            async: type_sync,
            success: function(data){
                for(var day of Object.keys(data)){
                    for (var row of data[day]){
                        var tr = document.createElement('tr')
                        tr.setAttribute('data-id', row['message_id'])
                        if (row['mine_message']){
                            if (!row['filename']){
                                tr.innerHTML = `<td class="mine_td">
                                        <p class="info_mine_p">${row['time']}</p>
                                        <p class="mine_text" data-id="${row['message_id']}">${row['message']}</p>
                                    </td>`
                            }else{
                                if (Number(row['filesize']) > 1000000){
                                    row['filesize'] = (Number(row['filesize'])/1000000).toFixed(2) + 'MB'
                                }else if(Number(row['filesize']) > 1000){
                                    row['filesize'] = (Number(row['filesize'])/1000).toFixed(2) + ' KB'
                                }else {
                                    row['filesize'] = Number(row['filesize']) + ' B'
                                }
                                tr.innerHTML = `<td class="mine_td">
                                                    <p class="info_mine_p">${row['time']}</p>
                                                    <div class="mine_text" onclick="download_file_chat('${row['fileuid']}')">
                                                        <div class="messege_img">
                                                            <img class="hover_img" src="/assets/img/doc.svg">
                                                        </div>
                                                        <div class="img_name">
                                                            <b>${row['filename']}</b><br>
                                                            <i>${row['filesize']}</i>
                                                        </div>
                                                    </div>
                                                </td>`
                            }
                        }else {
                            if (!row['filename']){
                                tr.innerHTML = `<td class="other_td">
                                        <p class="other_text" data-id="${row['message_id']}">${row['message']}</p>
                                        <p class="info_other_p">${row['time']}</p>
                                        <p class="info_other_p name_hidden">${row['creator']}</p>
                                    </td>`
                            }else {
                                if (Number(row['filesize']) > 1000000){
                                    row['filesize'] = (Number(row['filesize'])/1000000).toFixed(2) + 'MB'
                                }else if(Number(row['filesize']) > 1000){
                                    row['filesize'] = (Number(row['filesize'])/1000).toFixed(2) + ' KB'
                                }else {
                                    row['filesize'] = Number(row['filesize']) + ' B'
                                }
                                tr.innerHTML = `<td class="other_td">
                                                    <div class="mine_text" onclick="download_file_chat('${row['fileuid']}')">
                                                        <div class="messege_img">
                                                            <img class="hover_img" src="/assets/img/doc.svg">
                                                        </div>
                                                        <div class="img_name">
                                                            <b>${row['filename']}</b><br>
                                                            <i>${row['filesize']}</i>
                                                        </div>
                                                    </div>
                                                    <p class="info_other_p">${row['time']}</p>
                                                    <p class="info_other_p name_hidden">${row['creator']}</p>
                                                </td>`
                            }
                        }
                        body_chat.appendChild(tr)
                    }
                }
                if (Object.keys(data).length > 0){
                    var chat_window = document.getElementById('chat_window')
                    chat_window.scrollTo(0, chat_window.scrollHeight)
                }
            }
        })
    }
    function update_chat(){
        if ($('.selected_people').length > 0){
            new_message(true)
        }
    }

    /////Часть по выгрузке/загрузке файла
    function file_open(){
        document.getElementById('button_form_file').click()
    }
    function upload_file(){
        if(($('#button_form_file')[0].files).length !=0){
            var name_div = document.getElementById('name_recipient_div')
            var formData = new FormData();
            $.each($('#button_form_file')[0].files, function(i, file){
                formData.append("file[" + i + "]", file);
            });
            $.ajax({
                type: "POST",
                url: '/upload_file_chat/'+name_div.getAttribute('data-group')+'/'+name_div.getAttribute('data-id'),
                cache:false,
                dataType:"json",
                contentType: false,
                processData: false,
                async: false,
                data: formData,
                success: function(){
                    new_message(false)
                }
            });
        }
    }

    function download_file_chat(name_file){
        document.getElementById('iframe_message').setAttribute('src', '/download_file_chat/'+name_file)
    }

    var updatePeople, updateChat
    ////Открыть закрыть сам чат
    function close_messenger(){
        document.getElementById('new_group').style.display = 'none'
        document.getElementById('messenger').style.display = 'none'
        clearInterval(updatePeople)
        clearInterval(updateChat)
    }
    function open_messenger(){
        $('#sender_text').keydown(function(e) {if(e.keyCode === 13) {send_messege()}});
        $('#chat_window').scroll(function (){if (this.scrollTop == 0){old_message()}})
        document.getElementById('messenger').style.display = 'block'
        get_user_block()
        update_chat()
        clearInterval(updatePeople)
        clearInterval(updateChat)
        updatePeople = setInterval(get_user_block, 5000)
        updateChat = setInterval(update_chat, 5000)
        if ($('.selected_people').length == 0){
            $('.one_people ')[0].click()
        }
    }

</script>

<style>
    .unread{
        background: #3E546A; margin: 0; text-align: center; padding-top: 2px; padding-bottom: 2px; border-radius: 10px; width: auto; color: white
    }
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 10px 10px;
        transition: 0.3s;
        font-size: 15px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
        overflow-y: hidden;
        height: calc(100% - 4em);
    }
    .img_name{
        width: calc(100% - 50px); height: 100%; display: inline-block; white-space: nowrap; text-overflow: ellipsis; text-align: left; overflow: hidden;
    }
    .messege_img{
        width: 35px; position: relative; left: 0; height: 100%; display: inline-block;
    }
    .other_td:hover p{
        display: block !important;
    }
    .name_hidden{
        display: none !important;
    }
    .new_group{
        border: 1px solid black;
        width: 28%;
        height: 80%;
        border-radius: 0 10px 10px 0;
        position: absolute;
        right: 15px;
        top: 10%;
        background: white;
        z-index: 890;
        box-shadow: none;
        display: none;
    }
    .img_message svg:hover{
        height: 90%;
    }
    .body_chat tr td{
        margin-top: 5px;
    }
    /*.body_chat tr{ text-align: center; font-size: 12px; font-weight: bolder}*/
    .other_td{
        display: flex; align-items: end; justify-content: flex-start;
    }
    .other_td:hover::after{
        content: attr(data-name);
        position: relative;
        left: 20px;
        top: 0px;
        font-size: 12px;
    }
    .info_other_p{
        width: auto; margin: 0px; font-size: 12px; display: inline-block; float: left; margin-left: 5px
    }
    .mine_td{
        display: flex; align-items: end; justify-content: flex-end
    }
    .info_mine_p{
        width: auto; margin: 0px; font-size: 12px; display: inline-block; float: right; margin-right: 5px
    }
    .mine_text{
        width: auto;
        max-width: 60%;
        float: right;
        background: #E3E6EA;
        border-radius: 10px;
        padding: 10px;
        margin: 0px;
    }
    .mine_text svg{
        margin-left: calc(50% - 15px);
    }
    .other_text{
        width: auto;
        max-width: 60%;
        float: left;
        background: #E3E6EA;
        border-radius: 10px;
        padding: 10px;
        margin: 0px;
    }
    .other_text svg{
        margin-left: calc(50% - 15px);
    }
    .input_message{
        position: absolute;
        bottom: 0px;
        width: 100%;
        height: 60px;
        border-top: 1px solid grey;
    }
    .text_chat{
        overflow-y: auto;
        top: 0;
        position: absolute;
        width: 100%;
        height: calc(100% - 60px);
    }
    .table_one_people{
        width: 100%;
        display: table;
        table-layout: fixed;
        height: 100%;
        vertical-align: middle;
    }
    .table_one_people:hover{
        background: lightgrey;
    }
    .table_one_people tr:first-child td:first-child{
        font-weight: bold;
        width: 80%;
    }
    .table_one_people tr:first-child td:last-child{
        width: 20%;
        text-align: center;
    }
    .table_one_people tr:last-child td:last-child{
        font-weight: bolder;
        padding-right: 10px;
    }
    .table_one_people tr td:first-child{
        padding-left: 5px;
        text-align: left;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .table_one_people tr td:last-child{
        text-align: right;
    }
    .selected_people{
        border-left: 4px solid #0079c2;
        background: white;
    }
    .one_people{
        height: 60px;
        border-top: 1px solid grey;
        border-bottom: 1px solid grey;
        width: 100%;
    }
    .close_messenger{
        border-radius: 0px 10px 0px 0px;
        background: red;
        position: absolute;
        right: 0;
        top: 0;
        height: 50px;
        width: 50px;
    }
    .name{
        position: absolute;
        left: 50px;
        top: 0;
        width: calc(100% - 100px);
        height: 50px;
    }
    .question{
        padding-top: 13px;
        padding-left: 13px;
        position: absolute;
        left: 0;
        top: 0;
        width: 50px;
        height: 50px;
    }
    .chat_div{
        position: absolute;
        bottom: 0;
        right: 0;
        height: calc(100% - 50px);
        width: 70%;
        border-radius: 0px 0px 10px 0px;
        overflow-y: hidden;
        background: white;
    }
    .name_recipient{
        position: absolute;
        top: 0;
        right: 0;
        width: 70%;
        height: 50px;
        min-height: 50px;
        border-radius: 0px 10px 0px 0px;
        background: #0079C2;
    }
    .search_input{
        /*position: ap;*/
        margin-top: 7px;
        /*margin-bottom: 10px;*/
        width: 95%;
        margin-left: 5%;
        top: 10px;
    }
    .search_input img{
        float: right;
        padding-right: 15px;
        padding-top: 7px;
    }
    .search_block{
        position: absolute;
        width: 30%;
        top: 0;
        height: 50px;
    }
    /*.create_block{*/
    /*    position: absolute;*/
    /*    width: 30%;*/
    /*    top: 55px;*/
    /*    height: 35px;*/
    /*    background: #2b542c;*/
    /*}*/
    .people{
        position: absolute;
        width: 30%;
        height: calc(100% - 50px);
        overflow-y: auto;
        overflow-x: hidden;
        bottom: 0;
    }
    .people_block{
        display: inline-block;
        vertical-align: top;
        margin-top: 0px;
        margin-left: 0px;
        width: 30%;
        height: 100%;
        background: #E3E6EA;
        border-radius: 10px 0px 0px 10px;
        overflow-y: auto;
        overflow-x: hidden;
        border-right: 1px solid darkgray;

    }
    .messenger{
        border: 1px solid black;
        width: 40%;
        height: 80%;
        border-radius: 10px;
        position: absolute;
        right: 15px;
        top: 10%;
        background: white;
        z-index: 888;
        box-shadow: 10px 5px 45px black;
        display: none;
    }
    .messenger_mini{
        width: 80px;
        height: 80px;
        position: absolute;
        bottom: 10px;
        right: 10px;
        z-index: 889;
    }
    .messenger_mini svg{
        height: 100%;
        width: 100%;
        opacity: 0.5;
    }

</style>
