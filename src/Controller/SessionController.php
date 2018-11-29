<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="session")
     */
    public function index()
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }
}
