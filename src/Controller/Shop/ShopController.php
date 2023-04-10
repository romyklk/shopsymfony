<?php

namespace App\Controller\Shop;

use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(ProductRepository $productRepository,CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();
        $products = $productRepository->findAll();

        // Créer le formlaire de recherche

        $form = $this->createForm(SearchProductType::class,null);

        
        return $this->render('shop/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'search' => $form->createView()
        ]);
    }
}
