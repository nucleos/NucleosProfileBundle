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

use Nucleos\ProfileBundle\Form\Type\ProfileFormType;
use Nucleos\ProfileBundle\Tests\App\Entity\TestUser;

final class ProfileFormTypeTest extends ValidatorExtensionTypeTestCase
{
    public function testSubmit(): void
    {
        $user = new TestUser();

        $form     = $this->factory->create(ProfileFormType::class, $user);
        $formData = [
            'timezone'  => 'Europe/Berlin',
            'locale'    => 'de_DE',
        ];
        $form->submit($formData);

        self::assertTrue($form->isSynchronized());
        self::assertSame($user, $form->getData());
        self::assertSame('Europe/Berlin', $user->getTimezone());
        self::assertSame('de_DE', $user->getLocale());
    }

    /**
     * @return mixed[]
     */
    protected function getTypes(): array
    {
        return array_merge(parent::getTypes(), [
            new ProfileFormType(TestUser::class),
        ]);
    }
}
