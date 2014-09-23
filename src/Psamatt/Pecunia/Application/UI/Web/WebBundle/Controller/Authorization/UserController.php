<?php

namespace Psamatt\Pecunia\Application\UI\Web\WebBundle\Controller\Authorization;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

use JMS\DiExtraBundle\Annotation as DI;

use Psamatt\Pecunia\Application\UI\Web\SharedBundle\Util\ControllerUtils;

class UserController
{
    private $utils;
    private $session;
    private $encoderFactory;

    /**
     * @DI\InjectParams({
     *     "utils" = @DI\Inject("Pecunia.ControllerUtils"),
     *     "encoderFactory"  = @DI\Inject("security.encoder_factory"),
     *     "session"         = @DI\Inject("session")
     * })
     */
    public function __construct(
            ControllerUtils $utils,
            $encoderFactory,
            $session)
    {
        $this->utils = $utils;
        $this->encoderFactory = $encoderFactory;
        $this->session = $session;
    }

    /**
     * Login to the application
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $user = $this->utils->getUser();

        if (is_object($user) && $user instanceof User) {
            $this->utils->redirect($this->router->generate('accountHolder_homepage'), 302);
        }

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->session->get(SecurityContext::AUTHENTICATION_ERROR);
            $this->session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle::login.html.twig', array(
            'error'         => $error,
            'last_username' => $this->session->get('_security.last_username'),
        ));
    }
}