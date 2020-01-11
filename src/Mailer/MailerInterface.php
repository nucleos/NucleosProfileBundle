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

interface MailerInterface
{
    /**
     * Send an email to a user to confirm the account creation.
     */
    public function sendConfirmationEmailMessage(UserInterface $user): void;
}
