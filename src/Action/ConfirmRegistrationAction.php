<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Action;

use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\FilterUserResponseEvent;
use Nucleos\UserBundle\Event\GetResponseUserEvent;
use Nucleos\UserBundle\Model\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class ConfirmRegistrationAction
{
    private EventDispatcherInterface $eventDispatcher;

    private UserManager $userManager;

    private RouterInterface $router;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UserManager $userManager,
        RouterInterface $router
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager     = $userManager;
        $this->router          = $router;
    }

    /**
     * Receive the confirmation token from user email provider, login the user.
     */
    public function __invoke(Request $request, string $token): Response
    {
        $userManager = $this->userManager;

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return new RedirectResponse($this->router->generate('nucleos_user_security_login'));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch($event, NucleosProfileEvents::REGISTRATION_CONFIRM);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url      = $this->router->generate('nucleos_profile_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $this->eventDispatcher->dispatch(
            new FilterUserResponseEvent($user, $request, $response),
            NucleosProfileEvents::REGISTRATION_CONFIRMED,
        );

        return $response;
    }
}
