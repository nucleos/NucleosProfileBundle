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
use Nucleos\ProfileBundle\Form\Type\RegistrationFormType;
use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\FilterUserResponseEvent;
use Nucleos\UserBundle\Event\FormEvent;
use Nucleos\UserBundle\Model\UserInterface;
use Nucleos\UserBundle\Model\UserManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    private EventDispatcherInterface $eventDispatcher;

    private FormFactoryInterface $formFactory;

    private UserManager $userManager;

    private Environment $twig;

    private RouterInterface $router;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FormFactoryInterface $formFactory,
        UserManager $userManager,
        RouterInterface $router,
        Environment $twig
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory     = $formFactory;
        $this->userManager     = $userManager;
        $this->router          = $router;
        $this->twig            = $twig;
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->userManager->createUser();

        $event = new GetResponseRegistrationEvent($user, $request);
        $this->eventDispatcher->dispatch($event, NucleosProfileEvents::REGISTRATION_INITIALIZE);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                return $this->updateUser($request, $user, $form);
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

    private function updateUser(Request $request, UserInterface $user, FormInterface $form): Response
    {
        $event = new UserFormEvent($user, $form, $request);
        $this->eventDispatcher->dispatch($event, NucleosProfileEvents::REGISTRATION_SUCCESS);

        $this->userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $response = new RedirectResponse($this->router->generate('nucleos_profile_registration_confirmed'));
        }

        $this->eventDispatcher->dispatch(
            new FilterUserResponseEvent($user, $request, $response),
            NucleosProfileEvents::REGISTRATION_COMPLETED
        );

        return $response;
    }

    private function createForm(UserInterface $user): FormInterface
    {
        return $this->formFactory
            ->create(RegistrationFormType::class, $user, [
                'validation_groups' => ['Registration', 'Default'],
            ])
            ->add('save', SubmitType::class, [
                'label'  => 'registration.submit',
            ])
        ;
    }
}
