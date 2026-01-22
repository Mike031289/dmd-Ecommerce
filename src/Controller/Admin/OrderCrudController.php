<?php

namespace App\Controller\Admin;

use App\Entity\Order;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}
    
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Liste des commandes')
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new( 'Afficher')
            ->linkToCrudAction('show')
            ->setIcon('fa fa-eye')
            ->setCssClass('btn btn-light')
        ;

        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    /**
     * Change the state of an order
    */
    public function changeOrderState(Order $order, int $state): void
    {
        $order->setState($state);
        $this->em->flush();
    }

    /**
     * Custom show Action to display order
    */
    public function show(AdminContext $context, AdminUrlGenerator $adminUrlGenerator, Request $request): Response
    {
        // $order = $context->getEntity()->getInstance();
        $orderId = null;
        $orderId = $context->getRequest()->query->get('entityId');
        $order = $this->em->getRepository(Order::class)->find($orderId);

        if (!$order) {
            throw $this->createNotFoundException();
        }
        
        $currentUrl = $adminUrlGenerator  
            ->setController(crudControllerFqcn: self::class)
            ->setAction('show')
            ->setEntityId($order->getId())
            ->generateUrl();
            
        // here we make the treatement of the order state.
        if ($request->query->get('state')) {
            $this->changeOrderState($order, (int)$request->query->get('state'));
            $this->em->flush();
        }
        
        return $this->render('admin/order.html.twig', [
            'order' => $order,
            'current_url' => $currentUrl,
        ]);
 
    }

    /**
     * Fields to be displayed in the CRUD
    */
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