Hooking into the Controllers
============================

The controllers packaged with the NucleosProfileBundle provide a lot of
functionality that is sufficient for general use cases. But, you might find
that you need to extend that functionality and add some logic that suits the
specific needs of your application.

For this purpose, the controllers are dispatching events in many places in
their logic. All events can be found in the constants of the
``Nucleos\ProfileBundle\NucleosProfileEvents`` class.

All controllers follow the same convention: they dispatch a ``SUCCESS`` event
when the form is valid before saving the user, and a ``COMPLETED`` event when
it is done. Thus, all ``SUCCESS`` events allow you to set a response if you
don't want the default redirection. And all ``COMPLETED`` events give you access
to the response before it is returned.

Controllers with a form also dispatch an ``INITIALIZE`` event after the entity is
fetched, but before the form is created.

For instance, this listener will change the redirection after the password
resetting to go to the homepage.

.. code-block:: php-annotations

    // src/App/EventListener/ProfileEditingListener.php
    namespace App\EventListener;

    use Nucleos\ProfileBundle\Event\FormEvent;
    use Nucleos\ProfileBundle\NucleosProfileEvents;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

    /**
     * Listener responsible to change the redirection at the end of the profile editing
     */
    class ProfileEditingListener implements EventSubscriberInterface
    {
        private $router;

        public function __construct(UrlGeneratorInterface $router)
        {
            $this->router = $router;
        }

        public static function getSubscribedEvents(): array
        {
            return [
                NucleosProfileEvents::PROFILE_EDIT_SUCCESS => 'onProfileEditingSuccess',
            ];
        }

        public function onProfileEditingSuccess(FormEvent $event): void
        {
            $url = $this->router->generate('homepage');

            $event->setResponse(new RedirectResponse($url));
        }
    }

You can then register this listener:

.. code-block:: yaml

    # config/services.yaml
    services:
        app.password_resetting:
            class: App\EventListener\ProfileEditingListener
            arguments: ['@router']
            tags:
                - { name: kernel.event_subscriber }
