<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px}

    .itemInfoTable th, .itemInfoTable td{
        border: 1px solid black;
        margin: 0;
        padding: 0;
        border-spacing: 0px;
        border-collapse: collapse;
    }
    table{
        font-size: 12px;
        -webkit-print-color-adjust: exact; /* благодаря этому заработал цвет*/
        border-spacing: 0px;
        border-collapse: collapse;
        /* благодаря этому строки переносятся*/
        /*white-space: pre-wrap; !* css-3 *!*/
        white-space: -moz-pre-wrap; /* Mozilla, начиная с 1999 года */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word; /
    word-break: break-all;
    }
    table th{
        background-color: darkgrey;
    }
</style>

<script src="/assets/js_library/jquery.js"></script>
<script src="/assets/js_library/jquery-ui.js"></script>
@stack('scripts')
@stack('styles')

<p id="date" style="display: none">{{$date}}</p>

    <div style="display: inline-flex; width: 100%;">
            <h3 style="width: 100%; text-align: center">Часовые показатели за {{$date}}</h3>
    </div>
    <div id="content-header"></div>
    <div id="redirect">
        <div id="tableDiv" style="width: 100%; text-align: center">
            <table class="itemInfoTable" style="width: 100%">
                <thead>
                    <tr>
                        <th>Наименование параметра</th>
                        <th>Ед.изм.</th>
                        <th>Сутки</th>
                        @for($j=0; $j<24; $j++)
                            @if($j >= $start_hour && $j <= 21)
                                <th>0{{$j - $start_hour}}:00</th>
                            @elseif($j>21)
                                <th>{{$j - $start_hour}}:00</th>
                            @else
                                <th>{{$start_hour+$j}}:00</th>
                            @endif
                        @endfor
                    </tr>
                </thead>
                <tbody id="tbody_table">

                </tbody>
            </table>
        </div>
        <div style="margin-top: 40px">
            <span style="text-decoration:underline; float: right; font-size: 20px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
        </div>
    </div>
    <script>

        $(document).ready(function () {
            get_table_data(document.getElementById('date').textContent)
        })

        function get_table_data(date) {
            $.ajax({
                url: '/get_hour_data/'+date,
                method: 'GET',
                dataType: 'html',
                async: false,
                success: function(res){
                    res = JSON.parse(res)
                    var table_body = document.getElementById('tbody_table')
                    table_body.innerText = ''
                    for (var row of res) {
                        var tr = document.createElement('tr')
                        tr.innerHTML += `<td><span style="background-color: rgba(0, 0, 0, 0)">${row['full_name']}</span></td>`
                        tr.innerHTML += `<td><spanstyle="background-color: rgba(0, 0, 0, 0)">${row['e_unit']}</span></td>`
                        for (var id = 0; id <= 24; id++) {
                            if (row[id]['id']){
                                tr.innerHTML += `<td><spanstyle="background-color: rgba(0, 0, 0, 0)" data-type="float">${row[id]['val']}</span></td>`
                            }else {
                                tr.innerHTML += `<td><spanstyle="background-color: rgba(0, 0, 0, 0)" data-type="float">...</span></td>`
                            }
                        }

                        table_body.appendChild(tr);
                    }
                }
            })
        }

        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/"
        }






    </script>
    <style>
        td{
            text-align: center;
        }
        .itemInfoTable span{
            text-align: center;
        }

    </style>


