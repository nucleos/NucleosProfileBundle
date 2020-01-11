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

use InvalidArgumentException;
use Nucleos\ProfileBundle\NucleosProfileEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashListener implements EventSubscriberInterface
{
    /**
     * @var string[]
     */
    private static $successMessages = [
        NucleosProfileEvents::PROFILE_EDIT_COMPLETED => 'profile.flash.updated',
        NucleosProfileEvents::REGISTRATION_COMPLETED => 'registration.flash.user_created',
    ];

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->flashBag   = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NucleosProfileEvents::PROFILE_EDIT_COMPLETED => 'addSuccessFlash',
            NucleosProfileEvents::REGISTRATION_COMPLETED => 'addSuccessFlash',
        ];
    }

    public function addSuccessFlash(Event $event, string $eventName): void
    {
        if (!isset(self::$successMessages[$eventName])) {
            throw new InvalidArgumentException('This event does not correspond to a known flash message');
        }

        $this->flashBag->add('success', $this->trans(self::$successMessages[$eventName]));
    }

    /**
     * @param array<string, int|string> $params
     */
    private function trans(string $message, array $params = []): string
    {
        return $this->translator->trans($message, $params, 'NucleosProfileBundle');
    }
}
