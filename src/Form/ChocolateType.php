<?php

namespace App\Form;

use App\Entity\Chocolate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ChocolateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du chocolat'
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix'
            ])
            ->add('chocolate', TextType::class, [
                'required' => true,
                'label' => 'Code'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chocolate::class,
        ]);
    }
}
