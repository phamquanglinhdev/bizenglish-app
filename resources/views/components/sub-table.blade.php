@php
    $columns = $widget["data"]["columns"];
    $grades = $widget["data"]["grades"];
@endphp
<table
    id="subTable"
    class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2"
    data-has-details-row="0"
    data-has-bulk-actions="0"
    cellspacing="0">
    <thead>
    <tr>
        {{-- Table columns --}}
        @foreach ($columns as $column)
            <th
                data-visible="false"
                data-visible-in-table="false"
                data-can-be-visible-in-table="false"
                data-visible-in-modal="false"
                data-visible-in-export="true"
                data-force-export="true"
                data-visible-in-export="false"
                data-force-export="false"
            >
                {!! $column['label'] !!}
            </th>
        @endforeach


        <th data-orderable="false"
            data-visible-in-export="false"
        >{{ trans('backpack::crud.actions') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($grades as $grade)
        <tr>
            <td>
                <a href="{{backpack_url("/log?grade_id=$grade->id")}}">
                    {{ $grade->name }}
                </a>
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
                @foreach($grade->Student()->get() as $student)
                    <a href="{{route("admin.student.detail",$student->id)}}">
                        {{$student->name}}
                    </a>
                    ,
                @endforeach
            </td>
            <td>
                @foreach($grade->Teacher()->get() as $teacher)
                    <a href="{{route("admin.teacher.detail",$teacher->id)}}">
                        {{$teacher->name}}
                    </a>
                @endforeach
            </td>
            <td>
                @foreach($grade->Client()->get() as $student)
                    {{$student->name}},
                @endforeach
            </td>
            <td>
                <a class="nav-link" href="{{route("grade.edit",$grade->id)}}">
                    <i class="la la-edit">a</i>
                    {{trans("backpack::crud.edit")}}
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        {{-- Table columns --}}
        @foreach ($columns as $column)
            <th>
                {!! $column['label'] !!}
            </th>
        @endforeach
        <th>{{ trans('backpack::crud.actions') }}</th>
    </tr>
    </tfoot>
</table>
