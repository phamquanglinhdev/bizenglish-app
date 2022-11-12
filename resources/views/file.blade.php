@extends(backpack_view("blank"))
@section("content")
    <div class="h1">
        {{trans("backpack::crud.file")}}
    </div>
    <hr>
    <div class="col-9">
        <ul class="list-group">
            @if(isset($files))
                @foreach($files as $file)
                    <li class="list-group-item d-flex justify-content-between">
               <span>
                    {{$file->name}}
               </span>
                        <span>
                    <a href="{{url($file->link)}}">
                        {{trans("backpack::crud.view_file")}}
                    </a>
                </span>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
@endsection
