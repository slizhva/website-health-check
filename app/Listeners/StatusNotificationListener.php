<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Notification;

use App\Events\Event;
use App\Notifications\StatusNotification;

class StatusNotificationListener
{
    public function handle(Event $event):void {
        Notification::send('telegram', new StatusNotification([
            'to' => env('TELEGRAM_CHAT_ID'),
            'content' => $event->info,
        ]));
    }
}
