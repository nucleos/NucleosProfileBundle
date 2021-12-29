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

namespace Nucleos\ProfileBundle\Tests\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Nucleos\ProfileBundle\NucleosProfileBundle;
use Nucleos\UserBundle\NucleosUserBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', false);
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new TwigBundle();
        yield new SecurityBundle();
        yield new DoctrineBundle();
        yield new NucleosUserBundle();
        yield new NucleosProfileBundle();
    }

    public function getCacheDir(): string
    {
        return $this->getBaseDir().'cache';
    }

    public function getLogDir(): string
    {
        return $this->getBaseDir().'log';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('@NucleosUserBundle/Resources/config/routing/security.php');
        $routes->import('@NucleosUserBundle/Resources/config/routing/change_password.php');
        $routes->import('@NucleosUserBundle/Resources/config/routing/resetting.php')
            ->prefix('/resetting')
        ;
        $routes->import('@NucleosUserBundle/Resources/config/routing/deletion.php');

        $routes->import('@NucleosProfileBundle/Resources/config/routing/profile.php')
            ->prefix('/profile')
        ;
        $routes->import('@NucleosProfileBundle/Resources/config/routing/registration.php')
            ->prefix('/register')
        ;
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(__DIR__.'/config/config.yaml');
        $container->import(__DIR__.'/config/security.yaml');
    }

    private function getBaseDir(): string
    {
        return sys_get_temp_dir().'/app-bundle/var/';
    }
}
