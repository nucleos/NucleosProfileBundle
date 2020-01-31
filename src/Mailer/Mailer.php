<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Mailer;

use Nucleos\UserBundle\Mailer\Mail\ResettingMail;
use Nucleos\UserBundle\Model\UserInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Mailer implements MailerInterface
{
    /**
     * @var SymfonyMailer
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var string|null
     */
    private $fromEmail;

    public function __construct(
        SymfonyMailer $mailer,
        TranslatorInterface $translator,
        UrlGeneratorInterface $router,
        ?string $fromEmail
    ) {
        $this->mailer     = $mailer;
        $this->translator = $translator;
        $this->router     = $router;
        $this->fromEmail  = $fromEmail;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationEmailMessage(UserInterface $user): void
    {
        $url  = $this->router->generate(
            'nucleos_profile_registration_confirm',
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $mail = (new ResettingMail())
            ->to(new Address($user->getEmail()))
            ->subject($this->translator->trans('registration.email.subject', [
                '%username%' => $user->getUsername(),
            ], 'NucleosProfileBundle'))
            ->setUser($user)
            ->setConfirmationUrl($url)
        ;

        if (null !== $this->fromEmail) {
            $mail->from(Address::fromString($this->fromEmail));
        }

        $this->mailer->send($mail);
    }
}
