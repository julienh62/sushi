<?php

namespace App\Controller;

use App\Entity\OrderAssociated;
use App\Entity\OrderLine;
use App\Enum\OrderStatus;
use App\Form\OrderType;
use App\Repository\OrderLineRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;


class OrderController extends AbstractController
{

    public function __construct(
        CsrfTokenManagerInterface $tokenManager
    ) {
        $this->tokenManager = $tokenManager;
    }

    #[Route('/checkout_success/{token}', name: 'checkout_success')]
    public function success( SessionInterface $session, OrderLineRepository $orderLineRepository, Request $request, EntityManagerInterface $em, string $token)
    {
        if( $this->isCsrfTokenValid('stripe_token', $token)) {
            $orderLine = $orderLineRepository->find($session->get("order_waiting"));
            $orderLine->getOrderAssociated()->setStatus(OrderStatus::PAID);
            $em->flush();

            return new Response("Commande validée");
        }else {
            return new Response("Erreur");
        }

    }


    #[Route('/order', name: 'app_order')]
    public function create( ParameterBagInterface $bag,  SessionInterface $session, ProductRepository $productRepository, Request $request, EntityManagerInterface $em): Response
    {
        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('stripe_token')->getValue();

        $stripeItems = [];
        $cart = $session->get('cart', []);
        if(empty($cart)){
            return $this->redirectToRoute('home');
        }


        $orderAssociated = new OrderAssociated();

        $form = $this->createForm(OrderType::class, $orderAssociated);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderAssociated->setStatus(OrderStatus::CREATED);
            $orderAssociated->setOrderDateAssociated(new \DateTimeImmutable());
            $orderAssociated->setDelivredDateAssociated(new \DateTimeImmutable());
            $cart = $session->get("cart", []);
            $totalPrice = 0;

            foreach ($cart as $key => $quantity) {

                $orderLine = new OrderLine();
                $product = $productRepository->find($key);


            $orderLine->setPrice($product->getPrice());
            $orderLine->setProduct($product);
            $orderLine->setQuantity($quantity);
            $orderLine->setOrderAssociated($orderAssociated);
            $totalPrice += $product->getPrice() * $quantity;


                $stripeItems[] =
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => $product->getName(),
                            ],
                            'unit_amount' => $product->getPrice(),
                        ],
                        'quantity' => $orderLine->getQuantity(),

                ];

            }


            $em->persist($orderLine);


            $orderAssociated->setTotal($totalPrice);


            // Enregistrez les modifications dans la base de données
            $em->persist($orderAssociated);
            $em->flush();
            $session->set("order_waiting", $orderLine->getId());

          /*  $this->addFlash(
                "success",
                "commande validée"
            );*/


            \Stripe\Stripe::setApiKey($bag->get("stripeKey"));
            //\Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET_KEY"]);


            $session = \Stripe\Checkout\Session::create([
                'line_items' => $stripeItems,
                'mode' => 'payment',
                'success_url' => 'https://localhost:8000/checkout_success/' . $token,
                'cancel_url' => 'http://localhost:8000/checkout_error'
            ]);


            return $this->redirect($session->url, 303);

            //  return $this->render('product/index.html.twig', [
            //     'products' => $products
            //  ]);
        }

        return $this->render('order/index.html.twig', [
            'form' => $form
        ]);

    }







}
