@php
    $grade = $widget["grade"];
    $teachers = $grade->Teacher()->get();
    $students = $grade->Student()->get();
@endphp
<div class="my-5">
    <ul class="list-group">
        <li class="list-group-item">Tên lớp :{{$grade->name}}</li>
        <li class="list-group-item">Giáo viên :
            @foreach($teachers as $teacher)
                {{$teacher->name}},
            @endforeach
        </li>
        <li class="list-group-item">Link lớp:
            <a href="{{$grade->zoom}}">{{$grade->zoom}}</a>
        </li>
        <li class="list-group-item">Học sinh :
            @foreach($students as $student)
                {{$student->name}},
            @endforeach
        </li>
        <li class="list-group-item">Số phút học: {{$grade->minutes}}</li>
        <li class="list-group-item">Gói học phí : {{number_format($grade->pricing)}} đ</li>
        <li class="list-group-item">Tài liệu : <a class='' href='/uploads/document/{{ $grade->attachment }}'><i
                    class="las la-file-alt"></i> Click để đọc</a></li>
        <li class="list-group-item">Trạng thái :
            @if($grade->status == 0)
                Đang học
            @else
                @if($grade->status ==1)
                    Đã kết thúc
                @else
                    @if($grade->status ==2)
                        Đã bảo lưu
                    @endif
                @endif
            @endif
        </li>
        <li class="list-group-item">Ngày tạo lớp : {{$grade->created_at}}</li>
    </ul>
    <a class="nav-link" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
       aria-controls="collapseExample">
        Xem thêm thông tin chi tiết
    </a>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            {!! $grade->information !!}
        </div>
    </div>
</div>


<div class="h2">Lịch sử lớp học</div>

