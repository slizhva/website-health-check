<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

use App\Models\Links;
use App\Events\LinkUnavailableEvent;
use App\Events\LinkCheckExceptionEvent;
use Symfony\Component\Console\Command\Command as ConsoleCommand;

class CheckHealthCommand extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Check websites health';

    public function handle(): int
    {
        $links = Links::get(['id', 'user', 'name', 'url', 'header', 'success_content', 'status']);

        foreach ($links as $link) {
            try {
                $curl = curl_init();

                $curlOptions = [
                    CURLOPT_URL => $link['url'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 20,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ];

                if ($header = trim($link['header'])) {
                    $curlOptions[CURLOPT_HTTPHEADER] = explode("\n", $header);
                }

                curl_setopt_array($curl, $curlOptions);

                $response = curl_exec($curl);

                curl_close($curl);

                $isCheckSuccess = str_contains($response, $link['success_content']);
                Links::whereId($link['id'])->update([
                    'status' => $isCheckSuccess ? Links::STATUS_AVAILABLE : Links::STATUS_UNAVAILABLE,
                ]);

                LinkUnavailableEvent::dispatchIf(!$isCheckSuccess, $link);
            } catch (Exception) {
                Links::whereId($link['id'])->update(['status' => Links::STATUS_PENDING]);

                LinkCheckExceptionEvent::dispatch($link);
            }
        }

        return ConsoleCommand::SUCCESS;
    }
}
