<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
  
    
        #[Route('/', name: 'product_index', methods: ['GET'])]
        public function index(ProductRepository $productRepository): Response
        {
            return $this->render('product/index.html.twig', [
                'products' => $productRepository->findAll(),
            ]);
        }
}
