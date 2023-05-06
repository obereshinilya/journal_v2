@extends('layouts.app')
@section('title')
    Отчеты
@endsection

@section('side_menu')
    @include('include.report_menu')
@endsection

@section('content')
    <h1 style="width: 100%; text-align: center; margin-top: 100px">Выберите отчет</h1>
@endsection
<script>
    localStorage.removeItem('indexReport')
</script>
