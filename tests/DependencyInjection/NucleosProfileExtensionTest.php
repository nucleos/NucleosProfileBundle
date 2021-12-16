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
use Nucleos\ProfileBundle\Form\Model\Profile;
use Nucleos\ProfileBundle\Form\Model\Registration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

final class NucleosProfileExtensionTest extends TestCase
{
    protected ContainerBuilder $configuration;

    public function testUserLoadUtilServiceWithDefaults(): void
    {
        $this->createEmptyConfiguration();

        $this->assertAlias('nucleos_profile.mailer.default', 'nucleos_profile.mailer');
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

    public function testUserLoadFlashesCanBeDisabled(): void
    {
        $this->createFullConfiguration();

        $this->assertNotHasDefinition(FlashListener::class);
    }

    public function testUserLoadFormModelsWithDefaults(): void
    {
        $this->createEmptyConfiguration();

        $this->assertParameter(Profile::class, 'nucleos_profile.profile.form.model');
        $this->assertParameter(Registration::class, 'nucleos_profile.registration.form.model');
    }

    public function testUserLoadFormModels(): void
    {
        $this->createFullConfiguration();

        $this->assertParameter('App\Form\Profile', 'nucleos_profile.profile.form.model');
        $this->assertParameter('App\Form\Registration', 'nucleos_profile.registration.form.model');
    }

    private function createEmptyConfiguration(): void
    {
        $this->configuration = new ContainerBuilder();
        $loader              = new NucleosProfileExtension();
        $loader->load([], $this->configuration);
        static::assertInstanceOf(ContainerBuilder::class, $this->configuration);
    }

    private function createFullConfiguration(): void
    {
        $this->configuration = new ContainerBuilder();
        $loader              = new NucleosProfileExtension();
        $config              = $this->getFullConfig();
        $loader->load([$config], $this->configuration);
        static::assertInstanceOf(ContainerBuilder::class, $this->configuration);
    }

    /**
     * @return array<mixed>
     */
    private function getFullConfig(): array
    {
        $yaml   = <<<'EOF'
use_listener: true
use_flash_notifications: false
profile:
    form:
         model: App\Form\Profile
registration:
    form:
         model: App\Form\Registration
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

    /**
     * @param mixed $value
     */
    private function assertParameter($value, string $key): void
    {
        static::assertSame($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }

    private function assertHasDefinition(string $id): void
    {
        static::assertTrue(($this->configuration->hasDefinition($id) ? true : $this->configuration->hasAlias($id)));
    }

    private function assertNotHasDefinition(string $id): void
    {
        static::assertFalse(($this->configuration->hasDefinition($id) ? true : $this->configuration->hasAlias($id)));
    }
}
