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
                {{$row["monday"]}}
            </td>
            <td>
                {{$row["tuesday"]}}
            </td>
            <td>
                {{$row["wednesday"]}}
            </td>
            <td>
                {{$row["thursday"]}}
            </td>
            <td>
                {{$row["friday"]}}
            </td>
            <td>
                {{$row["saturday"]}}
            </td>
            <td>
                {{$row["sunday"]}}
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
