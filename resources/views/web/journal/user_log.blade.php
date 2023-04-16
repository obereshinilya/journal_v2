@extends('layouts.app')
@section('title')
    Журнал действий оператора
@endsection
@section('content')
    <div id="spreadsheet"></div>


<script>
        var data = [
        ['Jazz', 'Honda', '2019-02-12', '', true, '$ 2.000,00', '#777700'],
        ['Civic', 'Honda', '2018-07-11', '', true, '$ 4.000,01', '#007777'],
        ];

        jspreadsheet(document.getElementById('spreadsheet'), {
        data:data,
        columns: [
    {
        type: 'text',
        title:'Car',
        width:90
    },
    {
        type: 'dropdown',
        title:'Make',
        width:120,
        source:[
        "Alfa Romeo",
        "Audi",
        "Bmw",
        "Chevrolet",
        "Chrystler",
        // (...)
        ]
    },
    {
        type: 'calendar',
        title:'Available',
        width:120
    },
    {
        type: 'image',
        title:'Photo',
        width:120
    },
    {
        type: 'checkbox',
        title:'Stock',
        width:80
    },
    {
        type: 'numeric',
        title:'Price',
        mask:'$ #.##,00',
        width:80,
        decimal:','
    },
    {
        type: 'color',
        width:80,
        render:'square',
    },
        ]
    });
</script>

@endsection
