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

use Nucleos\ProfileBundle\Action\CheckRegistrationMailAction;
use Nucleos\ProfileBundle\Action\ConfirmRegistrationAction;
use Nucleos\ProfileBundle\Action\RegistrationAction;
use Nucleos\ProfileBundle\Action\RegistrationConfirmedAction;
use Nucleos\ProfileBundle\Form\Type\RegistrationFormType;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(RegistrationFormType::class)
            ->tag('form.type')
            ->args([
                new Parameter('nucleos_profile.registration.form.model'),
                new Reference('nucleos_user.user_manager'),
                new Reference(ValidatorInterface::class),
            ])

        ->set(RegistrationAction::class)
            ->public()
            ->args([
                new Reference('event_dispatcher'),
                new Reference('form.factory'),
                new Reference('nucleos_user.user_manager'),
                new Reference('router'),
                new Reference('twig'),
                new Parameter('nucleos_profile.registration.form.model'),
            ])

        ->set(CheckRegistrationMailAction::class)
            ->public()
            ->args([
                new Reference('nucleos_user.user_manager'),
                new Reference('twig'),
                new Reference('router'),
            ])

        ->set(ConfirmRegistrationAction::class)
            ->public()
            ->args([
                new Reference('event_dispatcher'),
                new Reference('nucleos_user.user_manager'),
                new Reference('twig'),
            ])

        ->set(RegistrationConfirmedAction::class)
            ->public()
            ->args([
                new Reference('twig'),
                new Reference('security.helper'),
                new Reference('security.token_storage'),
            ])

    ;
};
