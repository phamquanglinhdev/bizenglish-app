@extends(backpack_view("blank"))
@section("content")
    <div class="h1">Văn bản</div>
    <hr>
    <div class="col-9">
        <ul class="list-group">
            @foreach($files as $file)
                <li class="list-group-item d-flex justify-content-between">
               <span>
                    {{$file->name}}
               </span>
                    <span>
                    <a href="{{url($file->link)}}">Xem văn bản</a>
                </span>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
