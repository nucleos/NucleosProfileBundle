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

use Nucleos\ProfileBundle\Action\EditProfileAction;
use Nucleos\ProfileBundle\Action\ShowProfileAction;

return static function (RoutingConfigurator $routes): void {
    $routes->add('nucleos_profile_profile_edit', '/edit')
        ->controller(EditProfileAction::class)
        ->methods(['GET', 'POST'])
    ;

    $routes->add('nucleos_profile_profile_show', '/')
        ->controller(ShowProfileAction::class)
        ->methods(['GET'])
    ;
};
