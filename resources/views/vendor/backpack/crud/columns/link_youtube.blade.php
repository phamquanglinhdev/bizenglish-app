{{-- regular object attribute --}}
@php
    $value = data_get($entry, $column['name']);
    $value = is_array($value) ? json_encode($value) : $value;

    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 40;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    //$column['text'] = $column['prefix'].Str::limit(, $column['limit'], '[...]').$column['suffix'];
    $column['text'] =$value ?? "";
@endphp

<span>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
    @if($column["text"]!="")
        <a class="nav-link" href="{{url($column['text'])}}">
            <img
                src="https://banner2.cleanpng.com/20180701/ori/kisspng-youtube-computer-icons-logo-clip-art-5b3871d0eaddb7.296285981530425808962.jpg"
                style="width: 2em"
            >
        </a>
    @else
        -
    @endif
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>
