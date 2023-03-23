<hr>
<style>
    #header th { position: sticky; top: -1px;background: #f3f2f2
    }
</style>
<div style="overflow-x: scroll">
    <div style="min-width: 3300px;max-height: 300px">
        <table class="table table-bordered" style="position: relative">
            <thead>
            <tr class="text-center" id="header">
                <th >Ngày</th>
                <th >Bắt đầu</th>
                <th >Kết thúc</th>
                <th >Lớp</th>
                {{--                <th >Học viên</th>--}}
                <th >Giáo viên</th>
                @if(backpack_user()->type<=0)
                    <th >Đối tác</th>
                @endif
                <th >Bài học</th>
                <th >Video</th>
                <th >Drive Video</th>
                <th >Thời lượng</th>
                <th >Tình trạng lớp</th>
                <th >Nhận xét của giáo viên</th>
            </tr>
            </thead>
            <div style="overflow: hidden">
                <tbody>
                @foreach($grade->logs as $log)
                    <tr class="text-center">
                        <td>{{\Carbon\Carbon::parse($log->date)->isoFormat("DD-MM-YYYY")}}</td>
                        <td>{{$log->start}}</td>
                        <td>{{$log->end}}</td>
                        <td>{{$log->Grade->name}}</td>
                        <td>{{$log->teacher->name}}</td>
                        @if(backpack_user()->type<=0)
                            <td >{{$log->client()}}</td>
                        @endif
                        <td>{{$log->lesson}}</td>
                        <td>{{$log->video}}</td>
                        <td><a href="{{$log->drive}}">Link</a></td>
                        <td>{{$log->duration}} phút</td>
                        <td>{{$log->StatusShow()}}</td>
                        <td>{{$log->assessment}}</td>
                    </tr>
                @endforeach
                </tbody>
            </div>

            <tfoot>
            <tr class="text-center">
                <th >Ngày</th>
                <th >Bắt đầu</th>
                <th >Kết thúc</th>
                <th >Lớp</th>
                {{--                <th >Học viên</th>--}}
                <th >Giáo viên</th>
                @if(backpack_user()->type<=0)
                    <th >Đối tác</th>
                @endif
                <th >Bài học</th>
                <th >Video</th>
                <th >Drive Video</th>
                <th >Thời lượng</th>
                <th >Tình trạng lớp</th>
                <th >Nhận xét của giáo viên</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

