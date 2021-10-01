<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Event;

use Nucleos\UserBundle\Event\FormEvent;
use Nucleos\UserBundle\Model\UserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class UserFormEvent extends FormEvent
{
    private UserInterface $user;

    public function __construct(UserInterface $user, FormInterface $form, Request $request)
    {
        parent::__construct($form, $request);

        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
