<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeSliderController extends AbstractController
{
    #[Route('/home/slider', name: 'app_home_slider')]
    public function index(): Response
    {
        return $this->render('home_slider/index.html.twig', [
            'controller_name' => 'HomeSliderController',
        ]);
    }
}
