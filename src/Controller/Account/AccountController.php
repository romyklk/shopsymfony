<?php

namespace App\Controller\Account;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        // Récupérer toutes les commandes payées de l'utilisateur connecté et les trier par date décroissante
        $orders = $orderRepository->findBy(['user' => $this->getUser(),'isPaid' => true],['id' => 'DESC']);

       // dd($orders);

        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/account/order/{id}', name: 'app_account_order')]
    public function showOrder(?Order $order): Response
    {
        // Récupérer la commande payée de l'utilisateur connecté
        //$order = $orderRepository->findOneBy(['user' => $this->getUser(),'isPaid' => true,'id' => $id]);

       // dd($order);

        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute('app_home');
        }

        // Si la commande n'existe pas ou n'est pas payée, on redirige vers la page d'accueil
        if(!$order || !$order->getIsPaid()){
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/order_detail.html.twig', [
            'order' => $order,
        ]);
    }
}
