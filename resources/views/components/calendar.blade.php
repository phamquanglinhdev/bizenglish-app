<div class="container-fluid my-5">
    @if($user!=null)
        @if($user->calendar()!==null)
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="bg-white rounded p-2 border h-100">
                        <div class="font-weight-bold h5 text-center mb-0 mt-2">Thứ hai</div>
                        <hr>
                        @forelse($user->calendar()->monday as $item)
                            <div class="d-flex align-items-center item mb-2">
                                <div class="bg-success rounded-circle" style="width: 1em;height: 1em"></div>
                                <div class="ml-2">
                    <span class="font-weight-bold">
                      Lớp <a class="nav-link d-inline p-0"
                             href="{{backpack_url("/grade/".$item->id."/show")}}">{{$item->name}}</a> :
                    </span>
                                    <span class="font-italic ml-2">
                         {{$item->value}}
                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">-Không có lịch-</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="bg-white rounded p-2 border h-100">
                        <div class="font-weight-bold h5 text-center mb-0 mt-2">Thứ ba</div>
                        <hr>
                        @forelse($user->calendar()->tuesday as $item)
                            <div class="d-flex align-items-center item mb-2">
                                <div class="bg-success rounded-circle" style="width: 1em;height: 1em"></div>
                                <div class="ml-2">
                    <span class="font-weight-bold">
                      Lớp <a class="nav-link d-inline p-0"
                             href="{{backpack_url("/grade/".$item->id."/show")}}">{{$item->name}}</a> :
                    </span>
                                    <span class="font-italic ml-2">
                         {{$item->value}}
                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">-Không có lịch-</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="bg-white rounded p-2 border h-100">
                        <div class="font-weight-bold h5 text-center mb-0 mt-2">Thứ tư</div>
                        <hr>
                        @forelse($user->calendar()->wednesday as $item)
                            <div class="d-flex align-items-center item mb-2">
                                <div class="bg-success rounded-circle" style="width: 1em;height: 1em"></div>
                                <div class="ml-2">
                    <span class="font-weight-bold">
                      Lớp <a class="nav-link d-inline p-0"
                             href="{{backpack_url("/grade/".$item->id."/show")}}">{{$item->name}}</a> :
                    </span>
                                    <span class="font-italic ml-2">
                         {{$item->value}}
                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">-Không có lịch-</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="bg-white rounded p-2 border h-100">
                        <div class="font-weight-bold h5 text-center mb-0 mt-2">Thứ năm</div>
                        <hr>
                        @forelse($user->calendar()->thursday as $item)
                            <div class="d-flex align-items-center item mb-2">
                                <div class="bg-success rounded-circle" style="width: 1em;height: 1em"></div>
                                <div class="ml-2">
                    <span class="font-weight-bold">
                      Lớp <a class="nav-link d-inline p-0"
                             href="{{backpack_url("/grade/".$item->id."/show")}}">{{$item->name}}</a> :
                    </span>
                                    <span class="font-italic ml-2">
                         {{$item->value}}
                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">-Không có lịch-</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="bg-white rounded p-2 border h-100">
                        <div class="font-weight-bold h5 text-center mb-0 mt-2">Thứ sáu</div>
                        <hr>
                        @forelse($user->calendar()->friday as $item)
                            <div class="d-flex align-items-center item mb-2">
                                <div class="bg-success rounded-circle" style="width: 1em;height: 1em"></div>
                                <div class="ml-2">
                    <span class="font-weight-bold">
                      Lớp <a class="nav-link d-inline p-0"
                             href="{{backpack_url("/grade/".$item->id."/show")}}">{{$item->name}}</a> :
                    </span>
                                    <span class="font-italic ml-2">
                         {{$item->value}}
                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">-Không có lịch-</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="bg-white rounded p-2 border h-100">
                        <div class="font-weight-bold h5 text-center mb-0 mt-2">Thứ bảy</div>
                        <hr>
                        @forelse($user->calendar()->saturday as $item)
                            <div class="d-flex align-items-center item mb-2">
                                <div class="bg-success rounded-circle" style="width: 1em;height: 1em"></div>
                                <div class="ml-2">
                    <span class="font-weight-bold">
                      Lớp <a class="nav-link d-inline p-0"
                             href="{{backpack_url("/grade/".$item->id."/show")}}">{{$item->name}}</a> :
                    </span>
                                    <span class="font-italic ml-2">
                         {{$item->value}}
                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">-Không có lịch-</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="bg-white rounded p-2 border h-100">
                        <div class="font-weight-bold h5 text-center mb-0 mt-2">Chủ nhật</div>
                        <hr>
                        @forelse($user->calendar()->sunday as $item)
                            <div class="d-flex align-items-center item mb-2">
                                <div class="bg-success rounded-circle" style="width: 1em;height: 1em"></div>
                                <div class="ml-2">
                    <span class="font-weight-bold">
                      Lớp <a class="nav-link d-inline p-0"
                             href="{{backpack_url("/grade/".$item->id."/show")}}">{{$item->name}}</a> :
                    </span>
                                    <span class="font-italic ml-2">
                        {{$item->value}}
                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">-Không có lịch-</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
