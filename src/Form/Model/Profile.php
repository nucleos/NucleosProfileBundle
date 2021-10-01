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

use Nucleos\UserBundle\Model\LocaleAwareInterface;
use Nucleos\UserBundle\Model\UserInterface;

class Profile
{
    protected ?string $locale = null;

    protected ?string $timezone = null;

    public static function fromUser(UserInterface $user): self
    {
        $profile = new static();

        if ($user instanceof LocaleAwareInterface) {
            $profile->setLocale((string) $user->getLocale());
            $profile->setTimezone((string) $user->getTimezone());
        }

        return $profile;
    }

    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setTimezone(?string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function updateUser(UserInterface $user): void
    {
        if ($user instanceof LocaleAwareInterface) {
            $user->setLocale((string) $this->getLocale());
            $user->setTimezone((string) $this->getTimezone());
        }
    }
}
