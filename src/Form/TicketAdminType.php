<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Etat;
use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TicketAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('auteur', EmailType::class, [
                'label' => 'Email auteur',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 5],
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 20, max: 250),
                ],
            ])
            ->add('date_ouverture', DateTimeType::class, [
                'label' => 'Date d\'ouverture',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date_cloture', DateTimeType::class, [
                'label' => 'Date de clôture',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'nom',
                'label' => 'État',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('responsable', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Responsable',
                'required' => false,
                'attr' => ['class' => 'form-select'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}