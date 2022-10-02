<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Tests\DependencyInjection;

use Nucleos\ProfileBundle\DependencyInjection\NucleosProfileExtension;
use Nucleos\ProfileBundle\EventListener\FlashListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

final class NucleosProfileExtensionTest extends TestCase
{
    protected ContainerBuilder $configuration;

    public function testUserLoadUtilServiceWithDefaults(): void
    {
        $this->createEmptyConfiguration();

        $this->assertAlias('nucleos_profile.mailer.simple', 'nucleos_profile.mailer');
    }

    public function testUserLoadUtilService(): void
    {
        $this->createFullConfiguration();

        $this->assertAlias('acme_my.mailer', 'nucleos_profile.mailer');
    }

    public function testUserLoadFlashesByDefault(): void
    {
        $this->createEmptyConfiguration();

        $this->assertHasDefinition(FlashListener::class);
    }

    private function createEmptyConfiguration(): void
    {
        $this->configuration = new ContainerBuilder();
        $loader              = new NucleosProfileExtension();
        $loader->load([], $this->configuration);
        static::assertTrue($this->configuration->hasAlias('nucleos_profile.mailer'));
    }

    private function createFullConfiguration(): void
    {
        $this->configuration = new ContainerBuilder();
        $loader              = new NucleosProfileExtension();
        $config              = $this->getFullConfig();
        $loader->load([$config], $this->configuration);
        static::assertTrue($this->configuration->hasAlias('nucleos_profile.mailer'));
    }

    /**
     * @return array<mixed>
     */
    private function getFullConfig(): array
    {
        $yaml   = <<<'EOF'
registration:
    confirmation:
        from_email: register@acme.org
        enabled: true
service:
    mailer: acme_my.mailer
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    private function assertAlias(string $value, string $key): void
    {
        static::assertSame($value, (string) $this->configuration->getAlias($key), sprintf('%s alias is correct', $key));
    }

    private function assertHasDefinition(string $id): void
    {
        static::assertTrue($this->configuration->hasDefinition($id) ? true : $this->configuration->hasAlias($id));
    }
}
