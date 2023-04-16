<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px
    }

    .table th,
    .table td {
        padding: 5px;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        border: 1px solid black; /* Параметры рамки */
        text-align: center;
    }

    .table-hover tbody tr:hover {
        color: #212529;
        background-color: rgba(0, 0, 0, 0.075);
    }
</style>
<table style="border-collapse: collapse;" class="table table-hover">
    <thead>
    <tr>
        <th colspan="27"><h2 class="text-muted" style="text-align: center">{{$title}}</h2></th>
    </tr>
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
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{$row['full_name']}}</td>
            <td>{{$row['e_unit']}}</td>
            @for($i=0; $i<=24; $i++)
                @if(!$row[$i]['id'])
                <td>...</td>
                @else
                    <td>{{$row[$i]['val']}}</td>
                @endif
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>
