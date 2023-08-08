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

        .card {
            box-shadow: none;
            border: 1px solid rgba(2, 37, 79, 0.2);
        }
    </style>
@endsection

@section('main')
    <main class="mt-6">
        <div class="container">
            @foreach($orders as $order)
                <div class="card mt-6 p-0">
                    <div class="ticket">
                        <div class="tk-num flex-center">{{$order->schedule->train->number}}</div>
                        <div class="tk-left p-2">
                            <div class="tk-info">
                                <div class="tk-from">
                                    <div class="tk-station">{{$order->from_station->name}}</div>
                                    <div
                                        class="tk-time">{{$order->schedule->departure_date.' '.$order->departure_time}}</div>
                                </div>
                                <div class="tk-mid">
                                    <div class="line"></div>
                                    <div class="dur-time">{{date('H\hi\m',strtotime($order->during_time))}}</div>
                                    <div class="line"></div>
                                </div>
                                <div class="tk-to">
                                    <div class="tk-stat ion">{{$order->to_station->name}}</div>
                                    <div
                                        class="tk-time">{{$order->schedule->departure_date.' '.$order->arrived_time}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="order-class flex-center ">
                            {{$order->ticket_class}}
                        </div>
                    </div>

                    <div class="p-2">
                        <div class="price-card">
                            <div class="card-title">Passengers</div>
                            <div class="price flex-center">
                                <div class="unit-price flex-center"><i class="fa fa-dollar"></i><span
                                        class="unit-price-num">{{number_format($order->unit_price/100,2)}}</span></div>
                                <div class="symbol"><i class="fa fa-close"></i></div>
                                <div class="ps-number flex-center"><i class="fa fa-male"></i><span
                                        class="person-num">{{$order->passengers->count()}}</span>
                                </div>
                                <div class="symbol">&equals;</div>
                                <div class="total-price"><i class="fa fa-dollar"></i><span
                                        class="total-price-num">{{number_format(($order->unit_price/100) * $order->passengers->count(),2)}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="passengers-list mt-2">
                            @foreach($order->orderPassengers as $op)
                                <div class="passenger card {{$op->passenger->status ==0 ? 'cancel-passenger' : ''}}">
                                    <div class="ps-name">{{$op->passenger->name}}</div>
                                    <div class="ps-id">{{$op->passenger->id_card}}</div>
                                    @if($op->passenger->status == 1 &&$op->status == 1 && strtotime(date('Y-m-d H:i:s'))+60*60*2 < strtotime($order->schedule->departure_date.$order->departure_time))
                                        <div class="ps-btns">
                                            <a href="{{url('/passenger/cancel/'.$op->id)}}" style="margin-right: 8px">
                                                <button class="btn-s">Cancel</button>
                                            </a>
                                            <a href="{{url('/passenger/change/'.$order->id.'/'.$op->id)}}">
                                                <button class="btn-s">Change</button>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endforeach
            <div class="flex-right mt-6">
                <a href="{{url('/')}}">
                    <button class="btn">Back To Main Page</button>
                </a>
            </div>
        </div>
    </main>
@endsection
