<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

use App\Models\Links;
use App\Notifications\HealthStatusNotification;

class CheckHealth extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Check websites health';

    private function executeCurl(object $request): bool|string
    {
        if (!$request) { return false; }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array_map('trim', explode("\n", $request->header)));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->type);
        $state_result = curl_exec($curl);
        curl_close($curl);

        return $state_result;
    }

    public function handle():int
    {
        $links = Links
            ::get(['id', 'user', 'url', 'success_content', 'status']);

        foreach ($links as $link) {
            try {
                $linkContent = file_get_contents($link->url);
                if (str_contains($linkContent, $link['success_content'])) {
                    Links::where('id', $link->id)->update(['status' => Links::STATUS_AVAILABLE]);
                } else {
                    Links::where('id', $link->id)->update(['status' => Links::STATUS_UNAVAILABLE]);

                    Notification::send('telegram', new HealthStatusNotification([
                        'to' => env('TELEGRAM_CHAT_ID'),
                        'content' => 'ERROR: ' . $link->url,
                    ]));

                    $response = $this->executeCurl($link->error_command);
                    $status = null;
                    $name = null;
                    try {
                        $result = json_decode($response, false);
                        $status = $result->status;
                        $name = $result->name;
                    } catch (Exception) {}
                    if ($status === "stopped") {
                        Notification::send('telegram', new HealthStatusNotification([
                            'to' => env('TELEGRAM_CHAT_ID'),
                            'content' => ($name ?: $link)  . ' server restarted!',
                        ]));
                    }
                }
            } catch (Exception) {
                Links::where('id', $link->id)->update(['status' => Links::STATUS_PENDING]);
                Notification::send('telegram', new HealthStatusNotification([
                    'to' => env('TELEGRAM_CHAT_ID'),
                    'content' => 'PENDING: ' . $link->url,
                ]));
            }
        }

        return Command::SUCCESS;
    }
}
