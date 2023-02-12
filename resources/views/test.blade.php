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
        @if(backpack_user()->type<=0)
            <li class="list-group-item">Gói học phí : {{number_format($grade->pricing)}} đ</li>
        @endif
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
    <div class="mt-5">
        <div class="h3">Giáo trình của lớp :</div>
        <div class="bg-white p-3">
            @foreach($grade->menus as $menu)
                <div class="h5">
                    <i class="las la-book"></i>{{$menu->name}}
                </div>
                <hr/>
                <div class="row">
                    @foreach($menu->books as $book)
                        <div class="col-md-2 col-sm-6 col-6">
                            <a href="{{$book->link}}">
                                <div class="p-1">
                                    <img src="{{$book->thumbnail}}" class="w-100 shadow-lg">
                                </div>
                                <div class=text-center>{{$book->name}}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div class="collapse show" id="collapseExample">
        <div class="card card-body">
            {!! $grade->information !!}
        </div>
    </div>
</div>


<div class="h2">Lịch sử lớp học</div>

