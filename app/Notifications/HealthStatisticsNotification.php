<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class HealthStatisticsNotification extends Notification
{
    use Queueable;

    private array $statisticsData;

    public function __construct(array $statisticsData)
    {
        $this->statisticsData = $statisticsData;
    }

    public function via():array
    {
        return ['telegram'];
    }

    public function toTelegram(mixed $notifiable)
    {
        return TelegramMessage::create()
            ->to($this->statisticsData['to'])
            ->content($this->statisticsData['content']);
    }

    public function toArray(mixed $notifiable):array
    {
        return [
            //
        ];
    }
}
