<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Event;

use Nucleos\ProfileBundle\Form\Model\Registration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class GetResponseRegistrationEvent extends Event
{
    /**
     * @var Request|null
     */
    protected $request;

    /**
     * @var Registration
     */
    protected $registration;

    /**
     * @var Response|null
     */
    private $response;

    public function __construct(Registration $user, Request $request = null)
    {
        $this->registration = $user;
        $this->request      = $request;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }
}
