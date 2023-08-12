@php

    /**
     * @var \App\Models\Customer $data
     */
    $contests = $data->Contests()->get();
@endphp
@extends(backpack_view("blank"))

@section('content')
    <div class="container-fluid">
        <div class="h3">Bài test của KH</div>
        <div class="row">
            @foreach($contests as $contest)
                <div class="col-md-3 col-sm-6 col-12 ">
                    <div class="border shadow-lg bg-white p-2 h-100 rounded-lg">
                        <div class="h4 mt-2 text-muted">
                            {{$contest["title"]}}
                        </div>
                        <div class="">
                        <span class="mr-2">
                            <i class="la la-clock"></i>
                        {{$contest["limit_time"]}} phút
                        </span>
                            <span>
                            <i class="la la-question-circle"></i>
                            {{$contest->questions()}}
                        </span>
                        </div>
                        @if($contest["pivot"]["correct"]==null)
                            <div class="mt-2">
                               KH chưa làm bài test này
                            </div>
                        @else
                            <div class="border p-2 rounded mt-2">
                                <div>Số câu đúng: {{$contest["pivot"]["correct"]}}/{{$contest["pivot"]["total"]}}</div>
                                <div>Điểm số: {{$contest["pivot"]["score"]}}</div>
                                <a href="{{url("admin/contest/correct/".$contest["pivot"]["id"])}}">Xem chi tiết</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
