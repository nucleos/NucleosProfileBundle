<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\Form\Type;

use Exception;
use Nucleos\UserBundle\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RegistrationFormType extends AbstractType
{
    /**
     * @phpstan-var class-string<UserInterface>
     */
    private string $class;

    /**
     * @phpstan-param class-string<UserInterface> $class The User class name
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @param array<mixed> $options
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'              => 'form.email',
                'getter'             => static function (UserInterface $user, FormInterface $form): string {
                    try {
                        return $user->getEmail();
                    } catch (Exception) {
                        return '';
                    }
                },
                'setter'             => static function (UserInterface &$user, ?string $value, FormInterface $form): void {
                    if (null === $value) {
                        return;
                    }

                    try {
                        $user->setEmail($value);
                    } catch (Exception) {
                        throw new UnexpectedTypeException($value, 'string');
                    }
                },
            ])
            ->add('username', TextType::class, [
                'label'              => 'form.username',
                'getter'             => static function (UserInterface $user, FormInterface $form): string {
                    try {
                        return $user->getUsername();
                    } catch (Exception) {
                        return '';
                    }
                },
                'setter'             => static function (UserInterface &$user, ?string $value, FormInterface $form): void {
                    if (null === $value) {
                        return;
                    }

                    try {
                        $user->setUsername($value);
                    } catch (Exception) {
                        throw new UnexpectedTypeException($value, 'string');
                    }
                },
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'options'         => [
                    'attr'               => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options'   => ['label' => 'form.password'],
                'second_options'  => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'nucleos_user.password.mismatch',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => $this->class,
            'csrf_token_id'      => 'registration',
            'translation_domain' => 'NucleosProfileBundle',
        ]);
    }
}
