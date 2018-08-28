<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Sample App') - Laravel 入门教程</title>
    <link rel="stylesheet" href="/css/web.css">
    <link rel="stylesheet" href="/bootstrap/bootstrap.min.css">
    <script src="/js/jquery.js"></script>
    <script src="/bootstrap/bootstrap.min.js"></script>

</head>
<body>
@include('layouts._header')

<div class="container">
    <div class="col-md-offset-1 col-md-10" style=" margin-top: 80px;">
        @include('shared._messages')
        @yield('content')
        @include('layouts._footer')
    </div>
</div>
</body>
</html>