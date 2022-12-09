<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Links;

class LinkCheckException
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $eventLabel = 'EXCEPTION';

    public function __construct(
        public Links $link
    ) {}

    public function broadcastOn():Channel|array {
        return new PrivateChannel('link-status');
    }
}
