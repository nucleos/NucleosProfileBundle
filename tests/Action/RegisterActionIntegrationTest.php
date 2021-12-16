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

namespace Nucleos\ProfileBundle\Tests\Action;

use Nucleos\ProfileBundle\Tests\App\AppKernel;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegisterActionIntegrationTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLogin(): void
    {
        $this->client->followRedirects(true);
        $this->client->request('GET', '/register');

        static::assertResponseStatusCodeSame(200);
    }

    protected static function getKernelClass(): string
    {
        return AppKernel::class;
    }
}
