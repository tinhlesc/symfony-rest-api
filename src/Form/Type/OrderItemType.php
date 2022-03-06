<?php

namespace App\Form\Type;

use App\Entity\OrderItem;
use App\Form\EventListener\ClearCartListener;
use App\Form\EventListener\RemoveCartItemListener;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                    'class' => 'App\Entity\Product',
                ]
            )
            ->add('quantity', NumberType::class)
            ->add('save', SubmitType::class)
            ->add('clear', SubmitType::class);

        $builder->addEventSubscriber(new RemoveCartItemListener());
        $builder->addEventSubscriber(new ClearCartListener());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class,
            'csrf_protection' => false,
        ]);
    }
}
