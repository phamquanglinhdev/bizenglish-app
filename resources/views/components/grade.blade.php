<div class="container-fluid mt-5">
    <hr>
    <div class="h3">
        Thông tin lớp đang học
    </div>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-6">
                <div><i class="lab la-first-order-alt"></i> Tên lớp: {{$grade->name}}</div>
                <div><i class="la la-user"></i> Giáo viên:
                    {{implode(",",$grade->teacher->pluck("name")->toArray())}}
                </div>
                <div><i class="la la-user"></i> Trợ giảng:
                    {{implode(",",$grade->supporter->pluck("name")->toArray())}}
                </div>
                <div><i class="la la-user"></i> NV quản lý lớp:
                    {{implode(",",$grade->staff->pluck("name")->toArray())}}
                </div>
                <div><i class="la la-clock"></i> Tổng số phút học:
                   {{$grade->minutes}}
                </div>
                <div><i class="la la-clock"></i> Số phút còn lại:
                    {{$grade->getRs()}}
                </div>
            </div>
            <div class="col-md-6">
                <div><i class="las la-calendar-alt"></i> Lịch học :
                    @foreach($grade->time as $time)
                        @if($time["day"]!="")
                            <div class="ml-2"><i class="las la-stream"></i> {{\App\Untils\Trans::Week($time["day"])}} : {{$time["value"]}}</div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>


</div>
