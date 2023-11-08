<?php

namespace App\Controller;


use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
  
    


        #[Route('/', name: 'home', methods: ['GET'])]
        public function filter(ProductRepository $productRepository, Request $request ): Response
        {
           // phpinfo();
            //exit;
            $products = $productRepository->findAll();


            return $this->render('product/index.html.twig', [
                'products' => $products
            ]);
        }

    #[Route('/filter', name: 'filter', methods: ['GET'])]
    public function filterPrice(ProductRepository $productRepository, Request $request ): Response
    {
        $filter = $request->get("filter");
        $min = $request->get("min");
        $max = $request->get("max");
          // dd($min);

        $products = $productRepository->filter($filter, $min , $max);



        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }


}
