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

use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class CheckRegistrationMailAction
{
    private UserManagerInterface $userManager;

    private Environment $twig;

    private RouterInterface $router;

    /**
     * CheckRegistrationMailAction constructor.
     */
    public function __construct(UserManagerInterface $userManager, Environment $twig, RouterInterface $router)
    {
        $this->userManager = $userManager;
        $this->twig        = $twig;
        $this->router      = $router;
    }

    /**
     * Tell the user to check their email provider.
     */
    public function __invoke(Request $request): Response
    {
        $email = $request->getSession()->get('nucleos_profile_send_confirmation_email/email', '');

        if ('' === $email) {
            return new RedirectResponse($this->router->generate('nucleos_profile_registration_register'));
        }

        $request->getSession()->remove('nucleos_profile_send_confirmation_email/email');
        $user = $this->userManager->findUserByEmail($email);

        if (null === $user) {
            return new RedirectResponse($this->router->generate('nucleos_user_security_login'));
        }

        return new Response(
            $this->twig->render('@NucleosProfile/Registration/check_email.html.twig', [
                'user' => $user,
            ])
        );
    }
}
