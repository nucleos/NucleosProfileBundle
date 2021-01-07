<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Action;

use Nucleos\ProfileBundle\Event\GetResponseRegistrationEvent;
use Nucleos\ProfileBundle\Event\UserFormEvent;
use Nucleos\ProfileBundle\Form\Model\Registration;
use Nucleos\ProfileBundle\Form\Type\RegistrationFormType;
use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\FilterUserResponseEvent;
use Nucleos\UserBundle\Event\FormEvent;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

final class RegistrationAction
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $formModel;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FormFactoryInterface $formFactory,
        UserManagerInterface $userManager,
        RouterInterface $router,
        Environment $twig,
        string $formModel
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory     = $formFactory;
        $this->userManager     = $userManager;
        $this->router          = $router;
        $this->twig            = $twig;
        $this->formModel       = $formModel;
    }

    public function __invoke(Request $request): Response
    {
        $formModel = $this->createFormModel();

        $event = new GetResponseRegistrationEvent($formModel, $request);
        $this->eventDispatcher->dispatch($event, NucleosProfileEvents::REGISTRATION_INITIALIZE);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form      = $this->formFactory->create(RegistrationFormType::class, $formModel, [
            'validation_groups' => ['Registration', 'Default'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                return $this->updateUser($request, $formModel, $form);
            }

            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch($event, NucleosProfileEvents::REGISTRATION_FAILURE);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return new Response($this->twig->render('@NucleosProfile/Registration/register.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    private function createFormModel(): Registration
    {
        return new $this->formModel();
    }

    private function updateUser(Request $request, Registration $formModel, FormInterface $form): Response
    {
        $user = $formModel->toUser($this->userManager);

        $event = new UserFormEvent($user, $form, $request);
        $this->eventDispatcher->dispatch($event, NucleosProfileEvents::REGISTRATION_SUCCESS);

        $this->userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url      = $this->router->generate('nucleos_profile_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $this->eventDispatcher->dispatch(
            new FilterUserResponseEvent($user, $request, $response),
            NucleosProfileEvents::REGISTRATION_COMPLETED
        );

        return $response;
    }
}
