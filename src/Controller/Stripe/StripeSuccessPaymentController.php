<?php

namespace App\Controller\Stripe;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\EmailModel;
use App\Services\EmailSender;
use App\Services\CartServices;
use App\Services\StockManagerServices;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeSuccessPaymentController extends AbstractController
{
    #[Route('/stripe-success-payment/{stripeCheckoutSessionId}', name: 'app_stripe_success_payment')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface, OrderRepository $orderRepository, CartServices $cartServices,EmailSender $emailSender,StockManagerServices $stockManagerServices): Response
    {
        $stripeCheckoutSessionId = $request->get('stripeCheckoutSessionId');

        //dd($stripeCheckoutSessionId);

        $order = $orderRepository->findOneBy(['StripeCheckoutSessionId' => $stripeCheckoutSessionId]);


       // dd($order);
        // Si la commande n'existe pas ou si l'utilisateur n'est pas le propriétaire de la commande, on redirige vers la page d'accueil
        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Si la commande a déjà été payée, on redirige vers la page d'accueil
        if ($order->getIsPaid()) {
            return $this->redirectToRoute('app_home');
        }

       if(!$order->getIsPaid()){
            // On met à jour le statut de la commande
            $order->setIsPaid(true);

            // On met à jour le stock
            $stockManagerServices->updateStock($order);
            
            $entityManagerInterface->flush();

            // On vide le panier
            $cartServices->clearCart();

            $orderNumber = random_int(881, 6767686767) . time();

            // Envoi d'un email de confirmation de paiement
            $this->addFlash('success', 'Votre paiement a bien été effectué. Vous allez recevoir un email de confirmation.');

            // Envoi d'un email de confirmation de paiement
            $user = $this->getUser();

            $email = new EmailModel();
 

            $emailSender->sendEmailWithMailjet($user, $email,$order);

           // Créer un user qui sera l'admin qui va recevoir la notification
                $userAdmin = new User();
                $userAdmin->setEmail('romyklk2210+mailjet@gmail.com');
                $userAdmin->setFirstName('SYMSHOP');
                $userAdmin->setLastName('Shop');
            
                $email = new EmailModel();
                
                $emailSender->sendEmailToAdmin($userAdmin, $email,$order);
            

            // Récupérer la liste des produits de la commande
            $products = $order->getOrderDetails()->getValues();
        }

        return $this->render('stripe_success_payment/index.html.twig', [
            'order' => $order,
            'products' => $products,
            'orderNumber' => $orderNumber,
         ]);
    }

/*     // Méthode pour générer un nombre entier unique pour la commande (pour éviter les doublons) pour le numéro de commande

    public function generateOrderNumber()
    {
        $number= random_int(881, 6767686767) . time();
        return $number; 
    } */
}
