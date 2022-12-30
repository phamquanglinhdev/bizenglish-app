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
