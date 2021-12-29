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

use Nucleos\ProfileBundle\Event\UserFormEvent;
use Nucleos\ProfileBundle\Mailer\RegistrationMailer;
use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Util\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class EmailConfirmationListener implements EventSubscriberInterface
{
    private RegistrationMailer $mailer;

    private TokenGenerator $tokenGenerator;

    private UrlGeneratorInterface $router;

    private RequestStack $requestStack;

    public function __construct(
        RegistrationMailer $mailer,
        TokenGenerator $tokenGenerator,
        UrlGeneratorInterface $router,
        RequestStack $requestStack
    ) {
        $this->mailer              = $mailer;
        $this->tokenGenerator      = $tokenGenerator;
        $this->router              = $router;
        $this->requestStack        = $requestStack;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NucleosProfileEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        ];
    }

    public function onRegistrationSuccess(UserFormEvent $event): void
    {
        $user = $event->getUser();
        $user->setEnabled(false);

        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }

        $this->mailer->sendConfirmationEmailMessage($user);

        $this->requestStack->getSession()->set('nucleos_profile_send_confirmation_email/email', $user->getEmail());

        $event->setResponse(
            new RedirectResponse($this->router->generate('nucleos_profile_registration_check_email'))
        );
    }
}
