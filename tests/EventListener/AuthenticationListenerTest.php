<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Tests\EventListener;

use Nucleos\ProfileBundle\EventListener\AuthenticationListener;
use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\FilterUserResponseEvent;
use Nucleos\UserBundle\Model\UserInterface;
use Nucleos\UserBundle\Security\LoginManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class AuthenticationListenerTest extends TestCase
{
    public const FIREWALL_NAME = 'foo';

    private EventDispatcherInterface $eventDispatcher;

    private FilterUserResponseEvent $event;

    private AuthenticationListener $listener;

    protected function setUp(): void
    {
        $user                  = $this->getMockBuilder(UserInterface::class)->getMock();
        $response              = $this->getMockBuilder(Response::class)->getMock();
        $request               = $this->getMockBuilder(Request::class)->getMock();

        $this->event           = new FilterUserResponseEvent($user, $request, $response);

        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->eventDispatcher->expects(self::once())->method('dispatch');

        $loginManager   = $this->createMock(LoginManager::class);

        $this->listener = new AuthenticationListener(
            $loginManager,
            self::FIREWALL_NAME
        );
    }

    public function testAuthenticate(): void
    {
        $this->listener->authenticate(
            $this->event,
            NucleosProfileEvents::REGISTRATION_COMPLETED,
            $this->eventDispatcher
        );
    }
}
