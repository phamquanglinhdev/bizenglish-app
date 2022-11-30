<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no"/>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-cyan{
            background: #17a2b8!important;
        }
        a {
            text-decoration: none !important;
            color: #0b4d75 !important;
        }

        thead {
            position: sticky;
            top: 0;
        }
        @keyframes fadeOut {
            0% {opacity: 1;}
            100% {opacity: 0;}
        }
        .preloader {
            align-items: center;
            background: #FFF;
            display: flex;
            height: 100vh;
            justify-content: center;
            left: 0;
            position: fixed;
            top: 0;
            transition: opacity 0.2s linear;
            width: 100%;
            z-index: 9999;
            opacity: 1;
            animation: fadeOut 4s ease;

        }

    </style>
    <title>BizEnglish Education</title>
</head>
<body>
<div class="preloader">
    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif?20151024034921" alt="spinner">
</div>
@yield("content")
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function hide() {
        myPreloader.style.display = "none"
    }

    const myPreloader = document.querySelector('.preloader');
    window.addEventListener('load', () => {
        setInterval(hide, 1000)

    });
</script>
</body>
</html>
