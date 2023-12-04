<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrganisationAutorisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identifiantOrganisationPlage', TextType::class, [
                'label' => 'Identifiant Organisation Plage: ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L\'identifiant organisation plage est obligatoire.']),
                ],
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début: ',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est obligatoire.']),
                ],
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin: ',
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control ',
                ],
            ])
            ->add('perimetre', ChoiceType::class, [
                'label' => 'Périmètre: ',
                'choices' => [
                    'Médico Social' => 'medico_social',
                    'Finance' => 'finance',
                    'Activité' => 'activite',
                    'Ressources Humaines' => 'ressources_humaines',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le périmètre est obligatoire.']),
                ],
            ])
            ->add('typeAutorisation', ChoiceType::class, [
                'label' => 'Type d\'autorisation: ',
                'choices' => [
                    'Accès permanent' => 'acces_permanent',
                    'Autorisation en propre' => 'autorisation_en_propre',
                    'PDH' => 'pdh',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le type d\'autorisation est obligatoire.']),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }
}