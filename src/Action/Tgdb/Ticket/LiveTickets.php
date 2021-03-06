<?php

namespace App\Action\Tgdb\Ticket;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Tickets\Service\LatestTickets as Ticket;
use App\Data\Payload;

class LiveTickets extends Action
{
    private $ticket;

    public function __construct(Responder $responder, Ticket $ticket)
    {
        parent::__construct($responder);
        $this->ticket = $ticket;
    }

    public function action(array $args = []): Payload
    {
        return $this->ticket->initTicketStream();
    }
}
