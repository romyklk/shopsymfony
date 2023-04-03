<?php

namespace App\Controller\Admin;


use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
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


    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    
}
