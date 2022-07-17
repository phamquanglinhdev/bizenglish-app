@extends(backpack_view("blank"))

@section('content')
    <div class="row">

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 text-white bg-primary">
                <a class="nav-link text-white" href="{{route("grade.index")}}">
                    <div class="card-body">
                        <div class="text-value">{{\App\Models\Grade::where("disable","=",0)->count()}}</div>

                        <div>Lớp học</div>

                        {{--                    <div class="progress progress-white progress-xs my-2">--}}
                        {{--                        <div class="progress-bar" role="progressbar" style="width: 13.2%" aria-valuenow="13.2" aria-valuemin="0" aria-valuemax="100"></div>--}}
                        {{--                    </div>--}}

                        {{--                    <small class="text-muted">868 more until next milestone.</small>--}}
                    </div>
                </a>

            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 text-white bg-cyan">
                <a class="nav-link text-white" href="{{route("log.index")}}">
                    <div class="card-body">
                        <div class="text-value">{{\App\Models\Log::where("disable","=",0)->count()}}</div>

                        <div>Buổi học</div>

                        {{--                    <div class="progress progress-white progress-xs my-2">--}}
                        {{--                        <div class="progress-bar" role="progressbar" style="width: 13.2%" aria-valuenow="13.2" aria-valuemin="0" aria-valuemax="100"></div>--}}
                        {{--                    </div>--}}

                        {{--                    <small class="text-muted">868 more until next milestone.</small>--}}
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 text-white bg-success">
                <a class="nav-link text-white" href="{{route("teacher.index")}}">
                    <div class="card-body">
                        <div class="text-value">{{\App\Models\User::where("type","=",1)->count()}}</div>

                        <div>Giáo viên</div>

                        {{--                    <div class="progress progress-white progress-xs my-2">--}}
                        {{--                        <div class="progress-bar" role="progressbar" style="width: 13.2%" aria-valuenow="13.2" aria-valuemin="0" aria-valuemax="100"></div>--}}
                        {{--                    </div>--}}

                        {{--                    <small class="text-muted">868 more until next milestone.</small>--}}
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 text-white bg-warning">
                <a class="nav-link text-white" href="{{route("student.index")}}">
                    <div class="card-body">
                        <div class="text-value">{{\App\Models\User::where("type","=",3)->count()}}</div>

                        <div>Học sinh</div>

                        {{--                    <div class="progress progress-white progress-xs my-2">--}}
                        {{--                        <div class="progress-bar" role="progressbar" style="width: 13.2%" aria-valuenow="13.2" aria-valuemin="0" aria-valuemax="100"></div>--}}
                        {{--                    </div>--}}

                        {{--                    <small class="text-muted">868 more until next milestone.</small>--}}
                    </div>
                </a>
            </div>
        </div>
    </div>
@stop
