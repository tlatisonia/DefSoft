<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use App\Event\AddProductEvent;
use App\Event\ListAllPersonnesEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class ProductListener
{
    public function __construct(private LoggerInterface $logger,MailerInterface $mailer)
     {
        $this->mailer =$mailer;
}
    public function onProductAdd(AddProductEvent $event){
        $this->logger->debug("je suis entrain d'ecouter Evenement product.add  et un produit vient d'etre ajouté  ".$event->getProduct()->getName());
       try {
        $email = (new Email())
            ->from('tlatisonia@gmail.com')
            ->to('admin@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('New Product')
           
            ->text("Un nouveau produit". $event->getProduct()->getName()."  vient d'etre créé");
            
        $this->mailer->send($email);
       } catch (TransportExceptionInterface $th ) {
       dd($th);
       }

       
    }


    
}