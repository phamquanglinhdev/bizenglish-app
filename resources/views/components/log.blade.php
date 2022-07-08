<link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet"/>

<div class="container-fluid pb-5">
    <div class="h4 mt-2">{{$name}}</div>
    <table id="data-{{$id}}">
        <thead>
        <tr>
            @foreach($column["label"] as $value)
                <th>{{$value}}</th>
            @endforeach
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                @foreach($column["key"] as $value)
                    @if($value=="teacher_video")
                        <td><a href="{{url("/uploads/videos/$row->teacher_video")}}"><<i
                                    class="las la-cloud-download-alt"></i> <span>Download video</span></a></td>
                    @else
                        <td>{{$row->$value}}</td>
                    @endif
                @endforeach
                <td>
                    <a href="#"><i class="las la-cloud-upload-alt"></i> Gủi bài tập </a>
                    <span class="mr-2"></span>
                    <a href="#"><i class="las la-comment"></i> Đánh giá</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div id="table-{{$id}}"></div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>


<script>
    new gridjs.Grid({
        from: document.getElementById("data-{{$id}}"),
        search: {
            enabled: true
        },
        sort: true,
        pagination: {
            enabled: true,
            limit: 5,
            summary: false
        }
    }).render(document.getElementById("table-{{$id}}"));
    // new gridjs.Grid({
    //     columns: [],
    //     data: [
    //         ["John", "john@example.com", "(353) 01 222 3333"],
    //         ["Mark", "mark@gmail.com", "(01) 22 888 4444"],
    //         ["Eoin", "eoin@gmail.com", "0097 22 654 00033"],
    //         ["Sarah", "sarahcdd@gmail.com", "+322 876 1233"],
    //         ["Afshin", "afshin@mail.com", "(353) 22 87 8356"]
    //     ],
    //     search: {
    //         enabled: true
    //     },
    //     sort: true,
    //     pagination: {
    //         enabled: true,
    //         limit: 5,
    //         summary: false
    //     }
    // }).render(document.getElementById("table1"));
</script>


