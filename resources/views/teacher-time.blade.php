@php
    $daily = $widget["daily"];
    $week=["mon","tue","wed","thu","fri","sat","sun"];
@endphp
<div class="row">
    @foreach($week as $day)
        <div class="col-md-3 my-2">
            <div class="bg-cyan text-white  p-2" style="min-height: 200px">
                <div class="h3 text-uppercase">
                    {{--                    Thứ Hai (Monday)--}}{{$day}}
                </div>
                <hr>
                @if(isset($daily[$day]))
                    @foreach($daily[$day] as $mon)
                        <div>
                            <i class="las la-dot-circle"></i>
                            <a href="{{route("log.index")}}?grade_id={{$mon["grade"]->id}}" class="text-white">
                                {{$mon["grade"]->name}}
                            </a>
                            <span>: {{$mon["value"]}}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</div>
