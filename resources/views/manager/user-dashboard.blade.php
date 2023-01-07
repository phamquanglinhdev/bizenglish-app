@extends(backpack_view("blank"))

@section('content')
    <h2>Xin chào , {{backpack_user()->name}}</h2>
    <hr>
    <div>
        @foreach($posts as $post)
            <div class="bg-white p-2">
                <div class="h4 text-success font-weight-bold">
                    {{$post->title}}
                </div>
                <hr>
                <div>
                    {!! $post->document !!}
                </div>
            </div>
        @endforeach
    </div>
@stop
