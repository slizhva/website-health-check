<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Notification;

use App\Events\LinkCheckException;
use App\Events\LinkUnavailable;
use App\Notifications\HealthStatusNotification;

class LinkStatusNotification
{
    public function handle(LinkUnavailable|LinkCheckException $event):void {
        Notification::send('telegram', new HealthStatusNotification([
            'to' => env('TELEGRAM_CHAT_ID'),
            'content' => $event->eventLabel . ': ' . $event->link->url,
        ]));
    }
}
