<?php

namespace App\Form;

use App\Entity\Stock;
use App\Entity\Products;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')

            ->add('Product', EntityType::class, [
                'expanded' => false,
                'required' => false,
                'class' => Products::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'select2'
                ]
            ])

          
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
