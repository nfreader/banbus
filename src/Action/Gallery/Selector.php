<?php

namespace App\Action\Gallery;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Gallery\Service\GetServers as Service;
use App\Data\Payload;

class Selector extends Action
{
    private $service;

    public function __construct(Responder $responder, Service $service)
    {
        parent::__construct($responder);
        $this->service = $service;
    }

    public function action(array $args = []): Payload
    {
        return $this->service->getServerList();
    }
}
