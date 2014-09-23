<?php

namespace Psamatt\Pecunia\Application\UI\Web\SharedBundle\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

/** Service = Pecunia.ControllerUtils */
class ControllerUtils
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string         $route         The name of the route
     * @param mixed          $parameters    An array of parameters
     * @param Boolean|string $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string  $url    The URL to redirect to
     * @param integer $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Redirect and build the route
     *
     * @param string $route
     * @param mixed  $params
     */
    public function redirectRoute($route, $params = [])
    {
        return $this->redirect($this->generateUrl($route, $params));
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string|FormTypeInterface $type    The built type of the form
     * @param mixed                    $data    The initial data for the form
     * @param array                    $options Options for the form
     *
     * @return FormInterface
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    /**
     * Get a user from the Security Context
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    public function getUser()
    {
        if (!$this->container->has('security.context')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.context')->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    /**
     * Add a confirmation message
     *
     * @param string $message
     */
    public function addConfirmationMessage($message)
    {
        $this->container->get('session')->getFlashBag()->add(
            'notice',
            $message
        );
    }

    /**
     * Add a confirmation message
     *
     * @param string $message
     */
    public function addErrorMessage($message)
    {
        $this->container->get('session')->getFlashBag()->add(
            'error',
            $message
        );
    }

    /**
     * Get unit of work
     *
     * @return EntityManager
     */
    public function getUnitOfWork()
    {
        return $this->container->get('doctrine.orm.write_entity_manager');
    }

    /** @return AccountHolderId */
    public function getAccountHolderId()
    {
        return new AccountHolderId($this->getUser()->getId());
    }

    /**
     * Get the event dispatcer
     *
     * @return Dispatcher
     */
    public function getEventDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    /**
     * Get the mediator
     *
     * @return Mediator
     */
    public function getMediator()
    {
        return $this->container->get('servicebus.mediator');
    }
}