<?php

namespace App\Controller\Admin;

use App\Enum\OrderStatus;
use App\Entity\OrderAssociated;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderAssociatedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderAssociated::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('address'),
            TextField::new('firstname'),
            DateField::new('orderDateAssociated'),
            DateField::new('delivredDateAssociated'),
            ChoiceField::new('status')
                ->setFormType(EnumType::class)
                ->setFormTypeOptions([
                    'class' => OrderStatus::class,
                    'choices' => OrderStatus::cases()
                ]),
            NumberField::new('total')
          
        ];
    }
    
}
