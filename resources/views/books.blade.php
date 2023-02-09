@extends(backpack_view("blank"))
@section("content")
    <div class="h2 mt-5">{{trans("backpack::crud.books")}}</div>
    @if(backpack_user()->type<=0)
        <a href="{{backpack_url("book/create")}}" class="btn btn-primary text-white">
            <i class="las la-plus"></i>
            {{trans("backpack::crud.add")." ".trans("backpack::crud.book")}}
        </a>
    @endif
    <hr>
    @foreach($menus as $sub)
        <a class="my-1 btn btn-secondary w-100 text-left" data-toggle="collapse" href="#main-{{$sub->id}}">
            <i class="las la-list"></i>
            {{$sub->name}}
        </a>
        <div class="collapse show" id="main-{{$sub->id}}">
            <div class="card card-body p-2">
                @if($sub->books->count()>0)
                    <div class="card card-body">
                        @foreach($sub->books as $book)
                            <div class="my-1">
                                <img style="width: 30px;" src="{{$book->thumbnail}}">
                                {{$book->name}}:<a href="{{$book->link}}">Link</a>
                            </div>
                        @endforeach
                    </div>
                @endif
                @foreach($sub->children as $category)
                    <a class="my-1 btn btn-secondary w-100 text-left" data-toggle="collapse"
                       href="#sub-{{$category->id}}">
                        <i class="las la-ellipsis-v"></i>
                        {{$category->name}}
                    </a>
                    <div class="collapse" id="sub-{{$category->id}}">
                        <div class="card card-body">
                            @foreach($category->books as $book)
                                <div class="my-1">
                                    <img style="width: 30px;" src="{{$book->thumbnail}}">
                                    {{$book->name}}:<a href="{{$book->link}}">Link</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @endforeach

@endsection
