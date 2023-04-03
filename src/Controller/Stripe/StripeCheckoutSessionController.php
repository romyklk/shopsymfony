<?php

namespace App\Controller\Stripe;

use Stripe\Stripe;
use App\Entity\Cart;
use Stripe\Checkout\Session;
use App\Services\CartServices;
use App\Services\OrderServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeCheckoutSessionController extends AbstractController
{
    #[Route('/checkout-session/{reference}', name: 'app_create_checkout_session')]
    public function index(?Cart $cart, OrderServices $orderServices, EntityManagerInterface $entityManagerInterface): Response
    {
         //$cart = $cartServices->getFullCart();

        $user = $this->getUser();
        

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Si le panier est vide, on redirige vers la page d'accueil
        if (!$cart) {
            return $this->redirectToRoute('app_home');
        }

        // Création de l'intent de paiement. L'intent de paiement est une requête vers l'API Stripe qui permet de créer un paiement non finalisé (en attente de confirmation de la part de l'utilisateur) et de récupérer un identifiant client_secret qui permettra de finaliser le paiement. 
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $cart->getSubtotalTTC(),
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'receipt_email' => $user->getEmail(),
            'description' => 'Paiement de la commande ' . $cart->getReference() ,
        ]); 

        // envoi de l'intent de paiement à la vue
       // $intent = $paymentIntent->client_secret;


        $order= $orderServices->createOrder($cart);
        
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $orderServices->getLineItems($cart),
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . 'stripe-success-payment/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . 'stripe-cancel-payment/{CHECKOUT_SESSION_ID}',
        ]);


        // Pour enregistrer l'id de la session stripe dans la commande
        $order->setStripeCheckoutSessionId($checkout_session->id);
        $entityManagerInterface->flush();


        return $this->json([
            'id' => $checkout_session->id,
            
        ]);
    }
}
