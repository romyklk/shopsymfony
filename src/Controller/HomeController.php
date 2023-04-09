<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\HomeSliderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $repoProduct,HomeSliderRepository $homeSliderRepository): Response
    {
        $products = $repoProduct->findAll(); // Récupère tous les produits
        // dd($products);
        // Filtrer par critères (BestSeller, Featured, etc.)
        //$productsBestSeller = $repoProduct->findBy(['isBestSeller' => true]);
        $productsBestSeller = $repoProduct->findByIsBestSeller(true);

       // $productsFeatured = $repoProduct->findBy(['isFeatured' => true]);
        $productsFeatured = $repoProduct->findByIsFeatured(true); // findBy + nom de la propriété (isFeatured) + valeur de la propriété (true) . revient à faire findBy(['isFeatured' => true]) mais plus court et plus lisible .

        $productsNewArrival = $repoProduct->findByIsNewArrival(true);

        $productsOnSale = $repoProduct->findByIsOnSale(true);
        $productsIsSpecialOffer = $repoProduct->findByIsSpecialOffer(true);

       // dd($productsBestSeller, $productsFeatured, $productsNewArrival, $productsOnSale, $productsIsSpecialOffer);

        //$homeSliders = $homeSliderRepository->findBy(['isDisplayed' => true]);
        $homeSliders = $homeSliderRepository->findByIsDisplayed(true);
       // dd($homeSliders);

        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productsBestSeller' => $productsBestSeller,
            'productsFeatured' => $productsFeatured,
            'productsNewArrival' => $productsNewArrival,
            'productsOnSale' => $productsOnSale,
            'productsIsSpecialOffer' => $productsIsSpecialOffer,
            'homeSliders' => $homeSliders,


        ]);
    }

    // Pour afficher un produit en particulier
    #[Route('/product/{slug}', name: 'app_product_detail')] // slug = nom du produit
    // injection de dépendance ? pour dire que le param peut être nul
    public function show(?Product $product): Response 
    {
        if(!$product) { // Si le produit n'existe pas
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/single_product.html.twig', [
            'product' => $product, // On envoie le produit à la vue
        ]);
    }
}
