@if(backpack_user()->type == 0)
    @include("admin-dashboard")
@endif
@if(backpack_user()->type == 1)
    @include("teacher-detail",['data'=>\App\Models\Teacher::find(backpack_user()->id)])
@endif
@if(backpack_user()->type == 3)
    @include("student-detail",['data'=>\App\Models\Student::find(backpack_user()->id)])
@endif
