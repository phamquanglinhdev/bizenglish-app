@extends(backpack_view("blank"))
@section("content")
    <div class="container-fluid">
        <a href="{{url("admin/log")}}" class="d-print-none"><i
                class="la la-angle-double-left"></i> {{trans("backpack::crud.return")}}
            <span>{{trans("backpack::crud.history")}}</span>
        </a>
    </div>
    <style>
        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <div class="container-fluid">
        <hr>
        <div class="row">
            <div class="col-lg-7 col-md-6 col-12">
                <div class="video-wrapper">
                    @if($log->teacher_video!=null)
                        <iframe src="https://youtube.com/embed/{{$log->getVideo()->id??""}}"
                                title="{{$log->getVideo()->title??""}}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    @endif
                    @if($log->drive!=null)
                        <iframe src="https://drive.google.com/file/d/{{explode("/",$log->drive)[5]}}/preview"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    @endif
                </div>
                <hr>
                <div class="h4 my-2 text-capitalize">{{trans("backpack::crud.lesson_name")}} : {{$log->lesson}}</div>
                <div class="">
                    <span class="mr-4">
                        <i class="las la-calendar"></i> {{$log->start}} - {{$log->end}} | {{$log->date}}
                    </span>
                    <span>
                        <i class="las la-clock"></i> {{$log->duration}} {{trans("backpack::crud.minutes")}}
                    </span>
                </div>
                <div class="my-3 row m-0">

                    @if(backpack_user()->type!=3)
                        <a data-toggle="modal" data-target="#exercises"
                           class="ml-2 btn btn-success text-white">
                            {{trans("backpack::crud.published_homework")}}
                        </a>

                    @else
                        <a href="{{route("admin.log.report",$log->id)}}"
                           class="btn btn-outline-success btn-success text-white ">Phản
                            hồi</a>
                        <a href="{{route("exercise.create",['log_id'=>$log->id])}}"
                           class="ml-2 btn btn-outline-success btn-success text-white ">Nộp bài tập</a>
                        <a data-toggle="modal" data-target="#exercises"
                           class="ml-2 btn btn-outline-success btn-success text-white">Xem bài tập đã nộp</a>
                    @endif
                    @include("components.exercise-modal")
                </div>
                <div class="bg-white rounded p-2">
                    <div>
                        {{trans("backpack::crud.grade_name")}} : {{$log->grade->name}}
                    </div>
                    <div>
                        {{trans("backpack::crud.teacher_name")}}: {{$log->teacher->name}}
                    </div>
                    <div>
                        {{trans("backpack::crud.student_name")}}: {{$log->getStudentList()}}
                    </div>
                </div>
                <div class="h5 mt-4">{{trans("backpack::crud.homework")}}:</div>
                <div class="bg-white rounded p-2">
                    {{$log->question}}
                </div>
                <hr>
                <form method="post" action="{{route("admin.comment.store")}}">
                    @csrf
                    <input hidden name="log_id" value="{{$log->id}}">
                    <div class="comment my-3">
                        <div class="d-flex justify-content-center align-items-center">
                            <img
                                src="{{backpack_user()->avatar??"https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png"}}"
                                class="rounded-circle p-2" style="width: 4em;height: 4em">
                            <div class="w-100">
                                <div class="input-group">
                                    <input type="text" name="message" class="form-control"
                                           placeholder="{{trans("backpack::crud.write_comment")}}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success" type="submit">
                                            <i class="las la-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="teacher my-3">
                    <div class="d-flex align-items-center">
                        <img
                            src="{{$log->teacher->avatar??"https://thumbs.dreamstime.com/b/girl-placard-girl-avatar-blank-placard-222423977.jpg"}}"
                            class="rounded-circle p-2" style="width: 4em;height: 4em">
                        <div>
                            <div class="font-weight-bold text-success">
                                <span>
                                    {{$log->teacher->name}}
                                </span>
                                <span class="badge badge-warning text-white">
                                    {{trans("backpack::crud.assessment")}}
                                </span>
                            </div>
                            <div class="font-italic">
                                {{$log->assessment}}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="comment my-3">
                    @if($log->Comments()->count()>0)
                        @foreach($log->Comments()->get() as $comment)
                            <div class="d-flex align-items-center">
                                <img src="{{$comment->Owner()->first()->avatar}}"
                                     class="rounded-circle p-2" style="width: 4em;height: 4em">
                                <div>
                                    <div class="font-weight-bold">{{$comment->Owner()->first()->name}}</div>
                                    <div>
                                        {{$comment->message}}
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    @endif
                </div>

            </div>
            <div class="col-lg-5 col-md-6 col-12">
                <div class="p-2 bg-cyan mb-2"></div>
                @foreach($logs as $log)
                    <a href="{{route("admin.log.detail",$log->id)}}" style="text-decoration: none" class="text-dark">
                        <div class="row my-2">
                            <div class="col-6">
                                <div class="video-wrapper">
                                    <img
                                        src="{{$log->getVideo()->image??"https://www.englishexplorer.com.sg/wp-content/uploads/2017/02/english-course.jpg"}}"
                                        class="w-100">
                                </div>
                            </div>
                            <div class="col-6 pl-0">
                                <div class="font-weight-bold">{{trans("backpack::crud.lesson_name")}} : {{$log->lesson}}
                                </div>
                                <div>
                                    <div><i class="las la-chalkboard-teacher"></i> {{$log->teacher->name}}</div>
                                    <div><i class="las la-user-graduate"></i> {{$log->getStudentList()}}</div>
                                    <div>
                                        <i class="las la-clock"></i> {{$log->duration}} {{trans("backpack::crud.minutes")}}
                                    </div>
                                    <div><i class="las la-calendar"></i> {{$log->start}} - {{$log->end}}
                                        | {{$log->date}}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr>
                @endforeach
            </div>
        </div>
    </div>
@endsection
