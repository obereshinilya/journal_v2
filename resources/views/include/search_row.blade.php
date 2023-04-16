<input class="input header_blocks" type="text" id="search_row" style="width: 200px" placeholder="Поиск...">
<script>
    var input = document.getElementById('search_row')
    input.oninput = function() {
        search_object()
    };
    function search_object(){
        var body_statick = document.getElementById('statickTable').getElementsByTagName('tbody')[0]
        var all_tr_statick = body_statick.getElementsByTagName('tr')
        var search_text = new RegExp(document.getElementById('search_row').value, 'i');
        var body_dynamic = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0]
        if (body_dynamic){
            var all_tr_dynamic = body_dynamic.getElementsByTagName('tr')
            for(var i=0; i<all_tr_statick.length; i++){
                if (!all_tr_statick[i].classList.contains('hidden_rows')){
                    if (all_tr_statick[i].getElementsByTagName('td')[0].textContent.match(search_text) || all_tr_statick[i].getElementsByTagName('td')[1].textContent.match(search_text)){
                        all_tr_statick[i].style.display = ''
                        all_tr_dynamic[i].style.display = ''
                    }else {
                        all_tr_statick[i].style.display = 'none'
                        all_tr_dynamic[i].style.display = 'none'
                    }
                }
            }
        }else {
            for(var i=0; i<all_tr_statick.length; i++){
                if (!all_tr_statick[i].classList.contains('hidden_rows')){
                    if (all_tr_statick[i].getElementsByTagName('td')[0].textContent.match(search_text) || all_tr_statick[i].getElementsByTagName('td')[1].textContent.match(search_text)){
                        all_tr_statick[i].style.display = ''
                    }else {
                        all_tr_statick[i].style.display = 'none'
                    }
                }
            }
        }
    }
</script>

