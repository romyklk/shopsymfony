<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices
{
    private $session;
    private $productRepository;
    private $tva = 0.2;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    // Cette méthode permet d'ajouter un produit au panier. Elle prend en paramètre l'id du produit qui doit être ajouté au panier
    public function addToCart($id)
    {
        // Avant d'ajouter le produit au panier, on vérifie si le panier existe déjà dans le panier de la session. Si oui on incrémente la quantité du produit. Sinon on ajoute le produit au panier

        $cart = $this->getCart(); // On récupère le panier en session 

        // Si le produit existe déjà dans le panier, on incrémente sa quantité
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else // Sinon on ajoute le produit au panier
        {
            $cart[$id] = 1;
        }

        // On met à jour le panier en session
        $this->updateCart($cart);
    }






    // Cette méthode permet de supprimer un produit du panier. Elle prend en paramètre l'id du produit
    public function deleteFromCart($id)
    {
        // On récupère le panier en session
        $cart = $this->getCart();

        // Si le produit existe dans le panier, on va vérifier sa quantité. Si sa quantité est supérieure à 1, on décrémente sa quantité. Sinon on supprime le produit du panier
        if (isset($cart[$id])) {

            // Si la quantité du produit est supérieure à 1, on décrémente sa quantité
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else { // Sinon on supprime le produit du panier
                unset($cart[$id]);
            }

            // On met à jour le panier en session
            $this->updateCart($cart);
        }
    }

    // Cette méthode permet de vider le panier 
    public function clearCart()
    {
        // On met à jour le panier en session
        $this->updateCart([]);
    }

    // Cette méthode permet de supprimer un produit du panier. Elle prend en paramètre l'id du produit à supprimer
    public function deleteAllToCart($id)
    {
        // On récupère le panier en session
        $cart = $this->getCart();

        // Si le produit existe dans le panier, on le supprime 
        if (isset($cart[$id])) {
            unset($cart[$id]);

            // On met à jour le panier en session
            $this->updateCart($cart);
        }
    }



    public function updateCart($cart)
    {
        // On met à jour le panier en session
        $this->session->set('cart', $cart);

        // On met à jour le panier en session avec tous les produits. Cela permet de récupérer les produits en session dans le controller dans cartData
        $this->session->set('cartData', $this->getFullCart());
    }


    // Cette méthode permet de récupérer le contenu du panier
    public function getCart()
    {
        return $this->session->get('cart', []); // On retourne le panier, ou un tableau vide s'il n'existe pas
    }

    // Cette méthode permet de récupérer tous les produits du panier

    public function getFullCart()
    {
        // On récupère le panier en session
        $cart = $this->getCart();


        // On initialise un tableau qui contiendra tous les produits du panier
        $fullCart = [];

        // On initialise une variable qui contiendra la quantité totale des produits du panier
        $quantity_cart = 0;

        $subtotal = 0;

        foreach ($cart as $id => $quantity) {

            // On récupère le produit correspondant à l'id
            $product = $this->productRepository->find($id);

            // Si le produit existe, on l'ajoute au tableau $fullCart
            if ($product) {
                // On ajoute le produit au tableau $fullCart
                $fullCart['products'][] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                // On incrémente la quantité totale des produits du panier
                $quantity_cart += $quantity;

                // On incrémente le sous-total
                $subtotal += ($product->getPrice() / 100) * $quantity;

            } else {
                $this->deleteAllToCart($id); // On supprime le produit du panier
            }
        }

        // On ajoute la quantité totale des produits du panier et le sous-total au tableau $fullCart
        $fullCart['data'] = [
            'quantity_cart' => $quantity_cart,
            'subtotal' => $subtotal,
            'taxes' => round($subtotal * $this->tva, 2),
        ];

        // On retourne le tableau contenant tous les produits du panier et les données du panier
        return $fullCart; 
    }
}
