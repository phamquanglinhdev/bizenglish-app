@extends(backpack_view("blank"))

@section('content')
    <h2>Xin chào , {{backpack_user()->name}}</h2>
    <hr>
    <div class="row">

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 text-white bg-primary">
                <a class="nav-link text-white" href="{{route("grade.index")}}">
                    <div class="card-body">

                        <div class="text-value">Lớp học</div>

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
                        <div class="text-value">Buổi học</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 text-white bg-success">
                <a class="nav-link text-white" href="{{route("teacher.index")}}">
                    <div class="card-body">
                        <div class="text-value">Giáo viên</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 text-white bg-warning">
                <a class="nav-link text-white" href="{{route("student.index")}}">
                    <div class="card-body">
                        <div class="text-value">Học sinh</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <hr>
    <div>
        @foreach($posts as $post)
            <div class="bg-white p-2">
                <div class="h4 text-success font-weight-bold">
                    {{$post->title}}
                </div>
                <hr>
                <div>
                    {!! $post->document !!}
                </div>
            </div>
        @endforeach
    </div>
@stop
