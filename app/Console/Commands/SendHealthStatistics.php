<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\HealthStatisticsNotification;

use App\Models\Links;

class SendHealthStatistics extends Command
{
    protected $signature = 'health:statistics';
    protected $description = 'Send health statistics';

    public function handle():int
    {
        $links = Links
            ::orderBy('user', 'asc')
            ->get(['link', 'status'])
            ->toArray();

        $statistics = '';
        foreach ($links as $link) {
            $statistics .=
                ($link['status'] ? 'SUCCESS: ' : 'ERROR: ') .
                $link['link'] . "\n\n";
        }

        Notification::send('telegram', new HealthStatisticsNotification([
            'to' => env('TELEGRAM_CHAT_ID'),
            'content' => $statistics,
        ]));

        return Command::SUCCESS;
    }
}
