<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <title>Hello, world!</title>
</head>
<body style="display: flex">
@if(session("data")!=null)
    @foreach(session("data") as $file)
        <script>
            window.ReactNativeWebView.postMessage("{{$file}}")
        </script>
    @endforeach
@endif
<div class="p-2">
    <form id="uploadForm" method="post" enctype="multipart/form-data" action="{{route("uploadApp")}}">
        @csrf
        <div class="form-group">
            <div class="pick">
                <input type="file" class="form-control-file p-5" id="attachments" name="attachments[]">
            </div>
            <button class="btn btn-primary mt-2 w-100" onclick="sw()">
                <img id="loading" class="d-none"
                     src="https://www.superiorlawncareusa.com/wp-content/uploads/2020/05/loading-gif-png-5.gif"/>
                <span id="static">TẢI TỆP LÊN</span>
            </button>
        </div>
    </form>
</div>
<style>
    .pick {
        border: 3px dashed #0a53be;
    }

    img {
        width: 40px;
        height: 40px;
    }

    ::file-selector-button {
        display: none;
    }
</style>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function sw() {
        $("#loading").removeClass("d-none")
        $("#static").addClass("d-none")
    }
</script>
</body>
</html>
