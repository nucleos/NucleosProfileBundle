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

use Nucleos\ProfileBundle\Event\GetResponseRegistrationEvent;
use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\GetResponseLoginEvent;
use Nucleos\UserBundle\Event\GetResponseUserEvent;
use Nucleos\UserBundle\Model\UserInterface;
use Nucleos\UserBundle\NucleosUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

final class AlreadyLoggedinListener implements EventSubscriberInterface
{
    private Security $security;

    private RouterInterface $router;

    /**
     * AlreadyLoggedinListener constructor.
     */
    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router   = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NucleosProfileEvents::REGISTRATION_INITIALIZE => 'checkRegistration',
            NucleosUserEvents::SECURITY_LOGIN_INITIALIZE  => 'checkLogin',
            NucleosUserEvents::SECURITY_LOGIN_COMPLETED   => 'redirectToProfile',
        ];
    }

    public function redirectToProfile(GetResponseUserEvent $event): void
    {
        $event->setResponse(new RedirectResponse($this->router->generate('nucleos_profile_profile_show')));
    }

    public function checkRegistration(GetResponseRegistrationEvent $event): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $event->setResponse($this->getRedirect($event->getRequest()));
    }

    public function checkLogin(GetResponseLoginEvent $event): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $event->setResponse($this->getRedirect($event->getRequest()));
    }

    private function getRedirect(?Request $request): RedirectResponse
    {
        $url = null;

        if (null !== $request) {
            $url = $request->server->get('HTTP_REFERER');

            if ($request->getUri() === $url) {
                $url = null;
            }
        }

        if (null === $url) {
            $url = $this->router->generate('nucleos_profile_profile_show');
        }

        return new RedirectResponse($url);
    }
}
