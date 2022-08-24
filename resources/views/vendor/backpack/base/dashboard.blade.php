@if(backpack_user()->type == -1)
    @include("admin-dashboard")
@endif
@if(backpack_user()->type == 0)
    @include("staff-detail",['data'=>\App\Models\Staff::find(backpack_user()->id)])
@endif
@if(backpack_user()->type == 1)
    {{--    @include("teacher-detail",['data'=>\App\Models\Teacher::find(backpack_user()->id)])--}}
    @php
        header("Location: ".url("admin/teaching?teacher_id=".backpack_user()->id),true,301);
        exit();
    @endphp
@endif
@if(backpack_user()->type == 3)
    @include("student-detail",['data'=>\App\Models\Student::find(backpack_user()->id)])
@endif
