<?php

namespace App\Controller\Cart;

use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $cartServices;

    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }


    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        // On récupère le panier en session
        $cart = $this->cartServices->getFullCart();

        // Si le panier est vide, on redirige l'utilisateur vers la page d'accueil
        if(!isset($cart['products'])){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    // Cette méthode permet d'ajouter un produit au panier. Elle prend en paramètre l'id du produit qui doit être ajouté au panier

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function addToCart($id): Response
    {
         // On ajoute le produit au panier
        $this->cartServices->addToCart($id);

        // On redirige l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_cart');
    }


    // Cette méthode permet de supprimer un produit du panier. Elle prend en paramètre l'id du produit

    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function deleteFromCart($id, CartServices $cartServices): Response
    {
        // On supprime le produit du panier
        $cartServices->deleteFromCart($id);

        // On redirige l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    // Cette méthode permet de vider le panier

    #[Route('/cart/empty', name: 'app_cart_empty')]
    public function emptyCart(): Response
    {
        // On vide le panier
        $this->cartServices->clearCart();

        // On redirige l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    // Cette méthode permet de supprimer un produit du panier. Elle prend en paramètre l'id du produit

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function removeFromCart($id): Response
    {
        // On supprime le produit du panier
        $this->cartServices->deleteAllToCart($id);

        // On redirige l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

}
