<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12 mb-2">
            <div class="border rounded h-100 bg-white">
                <img
                    src="{{$user->avatar??$user->thumbnail??"https://png.pngtree.com/png-vector/20190521/ourlarge/pngtree-school-icon-for-personal-and-commercial-use-png-image_1044880.jpg"}}"
                    style="border: 1em white solid" class="w-100">
                @if(isset($user->time))
                    <div class="p-1 text-center">
                        <a href="{{backpack_url("/log?grade_id=".$user->id)}}" class="nav-link">
                            <i class="la la-history"></i>
                            Nhật ký lớp học
                        </a>
                        @if(in_array(backpack_user()->role,["admin","supper","staff"]))
                            <a href="{{backpack_url("/grade/".$user->id."/edit")}}" class="nav-link">
                                <i class="la la-pencil"></i>
                                Chỉnh sửa lớp học
                            </a>
                        @endif
                    </div>
                @endif
{{--                @if($user->role=="teacher")--}}
{{--                    <div class="p-1 text-center">--}}
{{--                        <a href="{{backpack_url("/grade?teacher_id=".$user->id)}}" class="nav-link">--}}
{{--                            <i class="la la-history"></i>--}}
{{--                            Danh sách lớp học--}}
{{--                        </a>--}}
{{--                        <a href="{{backpack_url("/log?teacher_id=".$user->id)}}" class="nav-link">--}}
{{--                            <i class="la la-history"></i>--}}
{{--                            Nhật ký lớp học--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--                @if($user->type==3)--}}
{{--                    <div class="p-1 text-center">--}}
{{--                        <a href="{{backpack_url("/grade?student_id=".$user->id)}}" class="nav-link">--}}
{{--                            <i class="la la-users"></i>--}}
{{--                            Danh sách lớp học--}}
{{--                        </a>--}}
{{--                        <a href="{{backpack_url("/log?student_id=".$user->id)}}" class="nav-link">--}}
{{--                            <i class="la la-history"></i>--}}
{{--                            Nhật ký lớp học--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>

        </div>
        <div class="col-md-9 col-sm-6 col-12 mb-2">
            <div class="border rounded bg-white p-lg-4 p-2 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="h2 text-primary font-weight-bold mb-2 mb-lg-3">{{$user->name}}</div>
                    <div>
                        @if(isset($user->google_id))
                            <span>
                            <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png"
                                 style="width: 1em;height: 1em">
                        </span>
                        @endif
                        @if(isset($user->facebook_id))
                            <span>
                            <a href="#">
                                <img
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/2021_Facebook_icon.svg/2048px-2021_Facebook_icon.svg.png"
                                    style="width: 1em;height: 1em">
                            </a>
                        </span>
                        @endif
                        @if(isset($user->github_id))
                            <span>
                            <a href="#">
                                <img
                                    src="https://cdn-icons-png.flaticon.com/512/25/25231.png"
                                    style="width: 1em;height: 1em">
                            </a>
                        </span>
                        @endif
                    </div>
                    @if($user->role=="teacher")

                        <div class="extras-information">
                            <div class="extras-information">
                                <div class="mt-2">
                                    <i class="la la-list text-danger"></i>
                                    Mã giáo viên : {{$user->code}}
                                </div>
                                <div class="mt-2">
                                    <i class="las la-user text-danger"></i>
                                    Tên giáo viên : {{$user->name}}
                                </div>
                                <div class="mt-2">
                                    <i class="las la-phone text-danger"></i>
                                    Sđt : {{$user->phone}}
                                </div>
                                <div class="mt-2">
                                    <i class="las la-envelope text-danger"></i>
                                    Email: {{$user->email}}
                                </div>
                                <div class="mt-2">
                                    <i class="las la-map text-danger"></i>
                                    Địa chỉ: {{$user->address}}
                                </div>
                                <div class="mt-2">
                                    <i class="las la-user text-danger"></i>
                                    Hồ sơ giáo viên: <a href="{{$user->cv}}"> <i class="la la-download"></i>Tải
                                        xuống</a>
                                </div>
                                @if($user->extras!=null)
                                    @foreach($user->extras as $item)
                                        @if($item["name"]!="")
                                            <div class="mt-2">
                                                <i class="las la-list text-danger"></i>
                                                {{$item["name"]}}: {{$item["value"]}}
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($user->type==3)
                        <div class="extras-information">
                            <div class="mt-2">
                                <i class="las la-graduation-cap text-danger"></i>
                                Mã học viên : {{$user->code}}
                            </div>
                            <div class="mt-2">
                                <i class="las la-phone text-danger"></i>
                                Sđt : {{$user->phone}}
                            </div>
                            <div class="mt-2">
                                <i class="las la-envelope text-danger"></i>
                                Email: {{$user->email}}
                            </div>
                            <div class="mt-2">
                                <i class="las la-user text-danger"></i>
                                Người giám hộ: {{$user->student_parent}}
                            </div>
                        </div>
                    @endif
                    @if(isset($user->time))
                        <div class="extras-information">
                            <div class="extras-information">
                                <div class="mt-2">
                                    <i class="la la-list text-danger"></i>
                                    Chương trình học : {{$user->program}}
                                </div>
                                <div class="mt-2">
                                    <i class="las la-user text-danger"></i>
                                    Giáo viên :
                                    @foreach($user->teachers as $teacher)
                                        <a href="{{route("teacher.show",$teacher->id)}}" class="nav-link d-inline p-0">
                                            {{$teacher->name}}
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-2">
                                    <i class="las la-user text-danger"></i>
                                    Trợ giảng :
                                    @foreach($user->supporters as $supporter)
                                        <a href="{{route("teacher.show",$supporter->id)}}"
                                           class="nav-link d-inline p-0">
                                            {{$supporter->name}}
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-2">
                                    <i class="las la-user text-danger"></i>
                                    Học sinh :
                                    @forelse($user->students as $student)
                                        <a href="{{route("student.show",$student->id)}}" class="nav-link d-inline p-0">
                                            {{$student->name}}
                                        </a>
                                    @empty
                                    @endforelse
                                </div>
                                <div class="mt-2">
                                    <i class="las la-user text-danger"></i>
                                    Nhân viên quản lý :
                                    @foreach($user->staffs as $staff)
                                        <a href="{{route("teacher.show",$staff->id)}}" class="nav-link d-inline p-0">
                                            {{$staff->name}}
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-2">
                                    <i class="las la-clock text-danger"></i>
                                    Thời lượng : {{$user->time}} phút
                                </div>
                                <div class="mt-2">
                                    <i class="las la-calendar text-danger"></i>
                                    Lịch học:
                                    @if($user->times)
                                        @foreach($user->times as $item)
                                            <div class="ml-2">
                                                <i class="las la-plus-circle text-danger"></i>
                                                <span class="font-italic">
                                                {{\App\Untils\Trans::Week($item["day"])}}:
                                                {{$item["start"]}} - {{$item["end"]}}
                                            </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span>Chưa có</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{--                <div class="mt-2">--}}
                {{--                    <button class="btn btn-success">Kết nối tài khoản</button>--}}
                {{--                    <button class="btn btn-primary">Sửa thông tin tài khoản</button>--}}
                {{--                    <a href="{{backpack_url("logout")}}" class="btn btn-dribbble">Đăng xuất</a>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
</div>
