<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class OrderCrudController extends AbstractCrudController
{
    
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Liste des commandes')
            // ->setPageTitle(Crud::PAGE_INDEX, 'Liste des commande')
            ->setDefaultSort(['id' => 'DESC'])
            ->overrideTemplates([
            'crud/detail' => 'admin/order.html.twig',
        ]);
            // ->setDateFormat('...')
            // ...
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('show', 'Afficher')
            ->linkToCrudAction('detail')
            ->setIcon('fa fa-eye')
            ->setCssClass('btn btn-dark')
        ;

        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date de la commande'),
            NumberField::new('state')->setLabel('Statut de la commande')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user')->setLabel('Nom du client'),
            TextField::new('carrierName')->setLabel('Transporteur'),
            NumberField::new('totalTva')->setLabel('Total TVA'),
            NumberField::new('totalWt')->setLabel('Prix total TTC')->setNumDecimals(2),
        ];
    }
    
}