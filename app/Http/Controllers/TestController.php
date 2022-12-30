<?php

namespace App\Http\Controllers;

use App\Notifications\SlackNotification;
use Illuminate\Support\Facades\Notification;

class TestController extends Controller
{
    public function index()
    {
        Notification::route('slack', "https://hooks.slack.com/services/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")
            ->notify(new SlackNotification("Xin chào"));
    }
}
