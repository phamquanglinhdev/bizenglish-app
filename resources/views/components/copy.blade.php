<a href="#" class="btn btn-sm btn-link" onclick="copy_{{$slug}}()"><i class="la la-link"></i>
    {{trans("backpack::crud.get_book_link")}}
</a>
<script>
    function copy_{{$slug}}() {
        var temp = "{{route("show-book",$slug)}}";
        document.execCommand("copy");
        navigator.clipboard.writeText("AAAA")
        alert("Copy link thành công !");
    }


</script>
