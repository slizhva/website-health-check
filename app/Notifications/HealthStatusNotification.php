<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class HealthStatusNotification extends Notification
{
    use Queueable;

    private array $healthData;

    public function __construct(array $healthData)
    {
        $this->healthData = $healthData;
    }

    public function via():array
    {
        return ['telegram'];
    }

    public function toTelegram(mixed $notifiable)
    {
        return TelegramMessage::create()
            ->to($this->healthData['to'])
            ->content($this->healthData['content']);
    }

    public function toArray(mixed $notifiable):array
    {
        return [
            //
        ];
    }
}
