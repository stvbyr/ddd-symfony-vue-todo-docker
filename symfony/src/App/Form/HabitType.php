<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HabitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('fromDate', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'attr' => [
                    'step' => 900,
                    'min' => (new \DateTimeImmutable())->format('Y-m-d'),
                ],
            ])
            ->add('toDate', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'attr' => [
                    'step' => 900,
                    'min' => (new \DateTimeImmutable())->format('Y-m-d'),
                ],
            ])
            ->add('frequency', ChoiceType::class, [
                'choices' => function () {
                    $choices = ['daily', 'weekly', 'yearly'];

                    return array_combine($choices, $choices);
                },
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
