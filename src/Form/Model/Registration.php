<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Form\Model;

use Nucleos\UserBundle\Model\UserInterface;
use Nucleos\UserBundle\Model\UserManagerInterface;

class Registration
{
    protected ?string $username = null;

    protected ?string $email = null;

    protected ?string $plainPassword = null;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function toUser(UserManagerInterface $manager): UserInterface
    {
        $user = $manager->createUser();
        $user->setEnabled(true);
        $user->setUsername((string) $this->getUsername());
        $user->setEmail((string) $this->getEmail());
        $user->setPlainPassword((string) $this->getPlainPassword());

        return $user;
    }
}
