<?php

namespace App\Http\Controllers;

use App\Notifications\SlackNotification;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public static function index()
    {
        dispatch(new SlackNotification("Xin chao"));
    }
}
