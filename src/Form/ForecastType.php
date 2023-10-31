<?php

namespace App\Form;

use App\Entity\Forecast;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class)
            ->add('temperature', NumberType::class, [
                'attr' => [
                    'min' => -50,
                    'max' => 50,
                ],
                'html5' => true,
            ])
            ->add('cloud', TextType::class, [
                'attr' => [
                    'placeholder' => 'Cloud',
                    'minLength' => 3,
                ],
            ])
            ->add('atmospheric_pressure', NumberType::class, [
                'attr' => [
                    'min' => 800,
                    'max' => 1100,
                ],
                'html5' => true,
            ])
            ->add('location', EntityType::class, [
                'class' => 'App\Entity\Location',
                'choice_label' => 'city',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forecast::class,
        ]);
    }
}
