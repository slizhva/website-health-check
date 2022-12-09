<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

use App\Models\Links;
use App\Notifications\StatusNotification;

class SendHealthStatisticsCommand extends Command
{
    protected $signature = 'health:statistics';
    protected $description = 'Send health statistics';

    public function handle():int
    {
        $links = Links
            ::orderBy('user', 'asc')
            ->get(['url', 'status']);

        $statistics = '';
        foreach ($links as $link) {
            $statistics .=
                Links::STATUS_LABEL[$link->status] .
                ": " .
                $link->url . "\n\n";
        }

        Notification::send('telegram', new StatusNotification([
            'to' => env('TELEGRAM_CHAT_ID'),
            'content' => $statistics,
        ]));

        return Command::SUCCESS;
    }
}
