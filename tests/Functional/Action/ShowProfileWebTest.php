<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Tests\Functional\Action;

use Nucleos\ProfileBundle\Action\ShowProfileAction;
use Nucleos\ProfileBundle\Tests\Functional\DoctrineSetupTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(ShowProfileAction::class)]
class ShowProfileWebTest extends WebTestCase
{
    use DoctrineSetupTrait;

    #[Test]
    public function execute(): void
    {
        $client = self::createClient();

        $this->persist(
            $user  = self::createUser(),
        );

        $client->loginUser($user);
        $client->request('GET', '/profile/');

        self::assertResponseIsSuccessful();

        self::assertSelectorTextContains('.nucleos_profile_user_show', $user->getUsername());
    }
}
