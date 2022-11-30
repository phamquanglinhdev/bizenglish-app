@extends("layouts.app-table")
@section("content")
    <div class="h2 pt-2 px-2 text-muted">
        <button type="button" class="h5 btn bg-cyan text-white" data-bs-toggle="modal" data-bs-target="#filter">Bộ lọc
        </button>
    </div>
    <table class="table table-bordered" style="width: 2000px">
        <thead class="bg-cyan text-white font-weight-bold">
        <tr>
            <th scope="col">Tên lớp</th>
            <th scope="col">Học viên</th>
            <th scope="col">Giáo viên</th>
            <th scope="col">Nhân viên quản lý</th>
            <th scope="col">Đối tác</th>
            <th scope="col">Link lớp</th>
            <th scope="col">Gói học phí</th>
            <th scope="col">Số phút</th>
            <th scope="col">Số phút còn lại</th>
            <th scope="col">Tài liệu</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Ngày tạo lớp</th>
            <th scope="col">Hành động</th>
        </tr>
        </thead>
        <tbody>
        @foreach($grades as $grade)
            <tr>
                <td>{{$grade->name}}</td>
                <td>
                    @foreach($grade->student as $student)
                        <div>
                            <a href="#">{{$student->name}}</a>
                        </div>
                    @endforeach
                </td>
                <td>
                    @foreach($grade->teacher as $teacher)
                        <div>
                            <a href="#">{{$teacher->name}}</a>
                        </div>
                    @endforeach
                </td>
                <td>
                    @foreach($grade->staff as $staff)
                        <div>
                            <a href="#">{{$staff->name}}</a>
                        </div>
                    @endforeach
                </td>
                <td>
                    @foreach($grade->client as $client)
                        <div>
                            <a href="#">{{$client->name}}</a>
                        </div>
                    @endforeach
                </td>
                <td>
                    <div class="text-center">
                        <a href="{{$grade->zoom??"#"}}">
                            <img src="https://www.cookiepro.com/wp-content/uploads/2020/06/zoom-icon-white-on-blue.png"
                                 style="width: 2em;height: 2em"
                                 class="rounded-circle">
                        </a>
                    </div>
                </td>
                <td>
                    {{$grade->pricing}}
                </td>
                <td>
                    {{$grade->minutes}}
                </td>
                <td>
                    {{$grade->getRs()}}
                </td>
                <td>
                    <div>
                        <a href="{{$grade->attachment}}">Link</a>
                    </div>
                </td>
                <td>
                    @if($grade->status==0)
                        {{trans("backpack::crud.teaching")}}
                    @endif
                    @if($grade->status==1)
                        {{trans("backpack::crud.taught")}}
                    @endif
                    @if($grade->status==2)
                        {{trans("backpack::crud.reserved")}}
                    @endif
                </td>
                <td>
                    {{$grade->created_at}}
                </td>
                <td>
                    <a class="nav-link" href="{{route("grade.edit",$grade->id)}}">
                        <i class="la la-edit"></i>
                        {{trans("backpack::crud.edit")}}
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <style>
        a {
            text-decoration: none !important;
            color: #0b4d75 !important;
        }

        thead {
            position: sticky;
            top: 0;
        }
    </style>
    <div class="modal fade" id="filter" tabindex="-1" aria-labelledby="filter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bộ lọc lớp học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route("app.grade.filter")}}">
                    <div class="modal-body">

                        <div class="mb-3">
                            <input type="text" name="staff_filter" class="form-control" placeholder="Nhân viên quản lý"
                                   value="{{$_REQUEST["staff_filter"]??""}}">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="student_filter" class="form-control" placeholder="Học viên"
                                   value="{{$_REQUEST["student_filter"]??""}}">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="teacher_filter" class="form-control" placeholder="Giáo viên"
                                   value="{{$_REQUEST["teacher_filter"]??""}}">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="client_filter" class="form-control" placeholder="Đối tác"
                                   value="{{$_REQUEST["client_filter"]??""}}">
                        </div>
                        @php
                            if(!isset($_REQUEST["status"])){
                                $_REQUEST["status"][] = [];
                            }
                        @endphp
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status[]" value="0" id="teaching"
                                   @if(in_array(0,$_REQUEST["status"]))
                                       checked
                                @endif
                            >
                            <label class="form-check-label" for="teaching">
                                Đang học
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status[]" value="1" id="stopped"
                                   @if(in_array(1,$_REQUEST["status"]))
                                       checked
                                @endif
                            >
                            <label class="form-check-label" for="stopped">
                                Đã kết thúc
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status[]" value="2" id="saved"
                                   @if(in_array(2,$_REQUEST["status"]))
                                       checked
                                @endif
                            >
                            <label class="form-check-label" for="saved">
                                Đang bảo lưu
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn bg-cyan text-white">Lọc dữ liệu</button>
                        <a href="{{route("app.grade")}}" type="button" class="btn text-white btn-danger">Xoá bộ lọc</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
