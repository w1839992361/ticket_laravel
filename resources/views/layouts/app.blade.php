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
    <link rel="stylesheet" href="{{asset('assets/icon/css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    @yield('style')
</head>
<body>
<div id="app">
    <header>
        <div class="container">
            <a class="logo" href="{{url('/')}}"><i class="fa fa-subway"></i>Railpack</a>
            <nav>
                @auth
                    <a href="{{url('/')}}">{{\Illuminate\Support\Facades\Auth::user()->username}}</a>
                    <a href="{{url('/order')}}">My Order</a>
                    <a href="javascript:void(document.querySelector('#logout').submit())">Logout</a>
                    <form action="{{route('logout')}}" method="post" id="logout" style="display: none">@csrf</form>
                @else
                    <a href="{{route('login')}}">Login</a>
                    <a href="{{route('register')}}">Register</a>
                @endauth
            </nav>
        </div>
    </header>
    @yield('app-content')
</div>
@if(session()->exists('message')||$errors->first())
    <div class="msg-content">
        <div class="alert bg-error">{{ session('message') }} {{$errors->first()}}</div>
    </div>
@endif


@yield('main')
<footer>
    <div class="container">
        &copy; 2019 Railpack.
    </div>
</footer>
</body>
</html>
