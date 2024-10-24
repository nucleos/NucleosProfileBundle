<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\ProfileBundle\EventListener\AlreadyLoggedinListener;
use Nucleos\ProfileBundle\EventListener\AuthenticationListener;
use Nucleos\ProfileBundle\EventListener\FlashListener;
use Nucleos\ProfileBundle\EventListener\ProfileChangeListener;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(AuthenticationListener::class)
            ->tag('kernel.event_subscriber')
            ->args([
                new Reference('nucleos_user.security.login_manager'),
                new Parameter('nucleos_user.firewall_name'),
            ])

        ->set(AlreadyLoggedinListener::class)
            ->tag('kernel.event_subscriber')
            ->args([
                new Reference('security.helper'),
                new Reference('router'),
            ])

        ->set(ProfileChangeListener::class)
            ->tag('kernel.event_subscriber')
            ->args([
                new Reference('event_dispatcher'),
            ])

        ->set(FlashListener::class)
            ->tag('kernel.event_subscriber')
            ->args([
                new Reference('request_stack'),
                new Reference('translator'),
            ])
    ;
};
