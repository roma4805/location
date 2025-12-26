<?php
// src/Form/VoitureType.php
namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque', TextType::class)
            ->add('modele', TextType::class)
            ->add('immatriculation', TextType::class)
            ->add('prix_journalier', NumberType::class)
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Disponible' => 'Disponible',
                    'Louée' => 'Louée',
                    'Maintenance' => 'Maintenance',
                ]
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Voiture::class]);
    }
}
