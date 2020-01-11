<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Tests\Routing;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Routing\RouteCollection;

final class RoutingTest extends TestCase
{
    /**
     * @dataProvider loadRoutingProvider
     *
     * @param string[] $methods
     */
    public function testLoadRouting(string $routeName, string $path, array $methods): void
    {
        $locator = new FileLocator();
        $loader  = new XmlFileLoader($locator);

        $collection    = new RouteCollection();
        $subCollection = $loader->load(__DIR__.'/../../src/Resources/config/routing/profile.xml');
        $subCollection->addPrefix('/profile');
        $collection->addCollection($subCollection);
        $subCollection = $loader->load(__DIR__.'/../../src/Resources/config/routing/registration.xml');
        $subCollection->addPrefix('/register');
        $collection->addCollection($subCollection);

        $route = $collection->get($routeName);
        static::assertNotNull($route, sprintf('The route "%s" should exists', $routeName));
        static::assertSame($path, $route->getPath());
        static::assertSame($methods, $route->getMethods());
    }

    /**
     * @return string[][]|string[][][]
     */
    public function loadRoutingProvider(): array
    {
        return [
            ['nucleos_profile_profile_show', '/profile/', ['GET']],
            ['nucleos_profile_profile_edit', '/profile/edit', ['GET', 'POST']],

            ['nucleos_profile_registration_register', '/register/', ['GET', 'POST']],
            ['nucleos_profile_registration_check_email', '/register/check-email', ['GET']],
            ['nucleos_profile_registration_confirm', '/register/confirm/{token}', ['GET']],
            ['nucleos_profile_registration_confirmed', '/register/confirmed', ['GET']],
        ];
    }
}
