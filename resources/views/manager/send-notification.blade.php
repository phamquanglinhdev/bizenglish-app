@extends(backpack_view("blank"))
@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-12 bg-white">
                <form action="{{route("manager.send")}}" method="post">
                    @csrf
                    <div class="mb-2">
                        <label>Người tới:</label>
                        <br>
                        <select class="js-example-basic-single form-control" name="people[]" multiple>
                            <option value="everyone">@Tất cả</option>
                            <option value="all-student">@Tất cả học sinh</option>
                            <option value="all-teacher">@Tất cả giáo viên</option>
                            <option value="all-client">@Tất cả đối tác</option>
                            <option value="all-staff">@Tất cả nhân viên</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->code}}-{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Tiêu đề :</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Nội dung ngắn:</label>
                        <textarea class="form-control" maxlength="100" id="exampleFormControlTextarea1"
                                  rows="1"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Nội dung thông báo :</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <button class="btn btn-success">Gửi</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section("after_scripts")
    <link href="{{asset("packages/select2/dist/css/select2.min.css")}}" rel="stylesheet"/>
    <style>
        .select2-selection__rendered {
            min-height: 100px;
        }

        .select2-selection {
            border: 1px solid rgba(0, 0, 0, 0.37);
        }
    </style>
    <script src="{{asset("packages/select2/dist/js/select2.min.js")}}"></script>
    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>
@endsection
