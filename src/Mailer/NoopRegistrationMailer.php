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

use Nucleos\UserBundle\Model\UserInterface;

/**
 * This mailer does nothing.
 * It is used when the 'email' configuration is not set,
 * and allows to use this bundle without swiftmailer.
 */
final class NoopRegistrationMailer implements RegistrationMailer
{
    public function sendConfirmationEmailMessage(UserInterface $user): void
    {
    }
}
