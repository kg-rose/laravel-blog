<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @yield('title', '默认标题') </title>

    <!-- 引用 css 和 js -->
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js"></script>
</head>

<body>
    @include('components.header')
    
    <div class="container">
        <div class="col-md-offset-1 col-md-10">
            @include('components.messages')
            @yield('content')
            @include('components.footer')
        </div>
    </div>
</body>

</html>