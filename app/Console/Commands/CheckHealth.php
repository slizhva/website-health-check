<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

use App\Models\Links;
use App\Notifications\HealthStatisticsNotification;

class CheckHealth extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Check websites health';

    public function handle():int
    {
        $links = Links
            ::get(['id', 'user', 'link', 'success_content', 'status'])
            ->toArray();

        foreach ($links as $link) {
            try {
                $linkContent = file_get_contents($link['link']);
                if (str_contains($linkContent, $link['success_content'])) {
                    Links::where('id', $link['id'])->update(['status' => Links::STATUS_AVAILABLE]);
                } else {
                    Links::where('id', $link['id'])->update(['status' => Links::STATUS_UNAVAILABLE]);
                    Notification::send('telegram', new HealthStatisticsNotification([
                        'to' => env('TELEGRAM_CHAT_ID'),
                        'content' => 'ERROR: ' . $link['link'],
                    ]));
                }
            } catch (Exception $e) {
                Links::where('id', $link['id'])->update(['status' => Links::STATUS_UNAVAILABLE]);
                Notification::send('telegram', new HealthStatisticsNotification([
                    'to' => env('TELEGRAM_CHAT_ID'),
                    'content' => 'ERROR: ' . $link['link'],
                ]));
            }
        }

        return Command::SUCCESS;
    }
}
