<?php

namespace App\Listeners;

use App\Notifications\HealthStatusNotification;
use Exception;

use App\Events\LinkUnavailable;
use Illuminate\Support\Facades\Notification;

class RunLinkCommand
{
    private function executeCurl(object $request): bool|string
    {
        if (!$request->url) { return false; }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->type ?? 'GET');
        if ($request->header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array_map('trim', explode("\n", $request->header)));
        }
        $state_result = curl_exec($curl);
        curl_close($curl);

        return $state_result;
    }

    public function handle(LinkUnavailable $event):void {
        $response = $this->executeCurl($event->link->error_command);
        try {
            $result = json_decode($response, false);
        } catch (Exception) {}
        $status = $result->status ?? null;
        $name = $result->name ?? null;
        if ($status === "stopped") {
            Notification::send('telegram', new HealthStatusNotification([
                'to' => env('TELEGRAM_CHAT_ID'),
                'content' => ($name ?: $event->link->url)  . ' server restarted!',
            ]));
        }
    }
}
