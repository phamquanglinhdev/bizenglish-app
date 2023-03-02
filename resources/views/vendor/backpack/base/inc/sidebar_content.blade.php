<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
            class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
@if(backpack_user()->type!=1 && backpack_user()->type!=3 && backpack_user()->type!=2)
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('grade') }}'><i
                class='nav-icon la la-graduation-cap'></i>Lớp học</a></li>
@endif
<li class='nav-item'>
    <a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-pen'></i>
        {{trans("backpack::crud.history")}}
    </a>
</li>
@if(backpack_user()->type == -1)
    {{--    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-user'></i> Tài--}}
    {{--            khoản</a></li>--}}

    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('staff') }}'><i class='nav-icon la la-user-cog'></i>
            Nhân viên</a></li>
@endif
@if(backpack_user()->type<=0)
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-user-o"></i> Người dùng</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('teacher') }}'><i
                        class='nav-icon la la-chalkboard-teacher'></i> Giáo viên</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('student') }}'><i
                        class='nav-icon la la-user-astronaut'></i> Học sinh</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('customer') }}'><i
                        class='nav-icon la la-user-friends'></i> KH đang tư vấn</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('client') }}'><i
                        class='nav-icon la la-user-lock'></i> Đối tác</a></li>
        </ul>
    </li>

@endif
@if(backpack_user()->type<=1)
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('time') }}"><i class="nav-icon la la-calendar"></i>
            {{trans("backpack::crud.empty_time")}}
        </a></li>
@endif
@if(backpack_user()->type<=0)
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-book-reader"></i>
            {{trans("backpack::crud.book")}}
        </a>
        <ul class="nav-dropdown-items">
            @if(backpack_user()->type<=0)
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('menu') }}"><i
                            class="nav-icon la la-list"></i> Danh mục sách</a></li>
            @endif
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('book') }}'><i
                        class='nav-icon la la-book-open'></i>{{trans("backpack::crud.book")}}</a></li>
        </ul>
    </li>
@endif
@if(backpack_user()->type!=-1 || backpack_user()->type!=0 || backpack_user()->type!=3)
    <li class="nav-item"><a class="nav-link" href="{{ route("user.file") }}">
            <i class="nav-icon la la-file"></i> <span>{{trans("backpack::crud.file")}}</span></a>
    </li>
@endif



<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->


{{--<li class='nav-item'><a class='nav-link' href='{{ backpack_url('comment') }}'><i class='nav-icon la la-comment'></i>Nhận xét</a></li>--}}
{{--<li class='nav-item'><a class='nav-link' href='{{ backpack_url('exercise') }}'>--}}
{{--        <i class='nav-icon la la-book'></i> Bài tập</a>--}}
{{--</li>--}}

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('notification') }}"><i class="nav-icon la la-bell"></i>
        {{trans("backpack::crud.notification")}}
    </a></li>
@if(backpack_user()->type<=1)
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('demo') }}"><i class="nav-icon lab la-buffer"></i>
            Demo</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}">
            <i class="nav-icon la la-files-o"></i> <span>
                {{trans("backpack::crud.storage")}}
            </span></a>
    </li>
@endif
@if(backpack_user()->type==1)
    <hr>
    @if(isset($_COOKIE["language"]))
        <li class="nav-item"><a class="nav-link" href="{{route("main-version")}}">
                <i class="nav-icon la la-language"></i> <span>Vietnam</span></a>
        </li>
    @else
        <li class="nav-item"><a class="nav-link" href="{{route("english-version")}}">
                <i class="nav-icon la la-language"></i> <span>English</span></a>
        </li>
    @endif
@endif
@if(backpack_user()->type==-1)
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('device') }}"><i class="nav-icon la la-mobile"></i>Thiết
            bị</a></li>
    <li class="nav-item"><a class="nav-link" href="{{route("manager.camp")}}"><i class="nav-icon la la-bullhorn"></i>Truyền
            thông</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('post') }}"><i class="nav-icon la la-pinterest"></i>Bài
            viết</a></li>
@endif
<li class="nav-item"><a class="nav-link" href="{{ route("account-info") }}"><i
            class="nav-icon la la-user"></i>{{backpack_user()->name}}</a></li>




@if(backpack_user()->email=="admin@biz.com")
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('backup') }}'><i class='nav-icon la la-hdd-o'></i>
            Sao lưu</a></li>
@endif
