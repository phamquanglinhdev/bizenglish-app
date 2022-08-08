<a href="#" class="btn btn-sm btn-link" onclick="copy_{{$slug}}()"><i class="la la-link"></i> Lấy link bộ sách</a>
<script>
    function copy_{{$slug}}(){
        navigator.clipboard.writeText("{{route("show-book",$slug)}}");
        alert("Copy link thành công !");
    }
</script>
