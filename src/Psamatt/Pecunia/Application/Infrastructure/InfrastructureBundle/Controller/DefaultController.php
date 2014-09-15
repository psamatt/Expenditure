<?php

namespace Psamatt\Pecunia\Application\Infrastructure\InfrastructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PsamattPecuniaApplicationInfrastructureInfrastructureBundle:Default:index.html.twig', array('name' => $name));
    }
}
