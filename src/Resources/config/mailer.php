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

use Nucleos\ProfileBundle\Mailer\NoopRegistrationMailer;
use Nucleos\ProfileBundle\Mailer\SimpleRegistrationMailer;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('nucleos_profile.mailer.simple', SimpleRegistrationMailer::class)
            ->args([
                new Reference('mailer.mailer'),
                new Reference('translator'),
                new Reference('router'),
                new Parameter('nucleos_profile.registration.confirmation.from_email'),
            ])

        ->set('nucleos_profile.mailer.noop', NoopRegistrationMailer::class)

    ;
};
