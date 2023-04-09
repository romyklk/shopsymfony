<?php

namespace App\Controller\Shop;

use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(ProductRepository $productRepository,CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();
        $products = $productRepository->findAll();
        return $this->render('shop/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
