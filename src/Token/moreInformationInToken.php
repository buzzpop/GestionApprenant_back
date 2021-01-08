<?php


namespace App\Token;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class moreInformationInToken
{
    public function updateJwtData(JWTCreatedEvent $event)
    {
        // On récupère l'utilisateur
        $user = $event->getUser();

        // On enrichit le data du Token
        $data = $event->getData();

        $data['archived'] = $user->getIsArchived();

        $event->setData($data);
    }
}
