<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('assets/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">

    <title>@yield('title')</title>

    <script src="/assets/js_library/jquery.js"></script>
    <script src="/assets/js_library/jquery-ui.js"></script>
    <link rel="stylesheet" href="/assets/my_styles/jquery-ui.css">

    <script src="/assets/js_library/air-datepicker.js"></script>
{{--Подключение графиков--}}
    <script src="/assets/js_library/fusionCharts/fusioncharts.js"></script>
    <script src="/assets/js_library/fusionCharts/fusioncharts.theme.fusion.js"></script>
{{--Подключение таблиц excel--}}
    <link rel="stylesheet" href="/assets/my_styles/excelTable/jexcel.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/my_styles/excelTable/jsuites.css" type="text/css"/>
    <script type="text/javascript" src="/assets/js_library/excelTable/jexcel.js"></script>
    <script type="text/javascript" src="/assets/js_library/excelTable/jsuites.js"></script>

    <link rel="stylesheet" href="/assets/my_styles/air-datepicker.css">

    <link rel="stylesheet" href="/assets/my_styles/my_style.css">
    <link rel="stylesheet" href="/assets/fonts/fonts.css">



</head>
<body>
@include('include.messenger')
@include('include.modal_window')
@include('include.header')

{{--@include('include.modal_window')--}}

@yield('side_menu')

<div class="content" id="main_content" >
    @yield('content')
</div>

<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("body").on("contextmenu", false);
    $('[data-toggle="tooltip"]').tooltip({
        content: function () {
            return $(this).prop('title');
        }
    })
</script>
</body>
</html>
