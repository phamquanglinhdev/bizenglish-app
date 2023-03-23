<div class="container-fluid mt-5">
    @if(backpack_user()->type<=1)
        <div class="h3">
            Nhật ký chăm sóc
        </div>
        <div class="container-fluid mt-3">
            @foreach($caring as $item)
                <div class="d-flex align-items-center item mb-2">
                    <div class="bg-success rounded-circle" style="width: 1em;height: 1em"></div>
                    <div class="ml-2">
                    <span class="font-weight-bold">
                        {{$item->Staff->name ?? "Admin"}}
                    </span>
                        <span class="font-italic">
                        ( {{\Carbon\Carbon::parse($item->date??$item->created_at)->isoFormat("DD/MM/YYYY")}} ):
                    </span>
                    </div>
                    <div class="ml-1">{{$item->note}}</div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-12">
                <hr>
                <div class="my-1 font-italic text-muted">{{backpack_user()->name}}</div>

                <form action="{{route("caring.store")}}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{$student->id}}">
                    <input type="hidden" name="staff_id" value="{{backpack_user()->id}}">
                    <input type="hidden" name="origin" value="bizenglish">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <input name="date" type="date" class="form-control"
                                   value="{{\Carbon\Carbon::parse(now())->isoFormat("YYYY-MM-DD hh:mm:ss")}}">
                        </div>
                        <input type="text" class="form-control" placeholder="Thêm nhật ký mới" name="note">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Thêm</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
