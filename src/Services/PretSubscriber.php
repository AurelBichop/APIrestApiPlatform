<?php


namespace App\Services;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Pret;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface
{

    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW =>['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function getAuthenticatedUser(ViewEvent $event){

        $entity = $event->getControllerResult();//recupere l'entité qui a declenché l'évenement
        $method = $event->getRequest()->getMethod();//récupere la methode invoquée dans la request
        $adherent = $this->token->getToken()->getUser();//récupere l'adhérent actuellement connecté

        if($entity instanceof Pret && $method == "POST"){
            $entity->setAdherent($adherent); //on ecrit l'adhérent dans la propriété adhérent de l'entity Pret
        }

        return;
    }
}