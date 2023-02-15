@include('crud::fields.inc.wrapper_start')


@include('crud::fields.inc.translatable_icon')
<label>{!! $field['label'] !!}</label>
<select
    class="{{ $field['name'] }}"
    name="{{ $field['name'] }}"
    style="width: 100%"
    data-init-function="bpFieldInitSelect2Element"
    multiple="multiple">
</select>

@include('crud::fields.inc.wrapper_end')
@push("crud_fields_scripts")
    <script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
    @if (app()->getLocale() !== 'en')
        <script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
    @endif
    <script>
        $(".{{ $field['name'] }}").select2({
            tags: true,
            placeholder: "Điền tên học viên",
            theme: "bootstrap"
        });
    </script>
    <style>

    </style>
@endpush
