<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\SlackNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SlackController extends Controller
{
    public function show()
    {
        return view("send-slack");
    }

    public function send(Request $request)
    {
        $message = $request->message;
        Notification::route('slack', "https://hooks.slack.com/services/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")
            ->notify(new SlackNotification($message));
        return redirect("/admin/slack");
    }
}
