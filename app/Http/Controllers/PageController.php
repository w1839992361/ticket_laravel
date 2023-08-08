<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    //

    function index()
    {
        if (Session::exists('change')) {
            Session::forget('change');
        }
        return view('index');
    }

    function myOrder()
    {
        $orders = Auth::user()->orders;
        return view('my-order')->with([
            'orders' => $orders
        ]);
    }

    function orderConfirm($key, $seat_class)
    {
        $trains = Session::get('trains') ?? Null;
        if (!$trains || !isset($trains[$key])) {
            return redirect('/')->with([
                'message' => 'Something errors!'
            ]);
        }

        return view('order-confirm')->with([
            'train' => $trains[$key],
            'seat_class' => $seat_class
        ]);
    }

    function orderCancel()
    {
        Session::forget(['changes', 'trains']);
        return redirect('/')->with([
            'message' => 'Order Cancel Success'
        ]);
    }

    function orderResult($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect('/')->with([
                'message' => 'Can not found order!'
            ]);
        }
        return view('order-result')->with([
            'order'=>$order
        ]);
    }
}
