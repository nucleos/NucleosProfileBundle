<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Tests\App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nucleos\UserBundle\Entity\BaseGroup;
use Nucleos\UserBundle\Entity\BaseUser;
use Nucleos\UserBundle\Model\Group;

if (class_exists(BaseUser::class)) {
    abstract class InternalTestGroup extends BaseGroup {}
} else {
    abstract class InternalTestGroup extends Group {}
}

#[ORM\Entity]
#[ORM\Table(name: 'user__group')]
class TestGroup extends InternalTestGroup
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    protected ?int $id = null;
}
