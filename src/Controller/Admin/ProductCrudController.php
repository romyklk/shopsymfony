<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name')->hideOnIndex(), // Le slug est généré automatiquement à partir du nom du produit
            TextEditorField::new('description'),
            TextField::new('moreInfos')->hideOnIndex(), // Plus d'infos sur le produit hideOnIndex() pour ne pas l'afficher dans la liste des produits
            MoneyField::new('price')->setCurrency('EUR'),
            IntegerField::new('quantity'),
            TextField::new('tags'),
            BooleanField::new('isBestSeller', 'Best Seller'),
            BooleanField::new('isFeatured', 'Featured'),
            BooleanField::new('isOnSale', 'On Sale'),
            BooleanField::new('isNewArrival', 'New Arrival'),
            BooleanField::new('isSpecialOffer', 'Special Offer'),
            AssociationField::new('category'),
            ImageField::new('image')->setBasePath('/assets/uploads/products/')
                                    ->setUploadDir('public/assets/uploads/products')
                                    ->setUploadedFileNamePattern('[randomhash].[extension]')
                                    ->setRequired(false),
        ];
    }
    
}
