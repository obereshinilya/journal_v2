<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .checked_button {
            background-color: red;
        }
    </style>
</head>
<body>
@foreach($all_param_hour as $param)
    <button data-unit="{{$param->e_unit}}" data-id="{{$param->id}}" data-name="{{$param->full_name}}"
            onclick="check_button(this)"> {{$param->full_name}} </button>

@endforeach
<br>
<button onclick="increment_weeks()">Кнопка для увеличения недель</button>
<label for="weeks">Сейчас отображается<input type="number" id="weeks"> недель</label>

<div id="container_main" style="">
</div>
{{--<div id="container_2" style="">--}}
{{--</div>--}}

<script src="/assets/js_library/jquery.js"></script>
<script src="/assets/js_library/jquery-ui.js"></script>
<script src="/assets/js_library/fusionCharts/fusioncharts.js"></script>
<script src="/assets/js_library/fusionCharts/fusioncharts.theme.fusion.js"></script>
<script>

    //Число отображаемых недель
    let number_of_weeks = 1;
    document.getElementById('weeks').value = number_of_weeks;

    function check_button(el) { //функция при нажатии на кнопку
        el.classList.toggle('checked_button')
        get_data(number_of_weeks)
    }

    function increment_weeks() { //увеличиваем недели, перестраиваем график
        number_of_weeks++
        document.getElementById('weeks').value = number_of_weeks;
        get_data(number_of_weeks)
    }

    function get_data(number_of_weeks) { //функция отрисовки графиков
        let container = document.getElementById('container_main') //контейнер в который будем пихать контейнеры для графиков
        container.innerHTML = ''
        let btns = document.querySelectorAll('.checked_button');
        if (btns.length > 0) {
            let suffixes = []
            btns.forEach((el) => {
                suffixes.push(el.getAttribute('data-unit') + '_' + el.getAttribute('data-id'))
            })
            suffixes.sort()
            let object = {}
            let unit, id
            let yaxis = []
            suffixes.forEach((el) => {
                unit = el.split('_')[0]
                id = el.split('_')[1]
                if (!(unit in object)) {
                    object[unit] = id + ','
                } else {
                    object[unit] += id + ','
                }
            })
            // console.log(object)
            let str = '';
            for (key in object) {
                str += object[key] + '!'
                switch (key) {
                    case '-':
                        yaxis.push(JSON.parse(`{"plot":{"value": "Положение/степень", "connectnulldata": true, "type": "smooth-line"},"format": {"suffix": " ${key}"}}`))
                        break
                    case '%':
                        yaxis.push(JSON.parse(`{"plot":{"value": "Задание/запас/моквелд", "connectnulldata": true, "type": "smooth-line"},"format": {"suffix": " ${key}"}}`))
                        break
                    case '°C':
                    case 'градус':
                        yaxis.push(JSON.parse(`{"plot":{"value": "Температура", "connectnulldata": true, "type": "smooth-line"},"format": {"suffix": " ${key}"}}`))
                        break
                    case 'кПа':
                    case 'МПа':
                        yaxis.push(JSON.parse(`{"plot":{"value": "Давление", "connectnulldata": true, "type": "smooth-line"},"format": {"suffix": " ${key}"}}`))
                        break
                    case 'м3/ч':
                    case 'Нм3/ч':
                        yaxis.push(JSON.parse(`{"plot":{"value": "Расход", "connectnulldata": true, "type": "smooth-line"},"format": {"suffix": " ${key}"}}`))
                        break
                    case 'об/мин':
                        yaxis.push(JSON.parse(`{"plot":{"value": "Частота", "connectnulldata": true, "type": "smooth-line"},"format": {"suffix": " ${key}"}}`))
                        break
                    case 'шт':
                        yaxis.push(JSON.parse(`{"plot":{"value": "Кол-во ГПА в работе", "connectnulldata": true, "type": "smooth-line"},"format": {"suffix": " ${key}"}}`))
                        break
                }
            }
            // console.log(yaxis)
            $.ajax({
                url: '/test_data_for_charts/' + str + '/' + number_of_weeks,
                method: 'GET',
                async: false,
                success: function (res) {
                    let schema = [];
                    let fusionDataStore, fusionTable, div;
                    let data = JSON.parse(res);
                    i = 0;
                    for (key in object) {
                        div = document.createElement('div')
                        div.id = 'container_' + i
                        container.append(div)
                        schema =
                            [{
                                "name": "Time",
                                "type": "date",
                                "format": "%Y-%m-%d %H:%M:%S"
                            },
                                {
                                    "name": "Param",
                                    "type": "string"
                                }, {
                                "name": yaxis[i].plot.value,
                                "type": "number"
                            }]
                        fusionDataStore = new FusionCharts.DataStore();
                        fusionTable = fusionDataStore.createDataTable(data[i], schema);
                        new FusionCharts({
                            type: "timeseries",
                            renderAt: "container_" + i,
                            width: "95%",
                            height: "500",
                            dataSource: {
                                data: fusionTable,
                                chart: {},
                                yAxis: yaxis[i],
                                "xAxis": {
                                    outputTimeFormat: {
                                        day: "%-d %b %Y",
                                        hour: "%-d %b %Y, %H:%M",
                                        minutes: "%-d %b %Y, %H:%M"
                                    }
                                },
                                tooltip: {
                                    outputTimeFormat: {hour: "%-d %b %Y, %H:%M", minutes: "%-d %b %Y, %H:%M"}
                                },
                                "series": "Param"
                            }
                        }).render();
                        i++;
                    }
                    // console.log(JSON.parse(res))
                }
            })
        }

    }


    // FusionCharts.ready(function () {
    //     let schema = [{
    //         "name": "Param",
    //         "type": "string"
    //     }, {
    //         "name": "Time",
    //         "type": "date",
    //         "format": "%-m/%-d/%Y"
    //     }, {
    //         "name": "Давление",
    //         "type": "number"
    //     }]
    //     let data_first =
    //         [
    //
    //             [
    //                 "United States",
    //                 "1/4/2011",
    //                 16.448
    //             ],
    //             [
    //                 "United States",
    //                 "1/5/2011",
    //                 272.736
    //             ],
    //             [
    //                 "United States",
    //                 "1/6/2011",
    //                 11.784
    //             ],
    //             [
    //                 "India",
    //                 "1/4/2011",
    //                 364.59
    //             ],
    //             [
    //                 "India",
    //                 "1/5/2011",
    //                 72
    //             ],
    //             [
    //                 "India",
    //                 "1/6/2011",
    //                 39.42
    //             ]
    //
    //
    //         ]
    //     let fusionDataStore = new FusionCharts.DataStore();
    //     let fusiontable = fusionDataStore.createDataTable(data_first, schema);
    //     var salesRevChart = new FusionCharts({
    //         type: "timeseries",
    //         renderAt: "container_1",
    //         width: "95%",
    //         height: "500",
    //         dataSource: {
    //             data: fusiontable,
    //             chart: {},
    //
    //             yAxis: [{
    //                 "plot": {
    //                     "value": "Sales",
    //                 }
    //             }],
    //             "series": "Param"
    //         }
    //     }).render();
    //
    //     let schema_second = [{
    //         "name": "Time",
    //         "type": "date",
    //         "format": "%-m/%-d/%Y"
    //     }
    //         , {
    //             "name": "Температура",
    //             "type": "number",
    //         }]
    //     let data_second = [
    //         [
    //
    //             "1/4/2011",
    //             16.448
    //         ],
    //         [
    //
    //             "1/5/2011",
    //             272.736
    //         ],
    //         [
    //
    //             "1/6/2011",
    //             11.784
    //         ],
    //         [
    //
    //             "1/7/2011",
    //             364.59
    //         ],
    //         [
    //
    //             "1/8/2011",
    //             72
    //         ],
    //         [
    //
    //             "1/9/2011",
    //             39.42
    //         ]
    //     ]
    //
    //     let fusionDataStore_2 = new FusionCharts.DataStore();
    //     let fusiontable_2 = fusionDataStore_2.createDataTable(data_second, schema_second);
    //     var dailyTransChart = new FusionCharts({
    //         type: "timeseries",
    //         renderAt: "container_2",
    //         width: "95%",
    //         height: "500",
    //         dataFormat: "json",
    //         dataSource: {
    //             chart: {
    //                 caption: "Daily Transactions",
    //                 subcaption: "Last 3 weeks",
    //                 xaxisname: "Date",
    //                 yaxisname: "No. of Transactions",
    //                 showvalues: "0",
    //                 theme: "fusion"
    //             },
    //             data: fusiontable_2
    //         }
    //     }).render();
    // })


    //     let schema = [{
    //         "name": "Param",
    //         "type": "string"
    //     }, {
    //         "name": "Time",
    //         "type": "date",
    //         "format": "%-m/%-d/%Y"
    //     }, {
    //         "name": "Value",
    //         "type": "number"
    //     }]
    //
    //     let data =
    //         // [
    //         [
    //         [
    //             "United States",
    //             "1/4/2011",
    //             16.448
    //         ],
    //         [
    //             "United States",
    //             "1/5/2011",
    //             272.736
    //         ],
    //         [
    //             "United States",
    //             "1/6/2011",
    //             11.784
    //         ],
    //         [
    //             "India",
    //             "1/4/2011",
    //             364.59
    //         ],
    //         [
    //             "India",
    //             "1/5/2011",
    //             72
    //         ],
    //         [
    //             "India",
    //             "1/6/2011",
    //             39.42
    //         ]
    //     // ]
    //         ,
    // // [        [
    // //             '1/4/2011',
    // //             356
    // //         ],
    // //     [  '1/4/2011',
    // //         123]
    // //     ]
    //
    //     ]
    //
    //     let new_data = {...data}
    // console.log(data)
    //     let fusionDataStore = new FusionCharts.DataStore();
    //     let fusionTable = fusionDataStore.createDataTable(data, schema);
    // console.log(fusionTable)
    //     new FusionCharts({
    //         type: 'timeseries',
    //         renderAt: 'container',
    //         width: "95%",
    //         height: 650,
    //         dataSource: {
    //             data: fusionTable,
    //             chart: {
    //             },
    //             caption: {
    //                 text: 'Online Sales of a SuperStore in India & the US'
    //             },
    //             yAxis: [{
    //                 "plot": {
    //                     "value": "Sales",
    //                 }
    //             }],
    //             "series": "Param"
    //         }
    //     }).render()
</script>
</body>
</html>
