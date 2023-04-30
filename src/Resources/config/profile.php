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

use Nucleos\ProfileBundle\Action\EditProfileAction;
use Nucleos\ProfileBundle\Action\ShowProfileAction;
use Nucleos\ProfileBundle\Form\Type\ProfileFormType;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(ProfileFormType::class)
            ->tag('form.type')
            ->args([
                new Parameter('nucleos_user.model.user.class'),
            ])

        ->set(EditProfileAction::class)
            ->public()
            ->args([
                new Reference('event_dispatcher'),
                new Reference('form.factory'),
                new Reference('nucleos_user.user_manager'),
                new Reference('twig'),
                new Reference('router'),
                new Reference('security.helper'),
            ])

        ->set(ShowProfileAction::class)
            ->public()
            ->args([
                new Reference('twig'),
                new Reference('security.helper'),
            ])
    ;
};
