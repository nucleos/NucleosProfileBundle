Advanced routing configuration
==============================

By default, the routing file ``@NucleosProfileBundle/Resources/config/routing/all.xml`` imports
all the routing files and enables all the routes.
In the case you want to enable or disable the different available routes, use the
single routing configuration files.

.. code-block:: yaml

    # config/routes/nucleos_profile.yaml
    nucleos_profile_profile:
        resource: "@NucleosProfileBundle/Resources/config/routing/profile.xml"
        prefix: /profile

    nucleos_profile_change_registration:
        resource: "@NucleosProfileBundle/Resources/config/routing/registration.xml"
        prefix: /registration

