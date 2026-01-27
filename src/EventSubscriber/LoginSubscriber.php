<?php

namespace App\EventSubscriber;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Class LoginSubscriber
 *
 * Listens to the LoginSuccessEvent and updates the user's last login date.
 */
class LoginSubscriber implements EventSubscriberInterface
{
    /**
     * @param EntityManagerInterface $em Doctrine entity manager
     */
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Handles a successful login event.
     *
     * Updates the last login date of the authenticated user.
     *
     * @param LoginSuccessEvent $event The login success event
     */
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        // Safety check: ensure a user object exists
        if (!$user instanceof User) {
            return;
        }

        // Update the last login date
        $user->setLastLoginAt(new DateTime());

        // Persist changes to the database
        $this->em->flush();
    }

    /**
     * Registers the events this subscriber listens to.
     *
     * @return array<string, array{0: string, 1: int}>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => ['onLoginSuccess', 100],
        ];
    }
}