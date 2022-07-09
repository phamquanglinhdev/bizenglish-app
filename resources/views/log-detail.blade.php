@extends(backpack_view("blank"))

@section('content')
    <div class="container-fluid">
        <div class="h2 mt-5">Lớp học : {{$log->Grade()->first()->name}}</div>
        <div>{{$log->lesson}}</div>
        <div>Thời gian bắt đầu : {{$log->time}}</div>
        <div>Thơi lượng : {{$log->duration}} phút</div>
        <div class="text-danger">Số buổi đã học : {{$log->Grade()->first()->Logs()->count()}}</div>
        <hr>
        <div class="bg-white p-lg-5 p-2">
            {!! $log->information !!}
        </div>

        <div class="row pb-5">

            <div class="col-md-6 col-12">
                <div class="h2 mt-5">Video bài giảng</div>
                <video width="100%" controls>
                    <source src="{{url("/uploads/videos/")."/".$log->teacher_video}}" type="video/mp4">
                </video>
                @if(backpack_user()->type==0)
                    <div class="mt-lg-3"><a href="#">Nộp bài tập</a></div>
                    <div><a href="#">Xác nhận tham gia học</a></div>
                @endif
            </div>
            <div class="col-md-6 col-12">
                <div class="h2 mt-5">Nhận xét</div>
                @foreach($log->Comments()->get() as $comment)
                    <div class="bg-white shadow-lg mb-2 p-2 comment rounded">
                        <div class="">
                        <span>
                            <img src="{{$comment->Owner()->first()->avatar}}" style="width: 3em;height: 3em"
                                 class="rounded-circle">
                        </span>
                            <span>{{$comment->Owner()->first()->name}}</span>
                            <span class="badge badge-{{$comment->Role($comment->Owner()->first()->type)["color"]}}">
                                {{$comment->Role($comment->Owner()->first()->type)["label"]}}
                            </span>
                            <hr>
                            <div>
                                {{$comment->message}}
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="bg-white shadow-lg mb-2 p-2 comment rounded">
                    <div class="">
                        <span>
                            <img src="{{backpack_user()->avatar}}" style="width: 3em;height: 3em"
                                 class="rounded-circle">
                        </span>
                        <span>{{backpack_user()->name}}</span>
                        <span class="badge badge-{{$comment->Role(backpack_user()->type)["color"]}}">
                                {{$comment->Role(backpack_user()->type)["label"]}}
                        </span>
                        <hr>
                        <div>
                            <form action="{{route("admin.comment.store")}}" method="post">
                                @csrf
                                <input type="hidden" name="log_id" value="{{$log->id}}">
                                <div class="form-group">
                                    <textarea name="message" class="form-control" id="exampleFormControlTextarea1"
                                              rows="3" placeholder="Nhận xét của bạn..."></textarea>
                                    <button class="btn btn-success mt-1" type="submit">Gủi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
