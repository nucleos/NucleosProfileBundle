Customize Registration
======================

Your business needs might require changes in registration form. It is possible to add, remove or modify form fields provided by NucleosProfileBundle.

How to customize a Form
-----------------------

If you want to modify the Registration form in your project there are a few steps that you should take. For example if you would like to add checkbox for accepting terms and conditions you will have to follow these steps:

1. Extend ``Nucleos\UserBundle\Model\User\User``

.. code-block:: php-annotations

    namespace App\Model;

    use Nucleos\UserBundle\Model\User as BaseUser;

    class User extends BaseUser
    {
        protected ?bool $termsAccepted = false;

        public function gettermsAccepted(): ?bool
        {
            return $this->termsAccepted;
        }

        public function setTermsAccepted(?bool $termsAccepted): void
        {
            $this->termsAccepted = $termsAccepted;
        }
    }

2. Use Symfony Form Extensions to add fields. You can use builder to remove or modify fields as well.

.. code-block:: php-annotations

    namespace App\Form\Type;

    use Symfony\Component\Form\AbstractTypeExtension;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\FormBuilderInterface;

    class RegistrationFormType extends AbstractTypeExtension
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('termsAccepted', CheckboxType::class);
        }

        public static function getExtendedTypes(): iterable
        {
            return ['Nucleos\ProfileBundle\Form\Type\RegistrationFormType'];
        }
    }
