<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
@if(backpack_user()->type==0)
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-user'></i> Tài khoản</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('teacher') }}'><i class='nav-icon la la-chalkboard-teacher'></i> Giáo viên</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('student') }}'><i class='nav-icon la la-user-astronaut'></i> Học sinh</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('client') }}'><i class='nav-icon la la-user-lock'></i> Đối tác</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('grade') }}'><i class='nav-icon la la-graduation-cap'></i> Lớp học</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}">
            <i class="nav-icon la la-files-o"></i> <span>Kho lưu trữ</span></a>
    </li>
@endif
<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@if(backpack_user()->type<=1)
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-pen'></i>Nhật ký</a></li>
@endif
<hr>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('comment') }}'><i class='nav-icon la la-comment'></i>Nhận xét</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('exercise') }}'><i class='nav-icon la la-book'></i> Bài tập</a></li>




