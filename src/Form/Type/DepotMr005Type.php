<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;


class DepotMr005Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ipe', TextType::class, [
                'label' => 'IPE',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'IPE',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L\'ipe est obligatoire.']),
                ],
            ])
            ->add('finess', TextType::class, [
                'label' => 'FINESS',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'FINESS',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La finess est obligatoire.']),
                ],
            ])
            ->add('raisonSociale', TextType::class, [
                'label' => 'Raison sociale',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Raison sociale',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La raison sociale est obligatoire.']),
                ],
            ])
            ->add('civilite', ChoiceType::class, [
                'label' => 'Civilité',
                'choices' => [
                    'M' => 'Monsieur',
                    'Mme' => 'Madame',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Civilité',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La civilité est obligatoire.']),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom',
                ],
            ])
            ->add('fonction', TextType::class, [
                'label' => 'Fonction',
                'attr' =>   [
                    'class' => 'form-control',
                    'placeholder' => 'Fonction',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La fonction est obligatoire.']),
                ],
            ])
            ->add('courriel', EmailType::class, [
                'label' => 'Courriel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Courriel',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le courriel est obligatoire.']),
                    new Email(['message' => 'Le courriel est invalide.']),
                ],
            ])
            ->add('numeroRecepice', IntegerType::class, [
                'label' => 'Numéro de récépissé',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Numéro de récépissé',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro de récépissé est obligatoire.']),
                ],
            ])
            ->add('dateAtribution', DateType::class, [
                'label' => 'Date d\'attribution',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Date d\'attribution',
                    'widget' => 'choice',
                ],
            ])
            ->add('fileType', FileType::class, [
                'label' => 'Fichier',
                'mapped' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Fichier',
                ],
            ])
            ->add('send', SubmitType::class)
        ;
    }
}