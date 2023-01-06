@extends(backpack_view("blank"))
@section("content")
    <div class="container-fluid">
        <div class="p-2">

            @if($maintain=="off")
                <div class="btn btn-success text-white">
                    IP V4 : {{$_SERVER["REMOTE_ADDR"]}}
                </div>
                <a href="{{route("maintain")}}">
                    <button class="btn btn-success">Enable Maintain Mode</button>
                </a>
            @else
                <div class="btn btn-success text-white">
                    CURRENT IP : {{$maintainIP}}
                </div>
                <a href="{{route("maintain")}}">
                    <button class="btn btn-danger">Disable Maintain Mode</button>
                </a>
                <img src="https://vinades.vn/uploads/news/2021_12/thong-bao-bao-tri-he-thong-tren-vinades.jpg">
            @endif
        </div>
@endsection
@section("after_scripts")

@endsection
