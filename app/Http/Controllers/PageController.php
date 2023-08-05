<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Driver\Session;

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
}
