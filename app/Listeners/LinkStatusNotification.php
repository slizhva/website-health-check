<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Notification;

use App\Events\Event;
use App\Notifications\HealthStatusNotification;

class LinkStatusNotification
{
    public function handle(Event $event):void {
        Notification::send('telegram', new HealthStatusNotification([
            'to' => env('TELEGRAM_CHAT_ID'),
            'content' => $event->eventLabel . ': ' . $event->link->url,
        ]));
    }
}
