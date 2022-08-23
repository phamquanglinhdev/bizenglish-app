<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
            class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

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
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-mortar-board"></i> Lớp học</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('grade') }}'><i
                        class='nav-icon la la-graduation-cap'></i> Lớp học</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}">
                    <i class="nav-icon la la-files-o"></i> <span>Kho lưu trữ</span></a>
            </li>
        </ul>
    </li>

@endif
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@if(backpack_user()->type<=3)
    {{--    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-pen'></i>Nhật ký</a>--}}
    {{--    </li>--}}
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lesson') }}'><i class='nav-icon la la-file-pdf'></i> Giáo trình</a></li>
@endif
{{--<li class='nav-item'><a class='nav-link' href='{{ backpack_url('comment') }}'><i class='nav-icon la la-comment'></i>Nhận xét</a></li>--}}
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('exercise') }}'>
        <i class='nav-icon la la-book'></i> Bài tập</a>
</li>




<li class='nav-item'><a class='nav-link' href='{{ backpack_url('book') }}'><i class='nav-icon la la-question'></i> Books</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('teaching') }}'><i class='nav-icon la la-question'></i> Teachings</a></li>