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

use LogicException;
use Nucleos\ProfileBundle\Form\Model\Profile;
use Nucleos\ProfileBundle\Form\Type\ProfileFormType;
use Nucleos\ProfileBundle\NucleosProfileEvents;
use Nucleos\UserBundle\Event\FilterUserResponseEvent;
use Nucleos\UserBundle\Event\FormEvent;
use Nucleos\UserBundle\Event\GetResponseUserEvent;
use Nucleos\UserBundle\Model\UserInterface;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

final class EditProfileAction
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
     * @var Security
     */
    private $security;

    /**
     * @var string
     */
    private $formModel;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        FormFactoryInterface $formFactory,
        UserManagerInterface $userManager,
        Environment $twig,
        RouterInterface $router,
        Security $security,
        string $formModel
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory     = $formFactory;
        $this->userManager     = $userManager;
        $this->twig            = $twig;
        $this->router          = $router;
        $this->security        = $security;
        $this->formModel       = $formModel;
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

        $formModel = $this->createFormModel($user);

        $form      = $this->formFactory->create(ProfileFormType::class, $formModel, [
            'validation_groups' => ['Profile', 'Default'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formModel->updateUser($user);

            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch($event, NucleosProfileEvents::PROFILE_EDIT_SUCCESS);

            $this->userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url      = $this->router->generate('nucleos_profile_profile_show');
                $response = new RedirectResponse($url);
            }

            $this->eventDispatcher->dispatch(
                new FilterUserResponseEvent($user, $request, $response),
                NucleosProfileEvents::PROFILE_EDIT_COMPLETED
            );

            return $response;
        }

        return new Response(
            $this->twig->render('@NucleosProfile/Profile/edit.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }

    private function createFormModel(UserInterface $user): Profile
    {
        if (!is_a($this->formModel, Profile::class)) {
            throw new LogicException(sprintf('The "%s" is not a valid "%s" class', $this->formModel, Profile::class));
        }

        return ($this->formModel)::fromUser($user);
    }
}
