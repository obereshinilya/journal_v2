<div id="context_rezhim_main" class="context_menu">
    <a onclick="window.location = '/admin_rezhim_lists/false'">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
        Создать режимный лист
    </a>
</div>
<p id="select_rezhim" style="display: none"></p>
<div id="context_rezhim" class="context_menu">
    <a onclick="window.location = '/admin_rezhim_lists/'+document.getElementById('select_rezhim').textContent">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="m16 2.012 3 3L16.713 7.3l-3-3zM4 14v3h3l8.299-8.287-3-3zm0 6h16v2H4z"></path></svg>
        Редактировать режимный лист
    </a>
    <a onclick="delete_rezhim(document.getElementById('select_rezhim').textContent)">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>
        Удалить режимный лист
    </a>
</div>

<div class="side_menu" id="side_menu">
    <div class="side_menu_content" id="side_menu_content">
        <ul>
            <li class="side_obj" report-id="1" onclick="save_index(this); window.location='/journal_sodu'">Журнал СОДУ</li>
        </ul>
        <ul>
            <li class="side_obj" report-id="2" onclick="save_index(this); window.location='/journal_events'">Журнал событий</li>
        </ul>
        <ul>
            <li class="side_obj" report-id="3" onclick="save_index(this); window.location='/open_lists_ufa_tm'">Опробование алгоритмов</li>
        </ul>
        <ul>
            <li class="side_obj" report-id="6" onclick="save_index(this); window.location='/open_lists_ufa_tm_kran'">Опробование кранов</li>
        </ul>
        <ul>
            <li class="side_obj" report-id="4" onclick="save_index(this); window.location='/open_journal_perestanovok'">Журнал перестановок</li>
        </ul>
        <ul>
            <li class="side_obj">Буфеизация КП105<img id="img_2" onclick="open_custom_list(this, 'ul_rezhim1')" class="plus_icon hide" src="/assets/img/plus.png"></li>
            <ul id="ul_rezhim1">
                    <li class="side_obj rezhim_lists" report-id="rezhim_77" data-id="7" onclick="save_index(this); window.location='/test_bufer/kp'">Аналоги</li>
                    <li class="side_obj rezhim_lists" report-id="rezhim_87" data-id="7" onclick="save_index(this); window.location='/test_bufer_discret/kp'">Дискреты</li>
            </ul>
        </ul>
        <ul>
            <li class="side_obj">Буфеизация ГИС<img id="img_3" onclick="open_custom_list(this, 'ul_rezhim2')" class="plus_icon hide" src="/assets/img/plus.png"></li>
            <ul id="ul_rezhim2">
                    <li class="side_obj rezhim_lists" report-id="rezhim_97" data-id="7" onclick="save_index(this); window.location='/test_bufer/gis'">Аналоги</li>
                    <li class="side_obj rezhim_lists" report-id="rezhim_107" data-id="7" onclick="save_index(this); window.location='/test_bufer_discret/gis'">Дискреты</li>
            </ul>
        </ul>
        <ul>
            <li class="side_obj">Буфеизация ГРС<img id="img_4" onclick="open_custom_list(this, 'ul_rezhim3')" class="plus_icon hide" src="/assets/img/plus.png"></li>
            <ul id="ul_rezhim3">
                    <li class="side_obj rezhim_lists" report-id="rezhim_117" data-id="7" onclick="save_index(this); window.location='/test_bufer/grs'">Аналоги</li>
                    <li class="side_obj rezhim_lists" report-id="rezhim_127" data-id="7" onclick="save_index(this); window.location='/test_bufer_discret/grs'">Дискреты</li>
            </ul>
        </ul>
        <ul>
            <li class="side_obj">Режимные листы<img id="img_1" onclick="open_custom_list(this, 'ul_rezhim')" class="plus_icon hide" src="/assets/img/plus.png"></li>
            <ul id="ul_rezhim">
                @foreach(\App\Models\rezhim\RezhimLists::orderby('name_rezhim')->get() as $rezhim)
                    <li class="side_obj rezhim_lists" report-id="rezhim_{{$rezhim->id}}" data-id="{{$rezhim->id}}" onclick="save_index(this); window.location='/rezhim_list/{{$rezhim->id}}'">{{$rezhim->name_rezhim}}</li>
                @endforeach
            </ul>
        </ul>
    </div>
    <div class="show_hide_side_menu" id="show_hide_side_menu">
        <img id="show_hide_side_menu_btn" class="hide" src="/assets/img/arrow_right.svg">
    </div>
</div>



<script>
    $(document).ready(function () {
        $('#main_content').width($(document.body).width()-$('#side_menu').width())
        $( "#side_menu" ).resizable({handles: 'e'});
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
        $(window).on('resize', function(){$( "#main_content" ).width($(window).width() - $("#side_menu").width())});
        if (localStorage.getItem('indexReport'))
            $(`.side_obj[report-id="${localStorage.getItem('indexReport')}"]`).addClass('choiced')
        if(localStorage.getItem('imageReport')){
            $(`#${localStorage.getItem('imageReport')}`).click()
        }
    })
    $('#img_1').parent().on('contextmenu', function (event){
        $('.bordered').removeClass('bordered')
        var li = this
        li.classList.add('bordered')
        var context_menu = document.getElementById('context_rezhim_main')
        context_menu.style.display = 'block'
        context_menu.style.top = Number(event.pageY)+'px'
        context_menu.style.left = Number(event.pageX)+'px'
        $('body').on('click', function (){
            li.classList.remove('bordered')
            document.getElementById('context_rezhim_main').style.display = 'none'
        })
    })
    $('.rezhim_lists').on('contextmenu', function (event) {
        $('.bordered').removeClass('bordered')
        var li = this
        li.classList.add('bordered')
        var context_menu = document.getElementById('context_rezhim')
        context_menu.style.display = 'block'
        context_menu.style.top = Number(event.pageY) + 'px'
        context_menu.style.left = Number(event.pageX) + 'px'
        document.getElementById('select_rezhim').textContent = li.getAttribute('data-id')
        $('body').on('click', function () {
            li.classList.remove('bordered')
            document.getElementById('context_rezhim').style.display = 'none'
        })
    })
    function save_index(li){
        localStorage.setItem('indexReport', li.getAttribute('report-id'))
    }
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
    function open_custom_list(img, id){
        if(img.classList.contains('hide')){
            img.classList.remove('hide')
            img.style.transform = 'rotate(45deg)'
            var ul = document.getElementById(id)
            ul.style.display = 'block'
            localStorage.setItem('imageReport', img.id)
        }else{
            localStorage.removeItem('imageReport')
            img.classList.add('hide')
            img.style.transform = ''
            document.getElementById(id).style.display = ''
        }
    }
    function delete_rezhim(id){
        change_header_modal('Удалить режимный лист?')
        document.getElementById('submit_button_side_menu').setAttribute('onclick', `confirm_delete_rezhim(${id})`)
        open_modal_side_menu()
    }
    function confirm_delete_rezhim(id){
        $.ajax({
            url: '/delete_rezhim/'+id,
            method: 'get',
            success: function (res) {
                window.location.href = '/reports'
            }
        })
    }
</script>
