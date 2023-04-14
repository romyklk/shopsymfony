<?php

namespace App\Services;

use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StockManagerServices
{
    private $manager;
    private $repoProduct;

    public function __construct(EntityManagerInterface $manager, ProductRepository $repoProduct)
    {
        $this->manager = $manager;
        $this->repoProduct = $repoProduct;
    }

    // Méthode qui permet de gérer les stocks

    public function updateStock(Order $order)
    {
        $orderDetails = $order->getOrderDetails()->getValues();

        foreach ($orderDetails as $key=>$details) {
            $product = $this->repoProduct->findOneByName($details->getProductName());

            $product->setQuantity($product->getQuantity() - $details->getQuantity());

            $this->manager->persist($product);
        }
            
        $this->manager->flush();

    }

}

