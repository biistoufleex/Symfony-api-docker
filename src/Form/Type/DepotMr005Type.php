<?php

namespace App\Form\Type;

use App\Service\Application\DepotMr005FormulaireService;
use App\Validator\Constraints\RecepiceExist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class DepotMr005Type extends AbstractType
{
    private DepotMr005FormulaireService $depotMr005FormulaireService;

    public function __construct(DepotMr005FormulaireService $depotMr005FormulaireService)
    {
        $this->depotMr005FormulaireService = $depotMr005FormulaireService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        dd($options);
        $builder
            ->add('ipe', TextType::class, [
                'label' => 'IPE: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' =>'readonly'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L\'ipe est obligatoire.']),
                ],
            ])
            ->add('finess', TextType::class, [
                'label' => 'N° FINESS: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' =>'readonly'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La finess est obligatoire.']),
                ],
            ])
            ->add('raisonSociale', TextType::class, [
                'label' => 'Raison Sociale: ',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' =>'readonly'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La raison sociale est obligatoire.']),
                ],
            ])
            ->add('civilite', ChoiceType::class, [
                'label' => 'Civilité: ',
                'choices' => [
                    'M' => 'Monsieur',
                    'Mme' => 'Madame',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La civilité est obligatoire.']),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom: ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom: ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le prenom est obligatoire.']),
                ],
            ])
            ->add('fonction', TextType::class, [
                'label' => 'Fonction: ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La fonction est obligatoire.']),
                ],
            ])
            ->add('courriel', EmailType::class, [
                'label' => 'Courriel: ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le courriel est obligatoire.']),
                    new Email(['message' => 'Le courriel est invalide.']),
                ],
            ]);

            $numeroRecepiceConstraint = [
                new NotBlank(['message' => 'Le numéro de récépissé est obligatoire.']),
            ];
            if ($options['method'] != 'PUT') {
                $numeroRecepiceConstraint[] = new RecepiceExist($this->depotMr005FormulaireService);
            }
            $builder->add('numeroRecepice', TextType::class, [
                'label' => 'Numéro de récépissé: ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => $numeroRecepiceConstraint,

            ])->add('filePath', FileType::class, [
                'label' => 'Transmission du récépissé: ',
                'mapped' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'cliquer pour déposer votre récépissé de MR005',
                ],
            ])->add('dateAttribution', DateType::class, [
                'label' => 'Date d\'attribution: ',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Date d\'attribution',
                    'data-format' => 'dd-mm-yyyy',
                ],
            ]);
        if (!$options['disabled']) {
            $label = 'Envoyer la demande de validation';
            if ($options['method'] === 'PUT') {
                $label = 'Modifier/Valider';
            }
            $builder->add('send', SubmitType::class, [
                'label' => $label
            ]);
        }
    }
}