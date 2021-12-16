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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

final class ShowProfileAction
{
    private Environment $twig;

    private Security $security;

    public function __construct(Environment $twig, Security $security)
    {
        $this->twig     = $twig;
        $this->security = $security;
    }

    public function __invoke(): Response
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return new Response(
            $this->twig->render('@NucleosProfile/Profile/show.html.twig', [
                'user' => $user,
            ])
        );
    }
}
