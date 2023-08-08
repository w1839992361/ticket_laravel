@extends("layouts.app")
@section('style')
    <style>
        body {
            padding-bottom: 0;
        }

        footer {
            color: #FFF;
        }

        .title {
            text-shadow: 0 0 5px rgba(60, 202, 209, .5);
        }

    </style>
@endsection

@section('app-content')
    <div class="mt-2h">
        <div class="container">
            <div class="title">Book&nbsp;&nbsp;Your&nbsp;&nbsp;Train</div>
            <form class="search-form br-8 p-2 mt-3" action="{{url('search')}}" method="get">
                <div class="form-inside">
                    <select name="from" class="form-ele" required>
                        <option value="" selected>From</option>
                        @foreach(\App\Models\City::all() as $city)
                            <option value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                    <select name="to" class="form-ele" required>
                        <option value="" selected>To</option>
                        @foreach(\App\Models\City::all() as $city)
                            <option value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                    <input type="date" class="form-ele" name="date" min="{{date('Y-m-d')}}" required/>
                    <select name="seat" class="form-ele">
                        <option value="second" selected>Second Class</option>
                        <option value="first">First Class</option>
                    </select>
                    <input type="number" name="passengers" class="form-ele" placeholder="Passengers"/>
                    <button class="form-ele">Search</button>
                </div>
            </form>
        </div>
    </div>
@endsection
