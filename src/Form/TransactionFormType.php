<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Transaction;
use App\Enum\TransactionType; // ⚠️ enum métier
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('description')

            ->add('type', ChoiceType::class, [
                'choices' => TransactionType::cases(),
                'choice_label' => fn (TransactionType $choice) => $choice->label(),
                'choice_value' => fn (?TransactionType $choice) => $choice?->value,
                'expanded' => true,   // boutons radio
                'multiple' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
