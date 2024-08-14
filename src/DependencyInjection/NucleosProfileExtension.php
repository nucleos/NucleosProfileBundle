<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class NucleosProfileExtension extends Extension
{
    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('mailer.php');
        $loader->load('listeners.php');

        $this->loadRegistration($config['registration'], $container, $loader);
        $this->loadProfile($loader);

        $container->setAlias('nucleos_profile.mailer', $config['service']['mailer']);
    }

    /**
     * @param array<mixed> $config
     */
    private function loadRegistration(array $config, ContainerBuilder $container, FileLoader $loader): void
    {
        $loader->load('registration.php');

        if (true === $config['confirmation']['enabled']) {
            $loader->load('email_confirmation.php');
        }

        $container->setParameter('nucleos_profile.registration.confirmation.from_email', $config['confirmation']['from_email']);
    }

    private function loadProfile(FileLoader $loader): void
    {
        $loader->load('profile.php');
    }
}
