<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle;

/**
 * Contains all events thrown in the NucleosProfileBundle.
 */
final class NucleosProfileEvents
{
    /**
     * The PROFILE_EDIT_INITIALIZE event occurs when the profile editing process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("Nucleos\UserBundle\Event\GetResponseUserEvent")
     */
    public const PROFILE_EDIT_INITIALIZE = 'nucleos_profile.profile.edit.initialize';

    /**
     * The PROFILE_EDIT_SUCCESS event occurs when the profile edit form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("Nucleos\UserBundle\Event\FormEvent")
     */
    public const PROFILE_EDIT_SUCCESS = 'nucleos_profile.profile.edit.success';

    /**
     * The PROFILE_EDIT_COMPLETED event occurs after saving the user in the profile edit process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Nucleos\UserBundle\Event\FilterUserResponseEvent")
     */
    public const PROFILE_EDIT_COMPLETED = 'nucleos_profile.profile.edit.completed';

    /**
     * The REGISTRATION_INITIALIZE event occurs when the registration process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("Nucleos\UserBundle\Event\GetResponseRegistrationEvent")
     */
    public const REGISTRATION_INITIALIZE = 'nucleos_profile.registration.initialize';

    /**
     * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("Nucleos\UserBundle\Event\FormEvent")
     */
    public const REGISTRATION_SUCCESS = 'nucleos_profile.registration.success';

    /**
     * The REGISTRATION_FAILURE event occurs when the registration form is not valid.
     *
     * This event allows you to set the response instead of using the default one.
     * The event listener method receives a Nucleos\UserBundle\Event\FormEvent instance.
     *
     * @Event("Nucleos\UserBundle\Event\FormEvent")
     */
    public const REGISTRATION_FAILURE = 'nucleos_profile.registration.failure';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Nucleos\UserBundle\Event\FilterUserResponseEvent")
     */
    public const REGISTRATION_COMPLETED = 'nucleos_profile.registration.completed';

    /**
     * The REGISTRATION_CONFIRM event occurs just before confirming the account.
     *
     * This event allows you to access the user which will be confirmed.
     *
     * @Event("Nucleos\UserBundle\Event\GetResponseUserEvent")
     */
    public const REGISTRATION_CONFIRM = 'nucleos_profile.registration.confirm';

    /**
     * The REGISTRATION_CONFIRMED event occurs after confirming the account.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Nucleos\UserBundle\Event\FilterUserResponseEvent")
     */
    public const REGISTRATION_CONFIRMED = 'nucleos_profile.registration.confirmed';
}
