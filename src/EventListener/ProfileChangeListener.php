<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\EventListener;

use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\FilterUserResponseEvent;
use Nucleos\UserBundle\Event\UserEvent;
use Nucleos\UserBundle\NucleosUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class ProfileChangeListener implements EventSubscriberInterface
{
    private readonly EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NucleosProfileEvents::PROFILE_EDIT_COMPLETED => 'profileChanged',
        ];
    }

    public function profileChanged(FilterUserResponseEvent $event): void
    {
        $this->eventDispatcher->dispatch(new UserEvent($event->getUser(), $event->getRequest()), NucleosUserEvents::USER_LOCALE_CHANGED);
        $this->eventDispatcher->dispatch(new UserEvent($event->getUser(), $event->getRequest()), NucleosUserEvents::USER_TIMEZONE_CHANGED);
    }
}
