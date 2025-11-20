<?php

namespace App\Controller;

use App\Repository\HeaderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
   #[Route('/', name: 'app_home')]
   public function index(HeaderRepository $headerRepository): Response
   {
        $headers = $headerRepository->findAll();
        
        return $this->render('home/index.html.twig'
        , [
            'headers' => $headers,
        ]);
   }
}