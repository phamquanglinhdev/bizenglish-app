@extends(backpack_view("blank"))

@section('content')
    @include("components.profile",["user"=>$data])
    <div class="bg-white mx-4 p-2 mt-5 rounded shadow-lg pb-5">
        @include("components.calendar",["user"=>$data])
        @if($data->Grades()->where("status",0)->count()<0)
            @include("components.grade",["grade"=>$data->Grades()->where("status",0)->first()])
        @endif
        @include("components.single-log",["grade"=>$data->Grades()->where("status",0)->first()])
        <hr>
        @if(backpack_user()->role<=1)
            @include("components.caring",["caring"=>$data->Carings()->orderBy("date")->get(),'student'=>$data])
        @endif
    </div>
@stop
