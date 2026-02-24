<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Withdrawal;
use App\Enum\WithdrawalFrequency;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WithdrawalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', MoneyType::class, [
                'label' => 'Montant',
                'currency' => 'EUR',
                'help' => 'Montant du prélèvement en euros',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'Ex: Loyer, Abonnement Netflix...',
                ],
            ])
            ->add('frequency', ChoiceType::class, [
                'label' => 'Fréquence',
                'choices' => WithdrawalFrequency::cases(),
                'choice_label' => fn (WithdrawalFrequency $choice) => $choice->label(),
                'choice_value' => fn (?WithdrawalFrequency $choice) => $choice?->value,
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'help' => 'Date du premier prélèvement',
            ])
            ->add('nextWithdrawalDate', DateTimeType::class, [
                'label' => 'Prochain prélèvement',
                'widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'required' => false,
                'help' => 'Laissez vide si le prélèvement est sans fin',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une catégorie',
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Prélèvement actif',
                'required' => false,
                'help' => 'Cochez pour activer ce prélèvement',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Withdrawal::class,
        ]);
    }
}
