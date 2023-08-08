@extends('layouts.app')

@section('style')
    <style>
        #app {
            min-height: 30%;
            background-position: 0 calc(50% - 100px);
            animation: start 3s;

        }

        .search-form {
            background: rgba(255, 255, 255, 0.9);
        }

        .this-ticket .fa-star {
            display: block;
        }

        .book-btn {
            position: relative;
            overflow: hidden;
        }

        .fa-star {
            display: none;
            position: absolute;
            font-size: 3em;
            color: #008caf;
            left: -20px;
            top: -15px;
            transform: rotateZ(50deg);
        }

        @keyframes start {
            0% {
                background-size: 200%;
                background-position: 100% calc(50% - 250px);
            }
            80% {
                background-size: 200%;
                background-position: 0 calc(50% - 250px);
            }

            100% {
                background-size: 100%;
                background-position: 0% calc(50% - 100px);
            }
        }
    </style>
@endsection

@section('app-content')
    <div class="mt-6">
        <div class="container">
            <form class="search-form br-8 p-2" action="{{url('search')}}" method="get">
                <div class="form-inside">
                    <select name="from" class="form-ele {{session()->exists('change') ? 'readonly' : ''}}" required>
                        @foreach(\App\Models\City::all() as $city)
                            <option
                                value="{{$city->id}}" {{isset($info['from']) && $info['from'] == $city->id ? 'selected':''}}>
                                {{$city->name}}
                            </option>
                        @endforeach
                    </select>
                    <select name="to" class="form-ele {{session()->exists('change') ? 'readonly' : ''}}" required>
                        @foreach(\App\Models\City::all() as $city)
                            <option
                                value="{{$city->id}}" {{isset($info['to']) && $info['to'] == $city->id ? 'selected':''}}>
                                {{$city->name}}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" class="form-ele" name="date" min="{{date('Y-m-d')}}" required
                           value="{{isset($info['date']) ? $info['date'] : ''}}"/>
                    <select name="seat" class="form-ele" required>
                        <option value="second" {{isset($info['seat']) &&  $info['seat'] == 'second' ? 'selected':''}}>
                            Second Class
                        </option>
                        <option value="first" {{isset($info['seat']) &&  $info['seat'] == 'first' ? 'selected':''}}>
                            First Class
                        </option>
                    </select>
                    <input type="number" class="form-ele {{session()->exists('change') ? 'readonly' : ''}}" placeholder="Passengers" required name="passengers"
                           value="{{isset($info['passengers']) ? $info['passengers']:''}}"/>
                    <button class="form-ele">Search</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('main')
    <main class="mt-6">
        <div class="container">
            <div class="ticket ticket-table-head br-8">
                <div class="tk-num flex-center">No.</div>
                <div class="tk-left p-2">
                </div>
                <div class="tk-right">
                    <div class="book-btn sec-btn">
                        <div class="tk-class">Second</div>
                    </div>
                    <div class="book-btn first-btn">
                        <div class="tk-class">First</div>
                    </div>
                </div>
            </div>
            @foreach($trains as $key=>$train)
                <div class="ticket br-8 mt-6">
                    <div class="tk-num flex-center">{{$train->number}}</div>
                    <div class="tk-left p-2">
                        <div class="tk-info">
                            <div class="tk-from">
                                <div class="tk-station">{{$train->lines->first()->from_station->name}}</div>
                                <div
                                    class="tk-time">{{date('H:i',strtotime($train->lines->first()->departure_time))}}</div>
                            </div>
                            <div class="tk-mid">
                                <div class="line"></div>
                                <div class="dur-time">{{ date('H\hi\m',strtotime($train->during_time)) }}</div>
                                <div class="line"></div>
                            </div>
                            <div class="tk-to">
                                <div class="tk-station">{{$train->lines->last()->to_station->name}}</div>
                                <div
                                    class="tk-time">{{date('H:i',strtotime($train->lines->last()->arrived_time))}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="tk-right">
                        @foreach(['second','first'] as $seat_class)
                            <a href="{{url('/order/confirm/'.$key.'/'.$seat_class)}}" role="button"
                               class="book-btn {{isset($info['seat']) && $info['seat'] == $seat_class ? 'this-ticket':''}}">
                                <i class="fa fa-star"></i>
                                <div class="tk-price flex-center"><i class="fa fa-dollar"></i><span
                                        class="ml-05">{{number_format($train[$seat_class.'_cal_price'],2)}}</span>
                                </div>
                                <div class="tk-remain flex-center"><i class="fa fa-ticket"></i><span
                                        class="ml-05">{{$train[$seat_class.'_remaining']}}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection
