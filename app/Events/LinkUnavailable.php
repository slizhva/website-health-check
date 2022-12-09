<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

use App\Models\Links;

class LinkUnavailable extends Event
{
    public string $eventLabel = 'UNAVAILABLE';

    public function __construct(
        public Links $link
    ) {}

    public function broadcastOn():Channel|array {
        return new PrivateChannel('link-status');
    }
}
