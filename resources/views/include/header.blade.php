<div class="header_line">

    <div class="header_cell div_list">
        <a href="/">Часовые показатели</a>
    </div>
    <div class="header_cell div_list">
        <a href="#">Настройка ОЖД</a>
        <img src="/assets/img/arrow.svg">
        <div><a href="#">Основные настройки</a></div>
        <div><a href="/signal_settings/false">Редактирование параметров</a></div>
    </div>
    <div class="user_div">
            <div class="small_user_div">
                <span>{{\Illuminate\Support\Facades\Auth::user()->displayname[0]}}</span>
            </div>
            <div class="out_div" onclick="window.location.href = '/logout'">
                <img src="/assets/img/logout.svg">
            </div>
    </div>
{{--    <div style="float: right" class="name_ojd header_cell">--}}
{{--        <a style="font-weight: bold">Оперативный журнал диспетчера</a>--}}
{{--    </div>--}}
</div>


<script>

    $('.div_list').click(function (){
        if (this.getElementsByTagName('img')[0].style.transform == 'rotate(90deg)'){
            this.style.margin = ''
            this.style.border = ''
            this.style.boxShadow = ''
            this.getElementsByTagName('img')[0].style.transform = ''
            for (var div of this.getElementsByTagName('div')){
                div.style.opacity = 0
                div.style.top = '37px'
            }
            slow_hide(this)
        }else {
            this.style.boxShadow = '5px 5px 5px black'
            this.style.margin = '16px 23px 0px 0px'
            this.style.border = '2px solid white'
            this.style.borderRadius = '5px'
            this.getElementsByTagName('img')[0].style.transform = 'rotate(90deg)'
            var i = 1;
            var max_width = 0
            var min_width = this.clientWidth
            for (var div of this.getElementsByTagName('div')){
                div.style.display = 'block'
                div.style.opacity = 1
                if (max_width < div.offsetWidth){
                    max_width = div.offsetWidth
                }
                if (i!==1){
                    div.style.top = 35*i+'px'
                }
                i++
            }
            if (max_width < min_width){
                for (var div of this.getElementsByTagName('div')){
                    div.style.width = min_width+'px'
                }
            }else {
                for (var div of this.getElementsByTagName('div')){
                    div.style.width = max_width+'px'
                }
            }
        }
    })
    function slow_hide(divs){
        setTimeout(function (){
            for (var div of divs.getElementsByTagName('div')){
                div.style.display = 'none'
            }
        }, 300)
    }
</script>

