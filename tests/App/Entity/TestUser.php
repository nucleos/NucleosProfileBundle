<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Tests\App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nucleos\UserBundle\Entity\BaseUser;
use Nucleos\UserBundle\Model\GroupInterface;
use Nucleos\UserBundle\Model\User;

if (class_exists(BaseUser::class)) {
    /**
     * @phpstan-extends BaseUser<GroupInterface>
     */
    abstract class InternalTestUser extends BaseUser {}
} else {
    /**
     * @phpstan-extends User<GroupInterface>
     */
    abstract class InternalTestUser extends User {}
}

#[ORM\Entity]
#[ORM\Table(name: 'user__user')]
class TestUser extends InternalTestUser
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    protected int $id;

    /**
     * @var Collection<array-key, GroupInterface>
     */
    #[ORM\ManyToMany(targetEntity: TestGroup::class)]
    #[ORM\JoinTable(name: 'user__user_group')]
    protected Collection $groups;

    private static int $index = 1;

    public function __construct()
    {
        parent::__construct();

        $this->id     = self::$index++;
        $this->groups = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }
}
