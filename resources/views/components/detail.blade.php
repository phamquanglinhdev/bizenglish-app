<a href="{{$route}}" class="btn btn-sm btn-link">

    @if(isset($title))
        <i class="la la-check-circle"></i> Xác nhận thông tin
    @else
        <i class="la la-eye"></i> {{trans('backpack::crud.info_button')}}
    @endif
</a>
