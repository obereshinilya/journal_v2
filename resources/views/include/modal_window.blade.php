<div class="modal_window" id="modal_side_menu">
    <div class="overlay" id="overlay_side_menu" onclick="close_modal_side_menu()">

    </div>
    <div class="content_modal" id="content_modal_side_menu">
        <h2 id="text_modal_side_menu"></h2>
        <div class="button_div">
            <button id="submit_button_side_menu" class="btn">Подтвердить</button>
            <button class="btn" onclick="close_modal_side_menu()">Отменить</button>
        </div>
    </div>
</div>


<script>
    function change_header_modal(text){
        var h2 = document.getElementById('text_modal_side_menu')
        h2.innerHTML = text
    }
    function close_modal_side_menu(){
        document.getElementById('modal_side_menu').style.left = '-100%';
        document.getElementById('modal_side_menu').style.width = '1px'
        document.getElementById('content_modal_side_menu').style.width = ''
        document.getElementById('content_modal_side_menu').style.marginLeft = ''
        $('#table_modal_side_menu').remove()
        $('#modal_side_menu input').remove()
        $('#time_div').remove()
    }
    function open_modal_side_menu(){
        var modal = document.getElementById('modal_side_menu')
        modal.style.left = '0'
        modal.style.width = '100%'
    }
</script>
