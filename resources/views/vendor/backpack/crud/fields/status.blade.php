{{--<!-- select -->--}}
{{--@php--}}
{{--    $current_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';--}}
{{--    $entity_model = $crud->getRelationModel($field['entity'],  - 1);--}}
{{--    $field['allows_null'] = $field['allows_null'] ?? $entity_model::isColumnNullable($field['name']);--}}

{{--    //if it's part of a relationship here we have the full related model, we want the key.--}}
{{--    if (is_object($current_value) && is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model') ) {--}}
{{--    $current_value = $current_value->getKey();--}}
{{--    }--}}

{{--    if (!isset($field['options'])) {--}}
{{--    $options = $field['model']::all();--}}
{{--    } else {--}}
{{--    $options = call_user_func($field['options'], $field['model']::query());--}}
{{--    }--}}
{{--@endphp--}}

@include('crud::fields.inc.wrapper_start')

<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

<div class="input-group">
    <select name="{{ $field['name'] }}">
        <option value="" selected>Học viên và giáo viên vào đúng giờ.</option>
    </select>
    <div class="input-group-append">
        <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
{{--        <button class="btn btn-outline-secondary" type="button">Button</button>--}}
    </div>
</div>


{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif

@include('crud::fields.inc.wrapper_end')
