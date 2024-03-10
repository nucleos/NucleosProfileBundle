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

use Nucleos\ProfileBundle\Action\CheckRegistrationMailAction;
use Nucleos\ProfileBundle\Action\ConfirmRegistrationAction;
use Nucleos\ProfileBundle\Action\RegistrationAction;
use Nucleos\ProfileBundle\Action\RegistrationConfirmedAction;
use Nucleos\ProfileBundle\Tests\App\Entity\TestUser;
use Nucleos\ProfileBundle\Tests\Functional\DoctrineSetupTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(RegistrationAction::class)]
#[CoversClass(ConfirmRegistrationAction::class)]
#[CoversClass(CheckRegistrationMailAction::class)]
#[CoversClass(RegistrationConfirmedAction::class)]
class RegistrationWebTest extends WebTestCase
{
    use DoctrineSetupTrait;

    #[Test]
    public function execute(): void
    {
        $client = self::createClient();

        $this->performRegister($client);

        $user = $this->getEntityManager()->getRepository(TestUser::class)->findOneBy([
            'username' => 'new_username',
        ]);

        self::assertNotNull($user);
        self::assertFalse($user->isEnabled());
        self::assertNotNull($user->getConfirmationToken());

        $this->performConfirm($client, $user);
    }

    #[Test]
    public function executeWithLoggedInUser(): void
    {
        $client = self::createClient();

        $this->persist(
            $user  = self::createUser(),
        );

        $client->loginUser($user);
        $client->request('GET', '/register/');

        self::assertResponseRedirects('/profile/');
    }

    private function performRegister(KernelBrowser $client): void
    {
        $client->request('GET', '/register/');

        self::assertResponseIsSuccessful();

        $client->submitForm('registration_form[save]', [
            'registration_form[username]'              => 'new_username',
            'registration_form[email]'                 => 'new@example.com',
            'registration_form[plainPassword][first]'  => 'super_secret_password',
            'registration_form[plainPassword][second]' => 'super_secret_password',
        ]);

        self::assertResponseRedirects('/register/check-email');
    }

    private function performConfirm(KernelBrowser $client, TestUser $user): void
    {
        $client->request('GET', sprintf('/register/confirm/%s', $user->getConfirmationToken()));

        self::assertResponseRedirects('/register/confirmed');

        $user = $this->getEntityManager()->find(TestUser::class, $user->getId());

        self::assertNotNull($user);
        self::assertTrue($user->isEnabled());
        self::assertNull($user->getConfirmationToken());
    }
}
