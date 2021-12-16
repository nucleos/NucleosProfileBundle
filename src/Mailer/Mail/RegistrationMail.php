<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Mailer\Mail;

use Nucleos\UserBundle\Model\UserInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

final class RegistrationMail extends TemplatedEmail
{
    private string $confirmationUrl;

    private UserInterface $user;

    public function __construct(Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);

        $this->textTemplate('@NucleosProfile/Registration/email.txt.twig');
        $this->htmlTemplate('@NucleosProfile/Registration/email.html.twig');
    }

    public function setConfirmationUrl(string $confirmationUrl): self
    {
        $this->confirmationUrl = $confirmationUrl;

        return $this;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConfirmationUrl(): string
    {
        return $this->confirmationUrl;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @return array<mixed>
     */
    public function getContext(): array
    {
        return array_merge([
            'user'             => $this->getUser(),
            'confirmationUrl'  => $this->getConfirmationUrl(),
        ], parent::getContext());
    }
}
