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

use Nucleos\ProfileBundle\Form\Model\Registration;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Validator\ViolationMapper\ViolationMapper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final class RegistrationFormType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

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

    /**
     * @param string $class The User class name
     */
    public function __construct(string $class, UserManagerInterface $userManager, ValidatorInterface $validator)
    {
        $this->class           = $class;
        $this->validator       = $validator;
        $this->userManager     = $userManager;
        $this->violationMapper = new ViolationMapper();
    }

    /**
     * @param array<mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'              => 'form.email',
            ])
            ->add('username', TextType::class, [
                'label'              => 'form.username',
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
            ->add('save', SubmitType::class, [
                'label'  => 'registration.submit',
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event) use ($options): void {
                $errors = $this->getUserErrors($event->getData(), (array) $options['validation_groups']);

                foreach ($errors as $error) {
                    \assert($error instanceof ConstraintViolation);

                    $this->violationMapper->mapViolation($error, $event->getForm());
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => $this->class,
            'csrf_token_id'      => 'registration',
            'translation_domain' => 'NucleosProfileBundle',
            'validation_groups'  => [],
        ]);
    }

    /**
     * @param mixed    $registration
     * @param string[] $validationGroups
     *
     * @return ConstraintViolationInterface[]
     */
    private function getUserErrors($registration, array $validationGroups): array
    {
        if (!$registration instanceof Registration) {
            return [];
        }

        try {
            $user = $registration->toUser($this->userManager);
        } catch (Throwable $exception) {
            return [];
        }

        return iterator_to_array($this->validator->validate($user, null, $validationGroups));
    }
}
