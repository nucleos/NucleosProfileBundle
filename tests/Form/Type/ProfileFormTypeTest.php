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

use Nucleos\ProfileBundle\Form\Model\Profile;
use Nucleos\ProfileBundle\Form\Type\ProfileFormType;

final class ProfileFormTypeTest extends ValidatorExtensionTypeTestCase
{
    public function testSubmit(): void
    {
        $profile = new Profile();

        $form     = $this->factory->create(ProfileFormType::class, $profile);
        $formData = [
            'timezone'  => 'Europe/Berlin',
            'locale'    => 'de_DE',
        ];
        $form->submit($formData);

        static::assertTrue($form->isSynchronized());
        static::assertSame($profile, $form->getData());
        static::assertSame('Europe/Berlin', $profile->getTimezone());
        static::assertSame('de_DE', $profile->getLocale());
    }

    /**
     * @return mixed[]
     */
    protected function getTypes(): array
    {
        return array_merge(
            parent::getTypes(),
            [
                new ProfileFormType(Profile::class),
            ]
        );
    }
}
