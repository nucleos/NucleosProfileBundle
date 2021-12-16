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

use Nucleos\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

final class RegistrationConfirmedAction
{
    private Environment $twig;

    private Security $security;

    private TokenStorageInterface $tokenStorage;

    public function __construct(Environment $twig, Security $security, TokenStorageInterface $tokenStorage)
    {
        $this->twig         = $twig;
        $this->security     = $security;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Tell the user his account is now confirmed.
     */
    public function __invoke(Request $request): Response
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return new Response(
            $this->twig->render('@NucleosProfile/Registration/confirmed.html.twig', [
                'user'      => $user,
                'targetUrl' => $this->getTargetUrlFromSession($request->getSession()),
            ])
        );
    }

    private function getTargetUrlFromSession(SessionInterface $session): ?string
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token || !\is_callable([$token, 'getProviderKey'])) {
            return null;
        }

        $key = sprintf('_security.%s.target_path', $token->getProviderKey());

        if ($session->has($key)) {
            return $session->get($key);
        }

        return null;
    }
}
