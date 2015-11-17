<?php

namespace RosaMolas\facturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('facturacionBundle:Default:index.html.twig', array('name' => $name));
    }
}
