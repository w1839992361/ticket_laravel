<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPassenger;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PassengerController extends Controller
{
    //
    function addPassenger(Request $req)
    {
        $info = $req->only('name', 'id_card');

        $val = Validator::make($info, [
            "name" => 'required|string',
            "id_card" => "required|string"
        ]);

        if ($val->fails()) {
            return response()->json([
                "msg" => "failed"
            ], 500);
        }
        $info['status'] = 1;
        $user = Auth::user();
        $psg = $user->passengers()->save(new Passenger($info));
        return response()->json([
            'msg' => 'Create Success',
            'data' => $psg
        ]);
    }

    function delPassenger($id)
    {
        $psg = Passenger::where(['id' => $id, 'status' => 1])->first();
        if (!$psg) {
            return response()->json([
                'msg' => 'Can not found this passenger!'
            ]);
        }
        $psg->status = 0;
        $psg->save();

        return response()->json([
            "msg" => 'Delete Success!'
        ]);
    }

    function cancelPassenger($id)
    {
        $op = OrderPassenger::where(['id' => $id, 'status' => 1])->first();
        if (!$op) {
            return redirect('/order')->with([
                'message' => 'Something errors!'
            ]);
        }
        $op->status = 0;
        $op->save();

        return redirect('/order');
    }

    function changePassenger($order_id, $op_id)
    {
        $order = Order::find($order_id);
        $op = OrderPassenger::find($op_id);
        if (!$op || !$order) {
            return redirect('/order')->with([
                'messaged' => 'Something errors!'
            ]);
        }

        Session::put('change', [
            'from' => $order->from_station->city->id,
            'to' => $order->to_station->city->id,
            'unit_price' => $order->unit_price,
            'op' => $op
        ]);

        return redirect('/search/?date=' . $order->schedule->departure_date . '&seat=' . $order->ticket_class);
    }
}
