<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use App\Event\AllProductEvent;

use App\Event\AddPersonneEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
      
        private LoggerInterface $logger
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            AllProductEvent::ALL_PRODUCTS_EVENT => ['onAllProductEvent', 5000]
        ];
    }

    public function onAllProductEvent(AllProductEvent $event) {
        $this->logger->debug('get all products'. $event->getProduct());
       // dd($event->getProduct());
        

    }
}