<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    @yield('meta')

    <!--meta name="keywords" content="" /-->
    <title>@yield('title')</title>

    <link href="{!! asset('css/vendor/simple-line-icons.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/vendor/themify-icons.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/vendor/owl.carousel.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/vendor/animate.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/vendor/viewbox.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/vendor/bootstrap-slider.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/vendor/bootstrap-datetimepicker.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/vendor/bootstrap.min.css') !!}" rel="stylesheet">

    <link href="{!! asset('css/scss/main.css') !!}" rel="stylesheet">
    @yield('style')
    <script src="{!! asset('js/vendor/fontawesome-all.min.js') !!}"></script>

</head>
<body class="@yield('body_class')">
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=351909355157812";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div>
    @include('header')

    @yield('content')

    @include('footer')
</div>

<!-- JavaScript

   ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="{!! asset('js/vendor/jquery.min.js') !!}"></script>
<script src="{!! asset('js/vendor/moment.js') !!}"></script>
<script src="{!! asset('js/vendor/bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/vendor/bootstrap-slider.min.js') !!}"></script>
<script src="{!! asset('js/vendor/bootstrap-datetimepicker.min.js') !!}"></script>
<script src="{!! asset('js/vendor/owl.carousel.min.js') !!}"></script>
<script src="{!! asset('js/vendor/navigation.js') !!}"></script>
<script src="{!! asset('js/vendor/modernizr.js') !!}"></script>
<script src="{!! asset('js/vendor/jqueryvalidation.js') !!}"></script>
<script src="{!! asset('js/vendor/jquery.viewbox.min.js') !!}"></script>
<script src="{!! asset('js/vendor/masonry.min.js') !!}"></script>
<script src="{!! asset('js/vendor/imagesloaded.js') !!}"></script>
<script src="{!! asset('js/vendor/jquery.waypoints.min.js') !!}"></script>
<script src="{!! asset('js/vendor/jquery.sticky-kit.min.js') !!}"></script>

<script src="{!! asset('js/main.js') !!}"></script>
@yield('script')
</body>
</html>
