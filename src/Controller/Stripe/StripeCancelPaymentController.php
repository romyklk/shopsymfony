<?php

namespace App\Controller\Stripe;

use App\Entity\Order;
use App\Services\CartServices;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeCancelPaymentController extends AbstractController
{
    #[Route('/stripe-cancel-payment/{stripeCheckoutSessionId}', name: 'app_stripe_cancel_payment')]
    public function index(?Order $order, Request $request,EntityManagerInterface $entityManagerInterface, OrderRepository $orderRepository, CartServices $cartServices): Response
    {
        $stripeCheckoutSessionId = $request->get('stripeCheckoutSessionId');

        //dd($stripeCheckoutSessionId);

        $order = $orderRepository->findOneBy(['StripeCheckoutSessionId' => $stripeCheckoutSessionId]);

        // Si la commande n'existe pas ou si l'utilisateur n'est pas le propriétaire de la commande, on redirige vers la page d'accueil
        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $orderNumber = random_int(881, 6767686767) . time();

        return $this->render('stripe_cancel_payment/index.html.twig', [
            'order' => $order,
            'orderNumber' => $orderNumber,
        ]);
    }
}
