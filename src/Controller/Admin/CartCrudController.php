<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('reference', 'Référence'),
            TextField::new('user.fullName', 'Client'),
            //TextField::new('fullName', 'Nom complet'),
            TextField::new('carrierName', 'Livreur'),
            MoneyField::new('carrierPrice', 'Frais de livraison')->setCurrency('EUR'),
            //MoneyField::new('subtotalHT', 'Sous-total')->setCurrency('EUR'),
           // MoneyField::new('taxe', 'TVA')->setCurrency('EUR'),
            MoneyField::new('subtotalTTC', 'Total')->setCurrency('EUR'),
            
            BooleanField::new('isPaid', 'Payé'),
        ];
    }

    // Cette fonction permet de trier les commandes par ordre décroissant
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    
}
