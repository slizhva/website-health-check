<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

use App\Models\Links;
use App\Events\LinkUnavailable;
use App\Events\LinkCheckException;

class CheckHealth extends Command
{
    protected $signature = 'health:check';
    protected $description = 'Check websites health';

    public function handle():int {
        $links = Links
            ::get(['id', 'user', 'url', 'success_content', 'status']);

        foreach ($links as $link) {
            try {
                $isCheckSuccess = str_contains(file_get_contents($link->url), $link['success_content']);
                Links::where('id', $link->id)->update([
                    'status' => $isCheckSuccess ? Links::STATUS_AVAILABLE : Links::STATUS_UNAVAILABLE
                ]);

                LinkUnavailable::dispatchIf(!$isCheckSuccess, $link);
            } catch (Exception) {
                Links::where('id', $link->id)->update(['status' => Links::STATUS_PENDING]);

                LinkCheckException::dispatch($link);
            }
        }

        return Command::SUCCESS;
    }
}
