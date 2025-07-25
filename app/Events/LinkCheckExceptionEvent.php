<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

use App\Models\Links;

class LinkCheckExceptionEvent extends Event
{
    public function __construct(
        public Links $link
    ) {
        $this->info = 'EXCEPTION' . ': ' . $this->link->url;

        if ($this->link->name) {
            $this->info .= ' (' . $this->link->name . ')';
        }
    }

    public function broadcastOn():Channel|array {
        return new PrivateChannel('link-status');
    }
}
