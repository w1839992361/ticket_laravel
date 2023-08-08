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

        .mask {
            transition: all 0.3s;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-size: 1.575em;
            opacity: 0;
        }

        .passenger .fa {
            transition: all 0.3s;
            cursor: pointer;
        }

        .passenger .fa:hover {
            transform: scale(1.1);
        }

        .passenger:hover .mask {
            opacity: 1;
        }

            .selected .fa-plus {
                display: none;
            }

            .passenger .fa-plus {
                margin-right: 20px;
            }
    </style>
@endsection

@section('main')
    <main class="mt-6">
        <div class="container">
            <div class="ticket br-8">
                <div class="tk-num flex-center">{{$train->number}}</div>
                <div class="tk-left p-2">
                    <div class="tk-info">
                        <div class="tk-from">
                            <div class="tk-station">{{$train->lines->first()->from_station->name}}</div>
                            <div class="tk-time">{{date('H:i',strtotime($train->lines->first()->departure_time))}}</div>
                        </div>
                        <div class="tk-mid">
                            <div class="line"></div>
                            <div class="dur-time">{{date('H\hi\m',strtotime($train->during_time))}}</div>
                            <div class="line"></div>
                        </div>
                        <div class="tk-to">
                            <div class="tk-station">{{$train->lines->last()->to_station->name}}</div>
                            <div class="tk-time">{{date('H:i',strtotime($train->lines->last()->arrived_time))}}</div>
                        </div>
                    </div>
                </div>
                <div class="order-class flex-center">
                    {{$seat_class}}
                </div>
            </div>
            <div class="card mt-6">
                <div class="card-title">Passengers</div>
                <div class="card-subtitle mt-2">Selected Passengers</div>
                <form class="passengers-list mt-2  selected-psg-list" style="min-height: 81px;"
                      action="{{url('/order/confirm')}}" method="post">
                    @csrf
                    <input type="hidden" name="unit_price" value="{{$train[$seat_class.'_cal_price']}}">
                    <input type="hidden" name="ticket_class" value="{{$seat_class}}">
                    <input type="hidden" name="from_station_id" value="{{$train->lines->first()->from_station_id}}">
                    <input type="hidden" name="to_station_id" value="{{$train->lines->last()->to_station_id}}">
                    <input type="hidden" name="departure_time" value="{{$train->lines->first()->departure_time}}">
                    <input type="hidden" name="arrived_time" value="{{$train->lines->last()->arrived_time}}">
                    <input type="hidden" name="during_time" value="{{ $train->during_time }}"/>
                    <input type="hidden" name="schedule_id" value="{{$train->schedules->last()->id}}">
                    @if(session()->exists('change'))
                        <div class="passenger card sel-passenger" data-id="{{session('change')['op']->passenger->id}}">
                            <input type="hidden" name="passenger_id[]"
                                   value="{{session('change')['op']->passenger->id}}">
                            <div class="ps-name">{{session('change')['op']->passenger->name}}</div>
                            <div class="ps-id">{{session('change')['op']->passenger->id_card}}</div>
                        </div>
                    @endif
                </form>
                @if(!session()->exists('change'))
                    <div class="card-subtitle mt-2">History Passengers</div>
                    <div class="passengers-list mt-2">
                        @foreach(\Illuminate\Support\Facades\Auth::user()->passengers as $p)
                            <div class="passenger history-passenger card" data-id="{{$p->id}}">
                                <input type="hidden" name="passenger_id[]" value="{{$p->id}}">
                                <div class="ps-name">{{$p->name}}</div>
                                <div class="ps-id">{{$p->id_card}}</div>
                                <div class="mask flex-center">
                                    <i class="fa fa-plus"></i>
                                    <i class="fa fa-trash"></i>
                                </div>
                            </div>
                        @endforeach

                        <div class=" ps-add-f flex-center br-8">
                            <div class="flex-center">Add a New Passenger</div>
                            <i class="fa fa-plus flex-center"></i>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card mt-6 price-card">
                <div class="card-title">Total Price</div>
                <div class="price flex-center">
                    <div class="unit-price flex-center"><i class="fa fa-dollar"></i><span
                            class="unit-price-num">{{$train[$seat_class.'_cal_price']}}</span>
                    </div>
                    <div class="symbol"><i class="fa fa-close"></i></div>
                    <div class="ps-number flex-center"><i class="fa fa-male"></i><span class="person-num">0</span></div>
                    <div class="symbol">&equals;</div>
                    <div class="total-price"><i class="fa fa-dollar"></i><span class="total-price-num">0</span></div>
                </div>
            </div>
            <div class="flex-right mt-6">
                <button class="btn" onclick="document.querySelector('.selected-psg-list').submit()"
                        style="margin-right: 8px">Buy
                </button>
                <a href="{{url('/order/cancel')}}">
                    <button class="btn">Cancel</button>
                </a>
            </div>
        </div>
    </main>


    <div class="modal">
        <form class="modal-body card bg-white login-form" action="{{ url('/passenger/add') }}" method="post">
            @csrf
            <div class="card-title">New Passenger</div>
            <input type="text" name="name" class="form-border" placeholder="Name"/>
            <input type="text" name="id_card" class="form-border" placeholder="Id Card"/>
            <div class="flex-space-between">
                <button class="btn add-btn">Add</button>
                <button class="btn close-btn" type="button">Close</button>
            </div>
        </form>
    </div>

    <script>
        $('.modal').hide();
        $('body').on('click', function (event)  {
            // 模态框隐藏
            if ($('.modal,.close-btn').is(event.target)) {
                $('.modal').fadeOut();
            }
        }).on('click', '.ps-add-f', function () {
            // 模态框显示
            $('.modal').fadeIn();
        }).on('click', '.fa-plus', function () {
            $(this).parents('.history-passenger').addClass('selected').clone().addClass('sel-passenger').appendTo('.selected-psg-list')
                .find('.fa-trash').removeClass('fa-trash').addClass('fa-remove');
            calPrice();
        }).on('click', '.fa-remove', function () {
            let psg_id = $(this).parents('.passenger').remove().attr('data-id');
            $(`.history-passenger[data-id=${psg_id}]`).removeClass('selected');
            calPrice();
        }).on('click', '.fa-trash', function () {
            let psd_id = $(this).parents('.history-passenger').attr('data-id');
            $.ajax({
                type: 'get',
                url: `{{url('/passenger/del')}}/${psd_id}`,
                success(val) {
                    $(`.passenger[data-id=${psd_id}]`).remove();
                    calPrice();
                }
            });
        });

        calPrice();

        function calPrice() {
            let number = $('.sel-passenger').length;
            $('.person-num').html(number);
            $('.total-price-num').html((parseInt($('.unit-price-num').html() * 100) * number) / 100);
        }

        $('form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(e.target);

            $.ajax({
                type: "post",
                url: "{{url('/passenger/add')}}",
                data: formData,
                contentType: false,
                processData: false,
                success(val) {
                    $('.modal').hide();

                    let psg_dom = $(`   <div class="passenger history-passenger card" data-id="${val.data.id}">
                                <input type="hidden" name="passenger_id[]" value="${val.data.id}">
                                <div class="ps-name">${val.data.name}</div>
                                <div class="ps-id">${val.data.id_card}</div>
                                <div class="mask flex-center">
                                    <i class="fa fa-plus"></i>
                                    <i class="fa fa-trash"></i>
                                </div>
                            </div>`);
                    $('.ps-add-f').before(psg_dom);
                }
            })
            e.target.reset();
        })
    </script>
@endsection
