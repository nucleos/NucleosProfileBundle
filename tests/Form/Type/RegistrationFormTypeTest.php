<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Tests\Form\Type;

use Nucleos\ProfileBundle\Form\Model\Registration;
use Nucleos\ProfileBundle\Form\Type\RegistrationFormType;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\Extension\Validator\ViolationMapper\ViolationMapper;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegistrationFormTypeTest extends ValidatorExtensionTypeTestCase
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ViolationMapper
     */
    private $violationMapper;

    protected function setUp(): void
    {
        $this->userManager     = $this->createMock(UserManagerInterface::class);
        $this->validator       = $this->createMock(ValidatorInterface::class);
        $this->violationMapper = $this->createMock(ViolationMapper::class);

        parent::setUp();
    }

    public function testSubmit(): void
    {
        $registration = new Registration();

        $form     = $this->factory->create(RegistrationFormType::class, $registration);
        $formData = [
            'username'      => 'bar',
            'email'         => 'john@doe.com',
            'plainPassword' => [
                'first'  => 'test',
                'second' => 'test',
            ],
        ];
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertSame($registration, $form->getData());
        static::assertSame('bar', $registration->getUsername());
        static::assertSame('john@doe.com', $registration->getEmail());
        static::assertSame('test', $registration->getPlainPassword());
    }

    /**
     * @return mixed[]
     */
    protected function getTypes(): array
    {
        return array_merge(
            parent::getTypes(),
            [
                new RegistrationFormType(
                    Registration::class,
                    $this->userManager,
                    $this->validator
                ),
            ]
        );
    }
}
