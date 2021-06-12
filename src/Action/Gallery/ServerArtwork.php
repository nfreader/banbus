<?php

namespace App\Action\Gallery;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Gallery\Service\ListArtwork as Service;
use App\Data\Payload;

class ServerArtwork extends Action
{
    private $service;

    public function __construct(Responder $responder, Service $service)
    {
        parent::__construct($responder);
        $this->service = $service;
    }

    public function action(array $args = []): Payload
    {
        // $payload = new Payload();
        // $payload->setTemplate('gallery/gallery.twig');
        // return $payload;
        return $this->service->getArtworkForServer($args['server']);
    }
}
