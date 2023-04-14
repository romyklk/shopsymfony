<?php

namespace App\Controller\Shop;

use App\Entity\SearchProduct;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(ProductRepository $productRepository,CategoriesRepository $categoriesRepository,Request $request): Response
    {
        $categories = $categoriesRepository->findAll();
        $products = $productRepository->findAll();


        // Créer le formlaire de recherche

        $search = new SearchProduct();
        $form = $this->createForm(SearchProductType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //dd($search);

            $products = $productRepository->findWithSearch($search);
            
        }

        if($products){
            foreach($products as $product){
                $product->setPrice($product->getPrice()/100);
            }
        }

        // Afficher le produit avec le prix/100

      



        return $this->render('shop/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'search' => $form->createView()
        ]);
    }
}
