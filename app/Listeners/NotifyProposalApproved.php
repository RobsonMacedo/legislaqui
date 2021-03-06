<?php

namespace App\Listeners;

use App\Events\ProposalApproved;

class NotifyProposalApproved extends Listener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProposalApproved $event)
    {
        $event->proposal->sendProposalApprovedEmail();
    }
}
