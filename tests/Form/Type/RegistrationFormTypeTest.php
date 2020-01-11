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

final class RegistrationFormTypeTest extends ValidatorExtensionTypeTestCase
{
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
                new RegistrationFormType(Registration::class),
            ]
        );
    }
}
