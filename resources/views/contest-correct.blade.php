@php
    /**
     * @var $case
     */
    $case = (array)$case;
    $correct_tasks = json_decode($case["correct_task"]);

@endphp
@extends(backpack_view("blank"))
@section("content")
    <div class="container-fluid">
        <div class=" w-100">
            {{--            @dd($correct_tasks)--}}
            <div class="font-weight-bold h3 mt-4 col-12">
                Xem kết quả bài test
            </div>
            <div class="">
                @foreach($correct_tasks as $task)
                    @php
                        $task = (array) $task
                    @endphp
                    <div class="border my-2 p-md-3 p-2 rounded">
                        <div><span class="font-weight-bold">Câu hỏi {{$task["id"]+1}} </span></div>
                        <div class="my-2">
                            {{$task["question"]}}
                        </div>
                        @if($task["audio"]!=null)
                            <div class="mb-3">
                                <audio controls src="{{url($task["audio"])}}">

                                </audio>
                            </div>
                        @endif
                        @if($task["image"])
                            <div>
                                <img alt="" style="width: 10rem" src="{{url($task["image"])}}">
                            </div>
                        @endif
                        @if($task["video"])
                            <div>
                                <a target="_blank" href="{{$task["video"]}}">Xem video trên youtube</a>
                            </div>
                        @endif
                        <div class="my-3"></div>
                        @if($task["text_correct"]==null)
                            <div class="{{$task["correct"]=="a"?"font-weight-bold text-success":""}}">
                                A: {{$task["a"]}}</div>
                            <div class="{{$task["correct"]=="b"?"font-weight-bold text-success":""}}">
                                B: {{$task["b"]}}</div>
                            <div class="{{$task["correct"]=="c"?"font-weight-bold text-success":""}}">
                                C: {{$task["c"]}}</div>
                            <div class="{{$task["correct"]=="d"?"font-weight-bold text-success":""}}">
                                D: {{$task["d"]}}</div>
                            <div class="mt-3">Đáp án chọn : <span
                                        class="font-weight-bold text-uppercase">{{$task["user_choose"]}}</span>
                                @if($task["correct"]==$task["user_choose"])
                                    <span class="font-weight-bold text-success">[Đúng]</span>
                                @else
                                    <span class="font-weight-bold text-danger">[Sai]</span>
                                @endif
                            </div>
                        @else
                            <div>Đáp án đúng : <span>{{$task["text_correct"]}}</span></div>
                            <div class="mt-3">Đáp án trả lời : <span
                                        class="font-weight-bold text-uppercase">{{$task["user_type"]}}</span>

                                @if(\Illuminate\Support\Str::upper($task["text_correct"]) == \Illuminate\Support\Str::upper($task["user_type"]))
                                    <span class="font-weight-bold text-success">[Đúng]</span>
                                @else
                                    <span class="font-weight-bold text-danger">[Sai]</span>
                                @endif
                            </div>
                        @endif


                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

