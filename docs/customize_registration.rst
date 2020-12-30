Customize Registration
======================

.. note::

    Work in progress!


Your business needs might require changes in registration form. It is possible to add, remove or modify form fields provided by NucleosProfileBundle.

How to customize a Form
-----------------------

If you want to modify the Registration form in your project there are a few steps that you should take. For example if you would like to add checkbox for accepting terms and conditions you will have to follow these steps:

1. Extend ``Nucleos\ProfileBundle\Form\Model\Registration``

.. code-block:: php-annotations

    namespace App\Form\Model;

    use Nucleos\ProfileBundle\Form\Model\Registration as BaseRegistration;

    class Registration extends BaseRegistration
    {
        /**
         * @var bool|null
         */
        protected $termsAccepted;

        /**
         * @return bool|null
         */
        public function gettermsAccepted(): ?bool
        {
            return $this->termsAccepted;
        }

        /**
         * @param Boolean|null $termsAccepted
         */
        public function setTermsAccepted(?bool $termsAccepted): void
        {
            $this->termsAccepted = $termsAccepted;
        }
    }

2. Add custom form model to configuration

.. code-block:: yaml

    nucleos_profile:
        registration:
            form:
                model: App\Form\Model\Registration

3. Use Symfony Form Extensions to add fields. You can use builder to remove or modify fields as well.

.. code-block:: php-annotations

    namespace App\Form\Type;

    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\AbstractTypeExtension;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

    class RegistrationFormType extends AbstractTypeExtension
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            // Adding new fields works just like in the parent form type.
            $builder->add('termsAccepted', CheckboxType::class);
        }

        public static function getExtendedTypes(): iterable
        {
            return ['Nucleos\ProfileBundle\Form\Type\RegistrationFormType'];
        }
    }
