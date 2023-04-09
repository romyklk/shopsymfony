<?php

namespace App\Controller\Admin;

use App\Entity\HomeSlider;
use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HomeSliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomeSlider::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description'),
            TextField::new('buttonMessage'),
            TextField::new('buttonLink'),
            ImageField::new('image')->setBasePath('/assets/uploads/carousel/')
                                    ->setUploadDir('public/assets/uploads/carousel/')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),   
            BooleanField::new('isDisplayed', 'Active'),
        ];
    }
    
}
