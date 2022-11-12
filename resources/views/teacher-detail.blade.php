@php
    $data = $widget["data"]??$data;
@endphp
<style>

    .image_outer_container {
        margin-top: auto;
        margin-bottom: auto;
        border-radius: 50%;
    }

    .image_inner_container {
        border-radius: 50%;
        padding: 5px;
        background: #833ab4;
        background: -webkit-linear-gradient(to bottom, #459dfc, #fd1d1d, #833ab4);
        background: linear-gradient(to bottom, #459dfc, #20fa97, #833ab4);
    }

    .image_inner_container img {
        border-radius: 50%;
        border: 5px solid white;
    }

</style>
<div class="container-md-fluid mt-5">
    <div class="row">
        <div class="col-lg-2 col-12">
            <div class="d-flex">
                <div class="image_outer_container">
                    <div class="image_inner_container">
                        <img
                            src="{{$data->avatar??"https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png"}}"
                            class="w-100">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-10 col-12">
            <div class="mt-2 h3 text-muted font-italic">
                {{$data->name}}
            </div>
            <dìv class="card p-2 rounded">
                <div><i class="las la-mail-bulk text text-success"></i> <span
                        class="font-italic">{{$data->email}}</span></div>
                <div><i class="las la-address-book text-success "></i> <span
                        class="font-italic">{{$data->address??"Không có địa chỉ"}}</span></div>
                <div><i class="lab la-facebook text-success"></i> <span
                        class="font-italic"><a href="{{$data->facebook??""}}">{{$data->name}}</a></span></div>
                <div>
                    <i class="las la-info-circle text-success"></i> <span
                        class="font-italic">{{trans("backpack::crud.extra")}}</span>
                    <div class="mt-1">
                        @php
                            $extras = (array)$data->extra;
                        @endphp
                        @foreach($extras as $extra)
                            <div> - {!! $extra["name"].": ".$extra['info']  !!}</div>
                        @endforeach
                    </div>
                    @if(backpack_user()->id==$data->id)
                        <div><a href="{{route("teacher.edit",backpack_user()->id)}}"><i class="las la-edit"></i>
                                {{trans("backpack::crud.edit")}}
                            </a></div>
                    @endif
                </div>
            </dìv>
        </div>
    </div>
    <hr>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        $("i.la-eraser").parent().hide()
    });

</script>
