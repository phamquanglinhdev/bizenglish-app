@extends(backpack_view("blank"))

@section('content')
    <style>

        .image_outer_container {
            margin-top: auto;
            margin-bottom: auto;
            border-radius: 50%;
        }

        .image_inner_container {
            border-radius: 50%;
            padding: 5px;
            background: #833ab4;
            background: -webkit-linear-gradient(to bottom, #459dfc, #fd1d1d, #833ab4);
            background: linear-gradient(to bottom, #459dfc, #20fa97, #833ab4);
        }

        .image_inner_container img {
            border-radius: 50%;
            border: 5px solid white;
        }

    </style>
    <div class="container-md-fluid mt-5">
        <div class="row">
            <div class="col-lg-2 col-12">
                <div class="d-flex">
                    <div class="image_outer_container">
                        <div class="image_inner_container">
                            <img src="{{$data->avatar}}" class="w-100">
                        </div>
                    </div>
                </div>
                <div class="text-center mt-2 h3 text-muted font-italic">{{$data->name}}</div>
                <dìv class="card p-2 rounded">
                    <div><i class="las la-mail-bulk text text-success"></i> <span
                            class="font-italic">{{$data->email}}</span></div>
                    <div><i class="las la-address-book text-success "></i> <span
                            class="font-italic">{{$data->address??"Không có địa chỉ"}}</span></div>
                    <div><i class="lab la-facebook text-success"></i> <span
                            class="font-italic"><a href="{{$data->facebook??""}}">{{$data->name}}</a></span></div>
                    <div>
                        <i class="las la-info-circle text-success"></i> <span
                            class="font-italic">Thông tin thêm</span>
                        <div class="mt-1">
                            @php
                                $extras = (array)$data->extra;
                            @endphp
                            @foreach($extras as $extra)
                                <div> - {!! $extra["name"].": ".$extra['info']  !!}</div>
                            @endforeach
                        </div>
                    </div>
                </dìv>
            </div>
            <div class="col-lg-10 col-12">
                <div class="bg-cyan text-white h3 p-2 rounded">Lớp học</div>
                <div class="bg-white p-2 card">
                    @foreach($data->Grades()->get() as $grade)
                        <div><i class="las la-chalkboard-teacher"></i> {{$grade->name}}</div>
                        <div><i class="las la-hourglass"></i> {{$grade->getStatus()}}</div>
                        <div><i class="las la-user-astronaut"></i> Giáo viên:
                            @foreach($grade->Teacher()->get() as $teacher)
                            <span><a href="#">{{$teacher->name}}</a> ,</span>
                            @endforeach
                        </div>

                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
        <hr>
        <div class="bg-white card p-2">
            <div class="bg-cyan text-white h3 p-2 rounded">Nhật ký lớp học</div>
            @include("components.table",$crud))
        </div>
    </div>

@stop
