<?php

namespace App\Events;

use App\Proposal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class ProposalChanged
 * @package App\Events
 */
class ProposalChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Proposal
     */
    public $proposal;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($proposal)
    {
        $this->proposal = $proposal;
    }
}