@extends(backpack_view("blank"))
@section("content")
    <form action="{{route("update-time")}}" method="post">
        @csrf
        <input name="id" value="{{$time->id}}" hidden>
        <div class="h2">
            Lịch trống của {{$time->teacher->name}}
            <span class="w-100 pb-2">
            <button class="btn btn-success">Cập nhật</button>
        </span>
        </div>
        <hr>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Monday</th>
                <th scope="col">Tuesday</th>
                <th scope="col">Wednesday</th>
                <th scope="col">Thursday</th>
                <th scope="col">Friday</th>
                <th scope="col">Saturday</th>
                <th scope="col">Sunday</th>
            </tr>
            </thead>
            <tbody>
            {{--        Morning--}}
            <tr>
                <th rowspan="3">Morning</th>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="morning-0-{{$i}}" name="morning-0-{{$i}}" class="form-control"
                                   value="{{$time->getMorningArr()[0][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            <tr>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="morning-1-{{$i}}" name="morning-1-{{$i}}" class="form-control"
                                   value="{{$time->getMorningArr()[1][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            <tr>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="morning-2-{{$i}}" name="morning-2-{{$i}}" class="form-control"
                                   value="{{$time->getMorningArr()[2][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            {{--Afternoon--}}
            <tr>
                <th rowspan="3">Afternoon</th>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="afternoon-0-{{$i}}" name="afternoon-0-{{$i}}" class="form-control"
                                   value="{{$time->getAfternoonArr()[0][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            <tr>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="afternoon-1-{{$i}}" name="afternoon-1-{{$i}}" class="form-control"
                                   value="{{$time->getAfternoonArr()[1][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            <tr>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="afternoon-2-{{$i}}" name="afternoon-2-{{$i}}" class="form-control"
                                   value="{{$time->getAfternoonArr()[2][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            {{--Evening--}}
            <tr>
                <th rowspan="3">Evening</th>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="evening-0-{{$i}}" name="evening-0-{{$i}}" class="form-control"
                                   value="{{$time->getEveningArr()[0][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            <tr>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="evening-1-{{$i}}" name="evening-1-{{$i}}" class="form-control"
                                   value="{{$time->getEveningArr()[1][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            <tr>
                @for($i = 0;$i<7;$i++)
                    {{--                ->Moring()[1][$i]--}}
                    <td class="editor-preview">
                        <div class="input-group mb-3">
                            <input type="text" id="evening-2-{{$i}}" name="evening-2-{{$i}}" class="form-control"
                                   value="{{$time->getEveningArr()[2][$i]??"-"}}">
                            
                        </div>
                    </td>
                @endfor
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Monday</th>
                <th scope="col">Tuesday</th>
                <th scope="col">Wednesday</th>
                <th scope="col">Thursday</th>
                <th scope="col">Friday</th>
                <th scope="col">Saturday</th>
                <th scope="col">Sunday</th>
            </tr>
            </tfoot>
        </table>
    </form>

@endsection
@section("after_scripts")
    <script>
        $(".la-check-circle").click(function (e) {
            console.log("Ahihi")
        })
    </script>
@endsection
