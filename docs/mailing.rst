Sending E-Mails
===============

The NucleosProfileBundle has built-in support for sending emails in two different
instances.

Registration Confirmation
-------------------------

The first is when a new user registers and the bundle is configured
to require email confirmation before the user registration is complete.
The email that is sent to the new user contains a link that, when visited,
will verify the registration and enable the user account.

Requiring email confirmation for a new account is turned off by default.
To enable it, update your configuration as follows:

.. code-block:: yaml

    # config/packages/nucleos_profile.yaml
    nucleos_profile:
        registration:
            confirmation:
                enabled: true

Default Mailer Implementations
------------------------------

The bundle comes with three mailer implementations. They are listed below
by service id:

- ``nucleos_profile.mailer.simple`` is the default implementation, and uses symfony mailer to send emails.
- ``nucleos_profile.mailer.noop`` is a mailer implementation which performs no operation, so no emails are sent.

Configuring the Sender Email Address
------------------------------------

The NucleosProfileBundle default mailer allows you to configure the sender email address
of the emails sent out by the bundle.

To configure the sender email address for registration emails sent out by the bundle,
update your ``nucleos_profile`` config as follows:

.. code-block:: yaml

    # config/packages/nucleos_profile.yaml
    nucleos_profile:
        # ...
        registration:
            confirmation:
                from_email:   resetting@example.com

Using A Custom Mailer
---------------------

The default mailer service used by NucleosProfileBundle relies on the symfony mailer
library to send mail. If you would like to use a different library to send
emails or change the content of the email you
may do so by defining your own service.

First you must create a new class which implements ``Nucleos\UserBundle\Mailer\MailerInterface``
which is listed below:

.. code-block:: php-annotations

    namespace Nucleos\UserBundle\Mailer;

    use Nucleos\UserBundle\Model\UserInterface;

    interface MailerInterface
    {

        /**
         * Send an email to a user to confirm the password reset
         *
         * @param UserInterface $user
         */
        function sendConfirmationEmailMessage(UserInterface $user): void;
    }

After you have implemented your custom mailer class and defined it as a service,
you must update your bundle configuration so that NucleosProfileBundle will use it.
Set the ``mailer`` configuration parameter under the ``service`` section.
An example is listed below.

.. code-block:: yaml

    # config/packages/nucleos_profile.yaml
    nucleos_profile:
        # ...
        service:
            mailer: app.custom_nucleos_profile_mailer

