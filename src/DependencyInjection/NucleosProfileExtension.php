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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach (['mailer', 'listeners'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        if (!$config['use_authentication_listener']) {
            $container->removeDefinition('nucleos_profile.listener.authentication');
        }

        if ($config['use_flash_notifications']) {
            $loader->load('flash_notifications.xml');
        }

        $this->loadRegistration($config['registration'], $container, $loader);
        $this->loadProfile($config['profile'], $container, $loader);

        $container->setAlias('nucleos_profile.mailer', $config['service']['mailer']);
    }

    /**
     * @param array<mixed> $config
     */
    private function loadRegistration(array $config, ContainerBuilder $container, XmlFileLoader $loader): void
    {
        $loader->load('registration.xml');

        if ($config['confirmation']['enabled']) {
            $loader->load('email_confirmation.xml');
        }

        $container->setParameter('nucleos_profile.registration.form.model', $config['form']['model']);
        $container->setParameter('nucleos_profile.registration.confirmation.from_email', $config['confirmation']['from_email']);
    }

    /**
     * @param array<mixed> $config
     */
    private function loadProfile(array $config, ContainerBuilder $container, XmlFileLoader $loader): void
    {
        $loader->load('profile.xml');

        $container->setParameter('nucleos_profile.profile.form.model', $config['form']['model']);
    }
}
