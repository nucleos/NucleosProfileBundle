Configuration reference
=======================

All available configuration options are listed below with their default values.

.. code-block:: yaml

    nucleos_profile:
        registration:
            confirmation:
                from_email:         ~ # Required
                enabled:            false # change to true for required email confirmation
        service:
            mailer:                 nucleos_profile.mailer.simple
