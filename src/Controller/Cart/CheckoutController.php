<?php

namespace App\Controller\Cart;

use App\Form\CheckoutType;
use App\Services\CartServices;
use App\Services\OrderServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    private $cartServices;
    private $session;

    public function __construct(CartServices $cartServices, SessionInterface $session)
    {
        $this->cartServices = $cartServices;
        $this->session = $session;
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();


        $cart = $this->cartServices->getFullCart(); // On récupère le panier
        //dd($cart);
        if (!isset($cart['products'])) {
            return $this->redirectToRoute('app_home');
        }



        // Si l'utilisateur n'a pas d'adresse, on le redirige vers la page d'ajout d'adresse
        if (!$user->getAddresses()->getValues()) {
            $this->addFlash('warning', 'Vous devez ajouter une adresse avant de pouvoir passer commande');
            return $this->redirectToRoute('app_address_new');
        }

         $form = $this->createForm(CheckoutType::class, null, [
            'user' => $user
        ]); 

        // Verifier s'il y a des données dans la session
        if($this->session->get('checkout_data')){
            return $this->redirectToRoute('app_checkout_confirm');
        }


        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }


    // Validation du panier 
    #[Route('/checkout/confirm', name: 'app_checkout_confirm')]
    public function confirm(Request $request,OrderServices $orderServices): Response
    {

        $user = $this->getUser();
        $cart = $this->cartServices->getFullCart(); // On récupère le panier

        if (!isset($cart['products'])) {
            return $this->redirectToRoute('app_home');
        }



        // Si l'utilisateur n'a pas d'adresse, on le redirige vers la page d'ajout d'adresse
        if (!$user->getAddresses()->getValues()) {
            $this->addFlash('warning', 'Vous devez ajouter une adresse avant de pouvoir passer commande');
            return $this->redirectToRoute('app_address_new');
        }

        $form = $this->createForm(CheckoutType::class, null, [
            'user' => $user
        ]);

        $form->handleRequest($request);
       // dd($this->session);

        if ($form->isSubmitted() && $form->isValid() || $this->session->get('checkout_data')) {
            // Si le formulaire est soumis et valide, on récupère les données du formulaire qui sont stockées dans la session
            
            
            if($this->session->get('checkout_data')){
                $data = $this->session->get('checkout_data');
            }else{
                $data = $form->getData();
                $this->session->set('checkout_data', $data); // On stocke les données dans la session
            }
            
            $address = $data['address'];
            $carrier = $data['carrier'];
            $informations = $data['informations'];
            
            // Sauvegarde du panier
            $cart['checkout'] = $data;
            $reference = $orderServices->saveCart($cart, $user);
            
            //dd($reference);

            

            return $this->render('checkout/confirm.html.twig', [
                'cart' => $cart,
                'address' => $address,
                'carrier' => $carrier,
                'informations' => $informations,
                'reference' => $reference,
                'form' => $form->createView(),
                'PUBLISHABLE_KEY' => $_ENV['STRIPE_PUBLISHABLE_KEY']
            ]);
        }

        return $this->redirectToRoute('app_checkout');
    
    }

    // Cette méthode permet de vider la session et de revenir à la page de paiement
    #[Route('/checkout/edit', name: 'app_checkout_edit')]
    public function checkoutEdit(): Response
    {
        $this->session->set('checkout_data',[]);
        return $this->redirectToRoute('app_checkout');
    }


}
