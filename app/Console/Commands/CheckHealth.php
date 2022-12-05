<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\Models\Links;

class CheckHealth extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Check websites health';

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
            ::get(['id', 'user', 'link', 'success_content', 'status'])
            ->toArray();

        foreach ($links as $link) {
            try {
                $linkContent = file_get_contents($link['link']);
                if (str_contains($linkContent, $link['success_content'])) {
//                    Log::info('SUCCESS: ' . $link['link']);
                } else {
                    $this->sendMessageToTelegram(
                        env('TELEGRAM_API_TOKEN'),
                        env('TELEGRAM_CHAT_ID'),
                        'ERROR: ' . $link['link']
                    );
                }
            } catch (Exception $e) {
                $this->sendMessageToTelegram(
                    env('TELEGRAM_API_TOKEN'),
                    env('TELEGRAM_CHAT_ID'),
                    'ERROR: ' . $link['link']
                );
            }
        }

        return Command::SUCCESS;
    }
}
