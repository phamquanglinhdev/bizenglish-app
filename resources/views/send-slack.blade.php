@extends(backpack_view("blank"))
@section("content")
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-9 col-12">
                <form action="{{route("slack-send")}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Tin nhắn</label>
                        <input type="text" class="form-control" name="message">
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi</button>
                </form>
            </div>
        </div>
    </div>
@endsection
