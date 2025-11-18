<?php

namespace App\Controller;

use App\Class\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
   #[Route('/', name: 'app_home')]
   public function index(): Response
   {
        $mail = new Mail();
        $mail->send('mike.agbelou@gmail.com', 'John Doe', 'Mon premier test d\'envoie de mail', 'Bienvenue sur DMD Ecommerce. Merci pour votre inscription sur notre site.');
        
        return $this->render('home/index.html.twig');
   }
}