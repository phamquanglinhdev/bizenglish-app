{{-- regular object attribute --}}
@php
    $value = data_get($entry, $column['name']);
    $value = is_array($value) ? json_encode($value) : $value;

    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 40;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['prefix'].Str::limit($value, $column['limit'], '[...]').$column['suffix'];
@endphp

<span>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
    <a href="{{$value}}">
        <i class="lab la-google-drive"></i> Xem
    </a>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>
