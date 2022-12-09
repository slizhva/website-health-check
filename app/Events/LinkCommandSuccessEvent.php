<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

class LinkCommandSuccessEvent extends Event
{
    public function __construct(
        public string $info
    ) {}

    public function broadcastOn():Channel|array {
        return new PrivateChannel('command-status');
    }
}
