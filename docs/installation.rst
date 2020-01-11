Installation
============

Prerequisites
-------------

You need to configure the NucleosUserBundle first, check `NucleosUserBundle documentation`_.

Translations
~~~~~~~~~~~~

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

.. code-block:: yaml

    # config/packages/framework.yaml
    framework:
        translator: ~

For more information about translations, check `Symfony documentation`_.

Installation
------------

1. Download NucleosProfileBundle using composer
2. Enable the Bundle
3. Configure the NucleosProfileBundle
4. Configure your application's security.yaml
5. Import NucleosProfileBundle routing

Step 1: Download NucleosProfileBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Require the bundle with composer:

.. code-block:: bash

    $ composer require nucleos/profile-bundle

Step 2: Enable the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the kernel:

.. code-block:: php-annotations

    // config/bundles.php
    return [
        // ...
        Nucleos\UserBundle\NucleosProfileBundle::class => ['all' => true],
        // ...
    ]

Step 3: Configure the NucleosProfileBundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Add the following configuration to your ``config/packages/nucleos_profile.yaml``.

.. code-block:: yaml

    # config/packages/nucleos_profile.yaml
    nucleos_profile:
        registration:
            confirmation:
                from_email:  "%mailer_user%"



Step 4: Configure your application's security.yaml
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

In addition to the NucleosUserBundle configuration, you have to define some new access rules.

Below is a minimal example of the configuration necessary to use the NucleosUserBundle
in your application:

.. code-block:: yaml

    # config/packages/security.yaml
    security:
        // ...

        access_control:
            - { path: ^/profile, role: IS_AUTHENTICATED_REMEMBERED }

Step 5: Import NucleosProfileBundle routing files
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that you have activated and configured the bundle, all that is left to do is
import the NucleosProfileBundle routing files.

By importing the routing files you will have ready made pages for things such as
logging in, creating users, etc.

.. code-block:: yaml

    # config/routes/nucleos_profile.yaml
    nucleos_profile:
        resource: "@NucleosProfileBundle/Resources/config/routing/all.xml"

.. _Symfony documentation: https://symfony.com/doc/current/book/translation.html
.. _NucleosUserBundle documentation: https://nucleosuserbundle.readthedocs.io
