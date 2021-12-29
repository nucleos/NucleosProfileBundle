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

use Nucleos\ProfileBundle\EventListener\EmailConfirmationListener;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(EmailConfirmationListener::class)
            ->tag('kernel.event_subscriber')
            ->args([
                new Reference('nucleos_profile.mailer'),
                new Reference('nucleos_user.util.token_generator'),
                new Reference('router'),
                new Reference('request_stack'),
            ])

    ;
};
