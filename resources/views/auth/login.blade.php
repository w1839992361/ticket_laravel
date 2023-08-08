@extends('layouts.app')

@section('style')
    <style>
        #app {
            min-height: 0;
            padding-bottom: 0;
            background: #01567b;
        }

        .card {
            box-shadow: none;
            padding: 40px;
            border: 1px solid rgba(2, 37, 79, 0.2);
        }
    </style>
@endsection

@section('main')
    <div class="container mt-1h w-20">
        <div class="card">
            <form action="{{route('login')}}" class="login-form" method="post">
                @csrf
                <div class="card-title">Login</div>
                <input type="text" name="username" placeholder="Username" class="form-border" />
                <input type="password" name="password" placeholder="Password" class="form-border" />
                <div class="flex-space-between">
                    <button class="btn">Login</button>
                    <a href="{{route('register')}}" class="link">register</a>
                </div>
            </form>
        </div>
    </div>
@endsection
