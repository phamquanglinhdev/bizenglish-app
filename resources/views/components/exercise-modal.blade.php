<!-- Button trigger modal -->
<style>
    .modal-backdrop {
        display: none;
    }

    .modal {
        background: rgb(0 0 0 / 69%);
    }
</style>
<!-- Modal -->
<div class="modal fade" id="exercises" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Bài tập đã nộp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($log->Exercises()->orderBy("updated_at","DESC")->get() as $exercise)
                    <div class="exercise mb-2">
                        <div class="bg-light p-2">
                            <span>Ngày nộp : {{$exercise->updated_at}} bởi </span>
                            <a href="#">{{$exercise->student->name}}</a>
                        </div>
                        <div class="bg-white border p-2">
                            {!! $exercise->paragraph !!}
                        </div>
                        <div class="bg-light p-2 ">
                            <div class="d-flex">
                                @if($exercise->video!=null)
                                    <a target="_blank" href="{{url("/uploads/videos/$exercise->video")}}" class="nav-link"><i
                                            class="las la-file-video"></i> Video</a>
                                @endif
                                @if($exercise->document!=null)
                                    <a target="_blank" href="{{url("/uploads/document/$exercise->document")}}"
                                       class="nav-link"><i
                                            class="las la-file-alt"></i> Tài liệu</a>
                                @endif
                                @if($exercise->student->id == backpack_user()->id)
                                    <a  href="{{route("exercise.edit",$exercise->id)}}" class="nav-link"><i
                                            class="las la-pen-nib"></i>
                                        Nộp lại </a>
                                    <form action="{{route("exercise.destroy",$exercise->id)}}" method="post">
                                        @csrf
                                        @method("delete")
                                        <button class="nav-link" type="submit"><i class="las la-trash-alt"></i> Xoá bài
                                            tập
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                {{--                <button type="button" class="btn btn-primary">Understood</button>--}}
            </div>
        </div>
    </div>
</div>
