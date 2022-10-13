<table class="table table-bordered ">
    <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col">Monday</th>
        <th scope="col">Tuesday</th>
        <th scope="col">Wednesday</th>
        <th scope="col">Thursday</th>
        <th scope="col">Friday</th>
        <th scope="col">Saturday</th>
        <th scope="col">Sunday</th>
    </tr>
    </thead>
    <tbody>
    <style>
        .table-bordered td, th {
            text-align: center;
        }
    </style>
    @foreach($table??[] as $row)
        <tr>
            <th scope="row">{{$row["time"]}}</th>
            <td>
                @if($row["monday"]==1)
                    <i class="lar la-check-circle"></i>
                @endif
            </td>
            <td>
                @if($row["tuesday"]==1)
                    <i class="lar la-check-circle"></i>
                @endif
            </td>
            <td>
                @if($row["wednesday"]==1)
                    <i class="lar la-check-circle"></i>
                @endif
            </td>
            <td>
                @if($row["thursday"]==1)
                    <i class="lar la-check-circle"></i>
                @endif
            </td>
            <td>
                @if($row["friday"]==1)
                    <i class="lar la-check-circle"></i>
                @endif
            </td>
            <td>
                @if($row["saturday"]==1)
                    <i class="lar la-check-circle"></i>
                @endif
            </td>
            <td>
                @if($row["sunday"]==1)
                    <i class="lar la-check-circle"></i>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
