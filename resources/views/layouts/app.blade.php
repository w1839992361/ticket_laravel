<!doctype html>
<html lang="{{str_replace('_','-',app()->getLocale())}}">
<head>
    <meta charset="UTF-8">
    <meta content="{{csrf_token()}}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name','Laravel')}}</title>
    {{--    style--}}
    <script src="{{asset('assets/jquery-3.3.1.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/icon/css/font-awesome.css)}}">
    <link rel="stylesheet" href="{{asset('assets/style.css)}}">
    @yield('style')
</head>
<body>

</body>
</html>
