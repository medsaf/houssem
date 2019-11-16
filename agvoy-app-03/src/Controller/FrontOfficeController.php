<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontOfficeController extends AbstractController
{
    /**
     * @Route("/frontoffice", name="front_office")
     */
    public function index()
    {
        return $this->render('front_office/index.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }
}
