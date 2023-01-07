{{--@if(backpack_user()->type == -1)--}}
{{--    @php--}}
{{--        header("Location: ".url("admin/edit-account-info"),true,301);--}}
{{--        exit();--}}
{{--    @endphp--}}
{{--@endif--}}
{{--@if(backpack_user()->type == 0)--}}
{{--    @include("staff-detail",['data'=>\App\Models\Staff::find(backpack_user()->id)])--}}
{{--@endif--}}
{{--@if(backpack_user()->type == 1)--}}
{{--    @include("teacher-detail",['data'=>\App\Models\Teacher::find(backpack_user()->id)])--}}
{{--    @php--}}
{{--        header("Location: ".url("admin/teaching?teacher_id=".backpack_user()->id),true,301);--}}
{{--        exit();--}}
{{--    @endphp--}}
{{--@endif--}}
{{--@if(backpack_user()->type == 2)--}}
{{--        @include("teacher-detail",['data'=>\App\Models\Teacher::find(backpack_user()->id)])--}}
{{--    @php--}}
{{--        header("Location: ".url("admin/teaching?client_id=".backpack_user()->id),true,301);--}}
{{--        exit();--}}
{{--    @endphp--}}
{{--@endif--}}
{{--@if(backpack_user()->type == 3)--}}
{{--    @include("student-detail",['data'=>\App\Models\Student::find(backpack_user()->id)])--}}
{{--@endif--}}
