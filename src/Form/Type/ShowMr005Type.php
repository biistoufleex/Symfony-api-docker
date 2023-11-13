<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class ShowMr005Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ipe', TextType::class, [
                'label' => 'IPE: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('finess', TextType::class, [
                'label' => 'N° FINESS: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('raisonSocial', TextType::class, [
                'label' => 'Raison Sociale: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('civilite', TextType::class, [
                'label' => 'Civilité: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('fonction', TextType::class, [
                'label' => 'Fonction: ',
                'attr' =>   [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('courriel', EmailType::class, [
                'label' => 'Courriel: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('numeroRecepice', IntegerType::class, [
                'label' => 'Numéro de récépissé: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('dateAttribution', TextType::class, [
                'label' => 'Date d\'attribution: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
            ->add('filePath', TextType::class, [
                'label' => 'Transmission du récépissé: ',
                'mapped' => true,
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                ],
            ])
        ;
    }
}