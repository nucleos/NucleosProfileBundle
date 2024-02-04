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

use Nucleos\ProfileBundle\Event\UserFormEvent;
use Nucleos\ProfileBundle\Form\Type\ProfileFormType;
use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\FilterUserResponseEvent;
use Nucleos\UserBundle\Event\GetResponseUserEvent;
use Nucleos\UserBundle\Model\UserInterface;
use Nucleos\UserBundle\Model\UserManager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

final class EditProfileAction
{
    private EventDispatcherInterface $eventDispatcher;

    private FormFactoryInterface $formFactory;

    private UserManager $userManager;

    private Environment $twig;

    private RouterInterface $router;

    private Security $security;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FormFactoryInterface $formFactory,
        UserManager $userManager,
        Environment $twig,
        RouterInterface $router,
        Security $security
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory     = $formFactory;
        $this->userManager     = $userManager;
        $this->twig            = $twig;
        $this->router          = $router;
        $this->security        = $security;
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch($event, NucleosProfileEvents::PROFILE_EDIT_INITIALIZE);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->updateUser($request, $form, $user);
        }

        return new Response($this->twig->render('@NucleosProfile/Profile/edit.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    private function updateUser(Request $request, FormInterface $form, UserInterface $user): Response
    {
        $event = new UserFormEvent($user, $form, $request);
        $this->eventDispatcher->dispatch($event, NucleosProfileEvents::PROFILE_EDIT_SUCCESS);

        $this->userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $response = new RedirectResponse($this->router->generate('nucleos_profile_profile_show'));
        }

        $this->eventDispatcher->dispatch(
            new FilterUserResponseEvent($user, $request, $response),
            NucleosProfileEvents::PROFILE_EDIT_COMPLETED
        );

        return $response;
    }

    private function createForm(UserInterface $user): FormInterface
    {
        return $this->formFactory
            ->create(ProfileFormType::class, $user, [
                'validation_groups' => ['Profile', 'User', 'Default'],
            ])
            ->add('save', SubmitType::class, [
                'label'  => 'profile.edit.submit',
            ])
        ;
    }
}
