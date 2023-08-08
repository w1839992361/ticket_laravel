<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPassenger;
use App\Models\Train;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ActionController extends Controller
{
    //
    function search(Request $req)
    {
        if (Session::exists('change')) {
            $info = $req->only(['date', 'seat']);
            $info['from'] = Session::get('change')['from'];
            $info['to'] = Session::get('change')['to'];
            $info['passengers'] = 1;
        } else {
            $info = $req->only(['from', 'to', 'date', 'seat', 'passengers']);
        }

        $trains = [];

        $val = Validator::make($info, [
            'from' => 'required',
            'to' => 'required',
            'seat' => 'required|string|in:first,second',
            'passengers' => 'required|numeric|min:1'
        ]);

        if ($val->fails()) {
            return view('search')->with([
                'info' => $info,
                'trains' => $trains
            ]);
        }
        $train = new Train();
        $trains = $train->scopeThrough($info['from'], $info['to'], $info['date'])->get()->filter(function ($val) use ($info) {

            $from = $val->lines->firstWhere('from_city.id', $info['from']);
            $to = $val->lines->firstWhere('to_city.id', $info['to']);
            // 获取起始路线的上一条路线
            $pre_line = $val->lines->firstWhere('id', $from->id - 1);

            // 过滤掉没有经过的路线
            foreach ($val->lines as $key => $line) {
                if (strtotime($line->departure_time) < strtotime($from->departure_time) || strtotime($line->arrived_time) > strtotime($to->arrived_time)) {
                    $val->lines->forget($key);
                }
            }
            // 获取该班次经过的路线id
            $from_station_ids = $val->lines->pluck('from_station_id');
            $to_station_ids = $val->lines->pluck('to_station_id');
            $order_passenger = new OrderPassenger();
            $order_passenger_query = $order_passenger->newQuery();
            // 获取购买过该航班的所有人数
            $passenger = $order_passenger->scopeFilter($order_passenger_query, $val->schedules[0]->id, $from_station_ids, $to_station_ids)->get();
            foreach (['second', 'first'] as $seat_class) {
                $passenger_number = $passenger->where('order.ticket_class', $seat_class)->count();
                // 剩余票运算
                $val[$seat_class . '_remaining'] = $val[$seat_class . '_class_capacity'] - $passenger_number;
                // 价格运算
                $val[$seat_class . '_cal_price'] = $from[$seat_class . '_class_price'];
                if ($to->id != $from->id) {
                    $val[$seat_class . '_cal_price'] = $to[$seat_class . '_class_price'] - $from[$seat_class . '_class_price'];
                } else {
                    if ($pre_line) {
                        $val[$seat_class . '_cal_price'] = $to[$seat_class . '_class_price'] - $pre_line[$seat_class . '_class_price'];
                    }
                }
            }
            if (Session::exists('change')) {
                $unit_price = Session::get('change')['unit_price'];
                foreach (['second', 'first'] as $seat) {
                    $val[$seat . '_cal_price'] = (int)($val[$seat . '_cal_price'] * 100);
                    $val[$seat . '_cal_price'] = ($val[$seat . '_cal_price'] - $unit_price < 0 ? 0 : $val[$seat . '_cal_price'] - $unit_price) / 100;
                }
            }
            $val->during_time = date('H:i:s', strtotime($to->arrived_time) - strtotime($from->departure_time) - (60 * 60 * 8));
            return strtotime($info['date'] . $from->departure_time) > strtotime(date('Y-m-d H:i:s')) && strtotime($to->arrived_time) > strtotime($from->departure_time) && $info['passengers'] <= $val[$info['seat'] . '_remaining'];

//            return strtotime($info['date'] . $from->departure_time) > (strtotime(date('Y-m-d H:i:s')) + (60 * 60 * 8)) && strtotime($to->arrived_time) > strtotime($from->departure_time) && $info['passengers'] <= $val[$info['seat'] . '_remaining'];
        });
        Session::put('trains', $trains);
        return view('search')->with([
            'info' => $info,
            'trains' => $trains
        ]);
    }

    function orderStore(Request $req)
    {
        $info = $req->only(['unit_price', 'ticket_class', 'from_station_id', 'to_station_id', 'schedule_id', 'departure_time', 'arrived_time', 'during_time']);
        $passengers = $req->get('passenger_id');

        $info['unit_price']  *= 100;

        $val = Validator::make($info, [
            'unit_price' => 'required',
            'ticket_class' => 'required',
            'from_station_id' => 'required',
            'to_station_id' => 'required',
            'schedule_id' => 'required',
            'departure_time' => 'required',
            'arrived_time' => 'required',
            'during_time' => 'required',
        ]);

        if ($val->fails() || !count($passengers)) {
            return back()->with([
                'message' => 'Something errors!'
            ]);
        }

        $order = Auth::user()->orders()->save(new Order($info));

        foreach ($passengers as $p) {
            $order->orderPassengers()->save(new OrderPassenger(['passenger_id' => $p]));
        }

        Session::forget('trains');

        if (Session::exists('change')) {
            Session::get('change')['op']->status = 0;
            Session::get('change')['op']->save();
            Session::forget('change');
        }

        return redirect('/order/' . $order->id);
    }
}
