<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Transporteur')
            ->setEntityLabelInPlural('Transporteurs')
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
                ->setHelp('Nom du transporteur'),
            TextField::new('Description')
                ->setLabel('Description')
                ->setHelp('Description du transporteur')
                ->renderAsHtml(),
             NumberField::new('price')
                ->setLabel('Prix T.T.C')
                ->setHelp('Prix T.T.C du transporteur sans le sigle €'),
        ];
    }

    // /*
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id'),
    //         TextField::new('title'),
    //         TextEditorField::new('description'),
    //     ];
    // }
    // */
}