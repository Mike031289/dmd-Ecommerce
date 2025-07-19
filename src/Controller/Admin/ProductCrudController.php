<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            // ->setDateFormat('...')
            // ...
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $require = true;

        if ($pageName == 'edit') {
            $require = false;
        }

        return [
            TextField::new('name')
                ->setLabel('Nom')
                ->setHelp('Nom de votre produit'),
            SlugField::new('slug')
                ->setLabel('URL')
                ->setTargetFieldName('name')
                ->setHelp('URL de votre produit générée automatiquement'),
            ImageField::new('illustration')
                ->setLabel('Image')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/uploads')
                ->setUploadDir('/public/uploads')
                ->setHelp('Image du produit en 600x600px')
                ->setRequired($require),
            NumberField::new('price')
                ->setLabel('Prix H.T')
                ->setHelp('Prix H.T du produit sans le sigle €'),
            ChoiceField::new('tva')
                ->setLabel('Taux de TVA')
                ->setChoices(['5,5%' => '5.5','10%' => '10','20%' => '20'])
                ->setHelp('Choix du taux de TVA souhaité'),
            AssociationField::new('category')
                ->setLabel('Choix de la catégorie'),
            TextEditorField::new('description')
                ->setLabel('Description')
                ->setHelp('Description de votre produit'),
        ];
    }
}