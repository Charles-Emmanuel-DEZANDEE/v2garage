<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class AccountPasswordForgotEvent extends Event
{

    private $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

}