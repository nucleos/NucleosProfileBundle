<?php

declare(strict_types=1);

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Tests\App\Entity;

use Nucleos\UserBundle\Model\User;

/**
 * @phpstan-extends User<\Nucleos\UserBundle\Model\GroupInterface>
 */
class TestUser extends User
{
}
