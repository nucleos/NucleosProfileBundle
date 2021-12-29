UPGRADE FROM 1.x to 2.0
=======================

## Removed XML configurations

All configuration files were migrated to PHP to improve overall performance.

The `@NucleosProfileBundle/Resources/config/routing/all.xml` routing configuration was removed.

## Remove listeners configuration

All listeneres are now registered by default. The `use_authentication_listener`, `use_listener` and `use_flash_notifications` configuration keys were removed.

## Removed `Interface` suffix

The `Interface` suffix was removed from all interfaces. All default implementation use specific class prefix.

- `Nucleos\ProfileBundle\Mailer\MailerInterface` => `Nucleos\ProfileBundle\Mailer\RegistrationMailer`
- `Nucleos\ProfileBundle\Mailer\NoopMailer` => `Nucleos\ProfileBundle\Mailer\NoopRegistrationMailer`
- `Nucleos\ProfileBundle\Mailer\Mailer` => `Nucleos\ProfileBundle\Mailer\SimpleRegistrationMailer`
-
## Deprecations

All the deprecated code introduced on 1.8.x is removed on 2.0.

Please read [1.8.x](https://github.com/nucleos/NucleosProfileBundle/tree/1.8.x) upgrade guides for more information.

See also the [diff code](https://github.com/nucleos/NucleosProfileBundle/compare/1.8.x...2.0.0).
