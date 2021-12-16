<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\EventListener;

use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\FilterUserResponseEvent;
use Nucleos\UserBundle\Event\UserEvent;
use Nucleos\UserBundle\NucleosUserEvents;
use Nucleos\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class AuthenticationListener implements EventSubscriberInterface
{
    private LoginManagerInterface $loginManager;

    private string $firewallName;

    public function __construct(LoginManagerInterface $loginManager, string $firewallName)
    {
        $this->loginManager = $loginManager;
        $this->firewallName = $firewallName;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NucleosProfileEvents::REGISTRATION_COMPLETED => 'authenticate',
            NucleosProfileEvents::REGISTRATION_CONFIRMED => 'authenticate',
        ];
    }

    public function authenticate(FilterUserResponseEvent $event, string $eventName, EventDispatcherInterface $eventDispatcher): void
    {
        try {
            $this->loginManager->logInUser($this->firewallName, $event->getUser(), $event->getResponse());

            $eventDispatcher->dispatch(
                new UserEvent($event->getUser(), $event->getRequest()),
                NucleosUserEvents::SECURITY_IMPLICIT_LOGIN
            );
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}
