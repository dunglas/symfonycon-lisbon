<?php

namespace App\Controller;

use App\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * @Route("/", name="session_index")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Session::class);

        return $this->render('session/index.html.twig', [
            'sessions' => $repository->findBy([], ['datetime' => 'ASC'])
        ]);
    }

    /**
     * @Route("/session/{id}", name="session_detail")
     */
    public function detail(Session $session)
    {
        return $this->render('session/detail.html.twig', ['session' => $session]);
    }
}
