<div class="side_menu" id="side_menu">
    <div class="side_menu_content" id="side_menu_content">
        <ul>
            <li class="side_obj" report-id="1" onclick="save_index(this); window.location='/journal_sodu'">Журнал СОДУ</li>
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
</script>
