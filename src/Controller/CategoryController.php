<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
   #[Route('/categories', name: 'app_categorys')]
   public function index(CategoryRepository $categoryRepository): Response
   {
      $categorys = $categoryRepository->findAll();

      if (!$categorys) {
         return $this->redirectToRoute('app_home');
      }
      return $this->render('category/index.html.twig', [
         'categorys' => $categorys,
      ]);
   }
   
   #[Route('/categories/{slug}', name: 'app_category')]
   public function findOneBy($slug, CategoryRepository $categoryRepository): Response
   {
      $category = $categoryRepository->findOneBySlug($slug);

      if (!$category) {
         return $this->redirectToRoute('app_home');
      }
      return $this->render('category/detail.html.twig', [
         'category' => $category,
      ]);
   }
}