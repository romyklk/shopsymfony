<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\CartDetails;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderServices
{
    private $manager;
    private $productRepository;

    public function __construct(EntityManagerInterface $manager, ProductRepository $productRepository)
    {
        $this->manager = $manager;
        $this->productRepository = $productRepository;
    }

    // Méthode permettant de créer une commande
    public function createOrder($cart)
    {
        $order = new Order();
        $order->setReference($cart->getReference())
            ->setCarrierName($cart->getCarrierName())
            ->setCarrierPrice($cart->getCarrierPrice()/100)
            ->setFullName($cart->getFullName())
            ->setDeliveryAddress($cart->getDeliveryAddress())
            ->setMoreInformations($cart->getMoreInformations())
            ->setQuantity($cart->getQuantity())
            ->setSubtotalHT($cart->getSubtotalHT())
            ->setTaxe($cart->getTaxe())
            ->setSubtotalTTC($cart->getSubtotalTTC()/100)
            ->setUser($cart->getUser())
            ->setCreatedAt($cart->getCreatedAt());

        $this->manager->persist($order);


        $products = $cart->getCartDetails()->getValues();

        foreach ($products as $cart_product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setOrders($order)
                ->setProductName($cart_product->getProductName())
                ->setQuantity($cart_product->getQuantity())
                ->setProductPrice($cart_product->getProductPrice())
                ->setSubtotalHT($cart_product->getSubtotalHT())
                ->setSubtotalTTC($cart_product->getSubtotalTTC())
                ->setTaxe($cart_product->getTaxe())
            ;

            $this->manager->persist($orderDetails);
        }

        $this->manager->flush();

        return $order;
    }


    // Méthode permettant de sauvagarder un panier

    public function saveCart($data, $user)
    {

        $cart = new Cart();
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $carrier = $data['checkout']['carrier'];
        $informations = $data['checkout']['informations'];


        $cart->setReference($reference);
        $cart->setCarrierName($carrier->getName());
        $cart->setCarrierPrice($carrier->getPrice() / 100);
        $cart->setFullName($address->getFullName());
        $cart->setDeliveryAddress($address);
        $cart->setMoreInformations($informations);
        $cart->setQuantity($data['data']['quantity_cart']);
        $cart->setSubtotalHT($data['data']['subtotal']);
        $cart->setTaxe($data['data']['taxes']);
        $cart->setUser($user);
        $cart->setSubtotalTTC(round(($data['data']['total'] + ($carrier->getPrice() / 100)), 2));
        $cart->setCreatedAt(new \DateTimeImmutable('Europe/Paris'));

        $this->manager->persist($cart);

        $cart_details_array = [];

        foreach ($data['products'] as $products) {

            $cartDetails = new CartDetails();

            $subtotal = $products['quantity'] * $products['product']->getPrice() / 100;

            $cartDetails->setcarts($cart)
                ->setProductName($products['product']->getName())
                ->setQuantity($products['quantity'])
                ->setProductPrice($products['product']->getPrice() / 100)
                ->setSubtotalHT($subtotal)
                ->setSubtotalTTC($subtotal)
                ->setTaxe($subtotal * 0.2);
            $this->manager->persist($cartDetails);
            $cart_details_array[] = $cartDetails;
        }

        $this->manager->flush();

        return $reference;
    }

    // Méthode permettannt de générer un uuid pour les références des commandes
    public function generateUuid()
    {
        // Initialisation du générateur de nombres aléatoires

        mt_srand((float)microtime() * 10000);

        // Génération d'un nombre aléatoire
        // $charid  est une chaîne de 32 caractères hexadécimaux (0-9 et a-f) représentant un nombre aléatoire de 128 bits (32 * 4 bits)
        $charid = strtoupper(md5(uniqid(rand(), true)));

        // génération d'une chaine d'un octet (2 caractères hexadécimaux) à partir d'un nombre aléatoire
        $hyphen = chr(45);

        // Création d'un uuid

        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        return $uuid;
    }


    public function getLineItems($cart)
    {

        $cartDetails = $cart->getCartDetails();

        $line_items = [];

        foreach ($cartDetails as $details) {
            $product = $this->productRepository->findOneByName($details->getProductName());
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [$_ENV['YOUR_DOMAIN'] . 'assets/uploads/products/' . $product->getImage()],
                    ],
                ],
                'quantity' => $details->getQuantity(),
            ];
        }

        // GESTION DU FRAIS DE PORT

        $line_items[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $cart->getCarrierPrice(),
                'product_data' => [
                    'name' => "Livraison " . $cart->getCarrierName(),
                    'images' => [$_ENV['YOUR_DOMAIN'] . '/uploads/products/'],
                ],
            ],
            'quantity' => 1,
        ];


        // GESTION DE LA TAXE JE N'AJOUTE PAS LA TAXE CAR JE L'AI DÉJÀ AJOUTÉE DANS LE PRIX DU PRODUIT
/*         $line_items[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $cart->getTaxe() * 100,
                'product_data' => [
                    'name' => 'Taxes(TVA 20%)',
                    'images' => [$_ENV['YOUR_DOMAIN'] . '/uploads/products/'],
                ],
            ],
            'quantity' => 1,
        ];
 */


        return $line_items;
    }




  
}
