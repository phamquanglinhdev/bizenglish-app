@extends("layouts.public")
@section("content")
    <div class="container-fluid bg-light pt-5">
        <div class="row">
            <div class="col-sm-3 col-12">
                <img src="{{$book->thumbnail}}" class="w-100">
            </div>
            <div class="col-sm-6 col-12">
                <div class="h3 text-primary">Bộ sách: {{$book->name}}</div>
                <hr>
                <div>
                    @foreach($book->Lessons()->orderBy("name","ASC")->get() as $lesson)
                        <div class="d-flex">
                          {{$lesson->name}} :  <a href="{{url($lesson->pdf)}}">Link</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
