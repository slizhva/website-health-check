<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Links;
use App\Models\User;

class SendHealthStatistics extends Command
{
    protected $signature = 'health:statistics';
    protected $description = 'Send health statistics';

    protected function sendMessageToTelegram($token, $chatID, $message) {
        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
        $url = $url . "&text=" . urlencode($message);
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        if(!curl_exec($ch)) {
            echo curl_error($ch);
            die();
        }
        curl_close($ch);
    }

    public function handle():int
    {
        $links = Links
            ::orderBy('user', 'asc')
            ->get(['link', 'status'])
            ->toArray();

        $statistics = '';
        foreach ($links as $link) {
            $statistics .=
                $link['status'] ? 'SUCCESS: ' : 'ERROR: ' .
                $link['link'] . "\n\n";
        }

        $this->sendMessageToTelegram(
            env('TELEGRAM_API_TOKEN'),
            env('TELEGRAM_CHAT_ID'),
            $statistics
        );

        return Command::SUCCESS;
    }
}
