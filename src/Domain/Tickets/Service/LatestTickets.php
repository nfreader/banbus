<?php

namespace App\Domain\Tickets\Service;

use App\Service\Service;
use App\Factory\SettingsFactory;
use App\Domain\Tickets\Repository\LatestTicketRepository as Repository;
use App\Domain\Tickets\Factory\TicketFactory;
use App\Domain\User\Data\User;

class LatestTickets extends Service
{
    private $currentUser;
    private $ticketFactory;

    public function __construct(
        Repository $ticketRepo,
        SettingsFactory $settings,
        TicketFactory $ticketFactory
    ) {
        $this->ticketRepo = $ticketRepo;
        $this->ticketFactory = $ticketFactory;
        parent::__construct($settings);
    }

    public function initTicketStream()
    {
        $tickets = $this->ticketRepo->getLatestTickets()->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        $this->payload->setTemplate('tickets/live.twig');
        $this->payload->addData(
            'tickets',
            $tickets
        );
        return $this->payload;
    }
    public function pollForNewTickets(int $id){
        $tickets = $this->ticketRepo->getTicketsSinceId($id)->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        $this->payload->addData(
            'tickets',
            $tickets
        );
        return $this->payload->asJson();
    }
}