@php
    clearstatcache();
@endphp
@if(backpack_user()->type == -1)
    @php
        $posts = \App\Models\Post::orderBy("pin","DESC")->limit(3)->get();
    @endphp
    @include("manager.admin-dashboard",["posts"=>$posts])
@endif
@if(backpack_user()->type==0)
    @php
        $posts = \App\Models\Post::where("type",">=",0)->orderBy("pin","DESC")->limit(3)->get();
    @endphp
    @include("manager.staff-dashboard",["posts"=>$posts])
@endif
@if(backpack_user()->type==1)
    @php
        $posts = \App\Models\Post::where("type",1)->orWhere("type",5)->orderBy("pin","DESC")->limit(3)->get();
    @endphp
    @include("manager.user-dashboard",["posts"=>$posts])
@endif
@if(backpack_user()->type==2)
    @php
        $posts = \App\Models\Post::where("type",2)->orWhere("type",5)->orderBy("pin","DESC")->limit(3)->get();
    @endphp
    @include("manager.user-dashboard",["posts"=>$posts])
@endif
@if(backpack_user()->type==3)
    @php
        $posts = \App\Models\Post::where("type",3)->orWhere("type",5)->orderBy("pin","DESC")->limit(3)->get();
    @endphp
    @include("manager.user-dashboard",["posts"=>$posts])
@endif
@if(backpack_user()->type==4)
    @php
        $posts = \App\Models\Post::where("type",4)->orWhere("type",5)->orderBy("pin","DESC")->limit(3)->get();
    @endphp
    @include("manager.user-dashboard",["posts"=>$posts])
@endif
