@extends(backpack_view("blank"))
@section("content")
    <div class="h2 mt-5">{{trans("backpack::crud.books")}}</div>
    @if(backpack_user()->role<=0)
        <a href="{{backpack_url("book/create")}}" class="btn btn-primary text-white">
            <i class="las la-plus"></i>
            {{trans("backpack::crud.add")." ".trans("backpack::crud.books")}}
        </a>
    @else
    @endif
    @foreach($bag as $index => $books)
        <div class="category my-2">
            <a class="text-left btn btn-secondary w-100" type="button" data-toggle="collapse"
               data-target="#type{{$index}}"
               aria-expanded="false" aria-controls="collapseExample">
                <i class="las la-tags"></i>
                {{$name[$index]}}
            </a>
            <div class="collapse {{$index==0?"show":null}}" id="type{{$index}}">
                <div class="card card-body position-relative">
                    <div class="row">
                        @foreach($books as $book)
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 col-12 position-relative">
                                <a href="{{route("show-book",$book->slug)}}" class="nav-link text-dark">
                                    <div class="position-relative">
                                        <img src="{{$book->thumbnail}}" class="w-100 rounded shadow-lg">
                                    </div>
                                    <div class="my-1 text-center d-flex justify-content-center align-items-center">
                                        <span>{{$book->name}}</span>
                                        @if(backpack_user()->role<=0)
                                            <a href="{{backpack_url("book/$book->id/edit")}}">
                                                <i class="la la-edit"></i>
                                            </a>
                                        @else
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
