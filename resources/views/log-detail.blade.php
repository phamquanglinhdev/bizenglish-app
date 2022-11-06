@extends(backpack_view("blank"))
@section('content')
    @if(session("message"))
        @php
            \Prologue\Alerts\Facades\Alert::success(session("message"))
        @endphp
    @endif
    <style>
        .modal-backdrop {
            display: none;
        }
    </style>
    <div class="container-fluid">

        <div class="h2 mt-5">
            Lớp học : {{$log->Grade()->first()->name}}
            <a href="{{route("log.index")}}" class="d-print-none font-sm"><i class="la la-angle-double-left"></i> Quay
                về <span>Nhật ký</span></a>
        </div>

        <div>{{$log->lesson}}</div>
        <div>Thời gian bắt đầu :{{"Từ $log->start đến $log->end ngày $log->date"}}</div>
        <div>Thơi lượng : {{$log->duration}} phút</div>
        <div class="text-danger">Số phút đã học : {{$log->Grade()->first()->Logs()->sum("duration")}}</div>
        <div>Tổng số phút học: {{$log->Grade()->first()->minutes}}</div>
        <div class="text-danger">Số buổi đã học : {{$log->Grade()->first()->Logs()->count()}}</div>
        <hr>
        <div class="row pb-5">
            <div class="col-md-12 col-12">
                <div class="embed-responsive embed-responsive-21by9">
                    <iframe class="embed-responsive-item"
                            src="https://youtube.com/embed/{{$log->getVideo()->id??""}}"></iframe>
                </div>

                @if(backpack_user()->type==3)
                    <div class="d-inline">
                        <a class="btn btn-success" href="{{route("exercise.create")}}?log_id={{$log->id}}">Nộp bài
                            tập</a>
                        <a id="accept_btn" class="btn btn-success" href="#" data-toggle="modal" data-target="#accept">Phản
                            hồi</a>
                    </div>
                @endif
            </div>

            <!-- Modal -->
            <div>
                <div class="modal fade bg-cyan show" id="accept" data-backdrop="static" data-keyboard="false"
                     tabindex="9999" aria-labelledby="staticBackdropLabel" aria-hidden="false">
                    <div class="modal-dialog modal-dialog-centered ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Trước tiên hãy nhận xét vể buổi học nào
                                    !!</h5>
                                {{--                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                {{--                                    <span aria-hidden="true">&times;</span>--}}
                                {{--                                </button>--}}
                            </div>
                            <div class="modal-body">
                                <form action="{{route("admin.log.accept")}}" method="post">
                                    @csrf
                                    <input type="hidden" name="log_id" value="{{$log->id}}">
                                    <div class="form-check">
                                        <input class="form-check-input" name="accept" type="radio" value="0"
                                               id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1">
                                            <div class="text-muted">Xác nhận tham gia lớp học và thông tin về buổi học
                                                là đúng ( Số phút dạy, nội dung,...)
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-check mb-5">
                                        <input class="form-check-input" name="accept" type="radio" value="1"
                                               id="defaultCheck2">
                                        <label class="form-check-label" for="defaultCheck2">
                                            <div class="text-muted">Thông tin giáo viên điền là sai (Vui lòng ghi chi
                                                tiết bên dưới)
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Nhận xét (Nếu có)</label>
                                        <textarea class="form-control" name="comment" id="exampleFormControlTextarea1"
                                                  rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Gủi phản hồi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row h-100">
            <div class="col-md-6 col-12">
                <div class="h2 mt-5">Đánh giá của giáo viên</div>
                <div class="bg-white shadow-lg mb-2 p-2 comment rounded">
                    {{$log->assessment}}
                </div>
                <div class="h2 mt-1">Bài tập về nhà</div>
                <div class="bg-white shadow-lg mb-2 p-2 comment rounded">
                    {!! $log->question !!}
                    @if($log->attachments !=null)
                        <div>Đính kèm:</div>
                        @foreach($log->attachments as $file)
                            <span class="file">
                                <a href="{{$file["link"]}}" class="btn btn-outline-primary">
                                    @switch($file["type"])
                                        @case(0)
                                        <i class="las la-file-pdf"></i>
                                        @break
                                        @case(1)
                                        <i class="lab la-youtube"></i>
                                        @break
                                    @endswitch
                                    <span class="font-italic">
                                    Link
                                </span>
                                </a>
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="h2 mt-5">Bình luận</div>
                @if($log->Comments()->count()>0)
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
                @endif

                <div class="bg-white shadow-lg mb-2 p-2 comment rounded">
                    <div class="">
                        <span>
                            <img src="{{backpack_user()->avatar}}" style="width: 3em;height: 3em"
                                 class="rounded-circle">
                        </span>
                        <span>{{backpack_user()->name}}</span>
                        <span class="badge badge-{{\App\Models\Comment::Role(backpack_user()->type)["color"]}}">
                                {{\App\Models\Comment::Role(backpack_user()->type)["label"]}}
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
        <div class="">
            <div class="h2 mt-5">Nội dung bài học</div>
            <div class="bg-white p-2">
                {!! $log->information !!}
            </div>
        </div>


    </div>

@endsection
@section("after_scripts")
{{--    @if(!$log->reported(backpack_user()->id) && backpack_user()->type==3)--}}
{{--        <script>--}}
{{--            $("#accept_btn").click();--}}
{{--        </script>--}}
{{--    @endif--}}
@endsection
