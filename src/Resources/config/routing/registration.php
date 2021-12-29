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

namespace Symfony\Component\Routing\Loader\Configurator;

use Nucleos\ProfileBundle\Action\CheckRegistrationMailAction;
use Nucleos\ProfileBundle\Action\ConfirmRegistrationAction;
use Nucleos\ProfileBundle\Action\RegistrationAction;
use Nucleos\ProfileBundle\Action\RegistrationConfirmedAction;

return static function (RoutingConfigurator $routes): void {
    $routes->add('nucleos_profile_registration_check_email', '/check-email')
        ->controller(CheckRegistrationMailAction::class)
        ->methods(['GET'])
    ;

    $routes->add('nucleos_profile_registration_confirm', '/confirm/{token}')
        ->controller(ConfirmRegistrationAction::class)
        ->methods(['GET'])
    ;

    $routes->add('nucleos_profile_registration_confirmed', '/confirmed')
        ->controller(RegistrationConfirmedAction::class)
        ->methods(['GET'])
    ;

    $routes->add('nucleos_profile_registration_register', '/')
        ->controller(RegistrationAction::class)
        ->methods(['GET', 'POST'])
    ;
};
