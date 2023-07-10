@extends('layouts.app')
@section('title')
    Проверка буферизации
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <script src="/assets/js_library/apexChart/apexChart.js"></script>
    <div id="header_block_param" style="overflow-x: auto; overflow-y: hidden; max-height: 3.5em">
        <p id="header_doc" style="display: inline-block; max-width: 50%">Проверка буферизации</p>
        <button id="update" class="btn header_blocks btn_img"  data-toggle="tooltip" title="Обновить график" onclick="create_graph_bufer()"><img src="/assets/img/refresh.svg"></button>
    </div>
    <div style="width: calc(100% - 10px); height: calc(100% - 80px); overflow-y: auto" id="main_div">
        <div style="width: calc(100% - 10px); height: auto" id="main_div1">

        </div>
        <div style="width: calc(100% - 10px); height: auto" id="main_div2">

        </div>
        <div style="width: calc(100% - 10px); height: auto" id="main_div3">

        </div>
        <div style="width: calc(100% - 10px); height: auto" id="main_div4">

        </div>
        <div style="width: calc(100% - 10px); height: auto" id="main_div5">

        </div>
        <div style="width: calc(100% - 10px); height: auto" id="main_div6">

        </div>
        <div style="width: calc(100% - 10px); height: auto" id="main_div7">

        </div>
        <div style="width: calc(100% - 10px); height: auto" id="main_div8">

        </div>
    </div>

    <script>
        $(document).ready(function () {
            create_graph_bufer()
        })
        function create_graph_bufer(){
            $.ajax({
                url: '/test_bufer_data_discret',
                method: 'get',
                success: function (res) {
                    if (res === 'false'){
                        change_header_modal('Данных за последний час нет!')
                        open_modal_side_menu()
                        document.getElementById('submit_button_side_menu').setAttribute('onclick', `close_modal_side_menu()`)
                    }else {
                        document.getElementById('main_div1').innerText = ''
                        document.getElementById('main_div2').innerText = ''
                        document.getElementById('main_div3').innerText = ''
                        document.getElementById('main_div4').innerText = ''
                        document.getElementById('main_div5').innerText = ''
                        document.getElementById('main_div6').innerText = ''
                        document.getElementById('main_div7').innerText = ''
                        document.getElementById('main_div8').innerText = ''
                        for (var i = 0; i<res.length; i++){
                            var options = {
                                series: [{
                                    name: res[i][2],
                                    data: res[i][0]
                                }],
                                chart: {
                                    animations:{enabled: 0},
                                    height: 350,
                                    type: 'line',
                                    // zoom: {
                                    //     enabled: false
                                    // }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    curve: 'straight'
                                },
                                title: {
                                    text: res[i][2],
                                    align: 'left'
                                },
                                grid: {
                                    row: {
                                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                        opacity: 0.5
                                    },
                                },
                                xaxis: {
                                    categories: res[i][1],
                                }
                            };
                            var div_id = `#main_div${i+1}`
                            var chart = new ApexCharts(document.querySelector(div_id), options);
                            chart.render();
                        }
                    }

                }, async: false,
            })

        }
    </script>
@endsection

