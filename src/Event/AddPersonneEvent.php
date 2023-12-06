<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;


class AddPersonneEvent extends Event
{
    const ADD_PERSONNE_EVENT = 'personne.add';

    public function __construct(private User $personne) {}

    public function getUser(): User {
        return $this->personne;
    }

}