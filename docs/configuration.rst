Configuration reference
=======================

All available configuration options are listed below with their default values.

.. code-block:: yaml

    nucleos_profile:
        use_listener:               true
        use_flash_notifications:    true
        use_authentication_listener: true
        profile:
            form:
                model:               Nucleos\ProfileBundle\Form\Model\Profile
        registration:
            confirmation:
                from_email:         ~ # Required
                enabled:            false # change to true for required email confirmation
            form:
                model:               Nucleos\ProfileBundle\Form\Model\Registration
        service:
            mailer:                 nucleos_profile.mailer.default
