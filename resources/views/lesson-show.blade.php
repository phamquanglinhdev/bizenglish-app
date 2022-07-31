@extends(backpack_view("blank"))

@section('content')
    <link
        rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
        crossorigin="anonymous"
    />
    {{--    <link rel="stylesheet" href="css/style.css" />--}}
    <title>PDF Viewer</title>
    <script>
        const url = '{{url($lesson->pdf)}}';
    </script>

    <div class="row">
        <div class="col-md-6">
            <canvas id="pdf-render" class="w-100"></canvas>
        </div>
        <div class="col-md-6">
            <div class="top-bar">
                <button class="btn" id="prev-page">
                    <i class="fas fa-arrow-circle-left"></i> Trang trước
                </button>
                <button class="btn" id="next-page">
                    Trang tiếp <i class="fas fa-arrow-circle-right"></i>
                </button>
                <span class="page-info">
        Trang <span id="page-num"></span> / <span id="page-count"></span>
      </span>
            </div>
            <div class="audios">
                @php
                $audios = json_decode($lesson->audios,true);
                @endphp
                @foreach($audios as $audio)
                    <div class="font-weight-bold">Trang {{$audio["page"]}}</div>
                   <audio src=" {{url($audio["audio"])}}" controls></audio>
                @endforeach

            </div>
        </div>
    </div>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
{{--    <script src="{{asset("js/pdf-reader.js")}}"></script>--}}
    <script src="{{asset("js/pdf-reader.js")}}"></script>

{{--    <iframe src="{{url($lesson->pdf)}}" class="w-100 " style="height: 800px"></iframe>--}}
@endsection
