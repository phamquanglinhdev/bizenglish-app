@extends(backpack_view("blank"))
@section('content')
    @if(session("message"))
        @php
            \Prologue\Alerts\Facades\Alert::success(session("message"))
        @endphp
    @endif
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

@endsection
@section("after_scripts")
{{--    @if(!$log->reported(backpack_user()->id) && backpack_user()->type==3)--}}
{{--        <script>--}}
{{--            $("#accept_btn").click();--}}
{{--        </script>--}}
{{--    @endif--}}
@endsection
