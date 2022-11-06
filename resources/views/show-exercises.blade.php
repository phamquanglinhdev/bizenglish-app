@extends(backpack_view("blank"))
@section("content")
    <div class="h2">Bài tập của bài {{$data->log->lesson}} lớp {{$data->log->Grade()->first()->name}}</div>
    <div class="h5">Học sinh nộp bài : {{$data->student->name}}</div>
    <div>
        @if($data->paragraph!=null)
            <div class="my-2">
                <div>Nội dung:</div>
                <div class="bg-white p-2 rounded border">
                    {!! $data->paragraph !!}
                </div>
            </div>
        @endif
        @if($data->video!=null)
            <div>
                Video : <a href="{{$data->video}}">Xem</a>
            </div>
        @endif
        @if($data->document!=null)
            <div>
                Tài liệu : <a href="{{url("uploads/document/".$data->document)}}">Xem</a>
            </div>
        @endif
    </div>
@endsection
