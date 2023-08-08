@extends('layouts.app')

@section('style')
    <style>
        #app {
            min-height: 0;
            padding-bottom: 0;
            background: #01567b;
        }

        .tk-num {
            width: 20%;
        }

        .tk-left {
            width: 80%;
        }

        .order-class {
            text-transform: capitalize;
        }
    </style>
@endsection

@section('main')
    <main class="mt-6">
        <div class="container">
            <div class="alert bg-success">Your order is successful</div>
            <div class="mt-6 ticket br-8">
                <div class="tk-num flex-center">{{$order->schedule->train->number}}</div>
                <div class="tk-left p-2">
                    <div class="tk-info">
                        <div class="tk-from">
                            <div class="tk-station">{{$order->from_station->name}}</div>
                            <div class="tk-time">{{date('H:i',strtotime($order->departure_time))}}</div>
                        </div>
                        <div class="tk-mid">
                            <div class="line"></div>
                            <div class="dur-time">{{date('H\hi\m',strtotime($order->during_time))}}</div>
                            <div class="line"></div>
                        </div>
                        <div class="tk-to">
                            <div class="tk-station">{{$order->to_station->name}}</div>
                            <div class="tk-time">{{date('H:i',strtotime($order->arrived_time))}}</div>
                        </div>
                    </div>
                </div>
                <div class="order-class flex-center">
                    {{$order->ticket_class}}
                </div>
            </div>
            <div class="card mt-6">
                <div class="card-title">All Passengers</div>
                <div class="passengers-list mt-2">
                    @foreach($order->passengers as $p)
                        <div class="passenger card">
                            <div class="ps-name">John</div>
                            <div class="ps-id">1005653205420</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card mt-6 price-card">
                <div class="card-title">Total Price</div>
                <div class="price flex-center">
                    <div class="unit-price flex-center"><i class="fa fa-dollar"></i><span
                            class="unit-price-num">{{$order->unit_price/100}}</span></div>
                    <div class="symbol"><i class="fa fa-close"></i></div>
                    <div class="ps-number flex-center"><i class="fa fa-male"></i><span
                            class="person-num">{{$order->passengers->count()}}</span></div>
                    <div class="symbol">&equals;</div>
                    <div class="total-price"><i class="fa fa-dollar"></i><span
                            class="total-price-num">{{($order->unit_price/100) *$order->passengers->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="flex-right mt-6">
                <a href="{{url('/order')}}">
                    <button class="btn">Back To Main Page</button>
                </a>
            </div>
        </div>
    </main>
@endsection
