<?php

namespace HLIN601\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RegistrationProfesseurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, array('label' => 'form.first_name', 'translation_domain' => 'FOSUserBundle'))
            ->add('prenom', null, array('label' => 'form.last_name', 'translation_domain' => 'FOSUserBundle'))
            ->add('matieres', EntityType::class, array(
                    'multiple' => true,
                    'group_by' => 'classe',
                    'label' => 'form.subjects.titles',
                    'translation_domain' => 'FOSUserBundle',
                    'class' => 'HLIN601MOOCBundle:Matiere',
                    'choice_label' => 'intitule'))
            ->add('duree', ChoiceType::class, array(
                'label'=> 'Durée',
                'mapped' => false,
                'placeholder' => 'Choisissez une durée',
                'choices' => array(
                    'Une semaine' => 'week',
                    'Un mois' => 'month',
                    'Six mois' => 'months',
                    'Un an' => 'year',
                    'Jusqu\'à désinscription' => 'infinite'
                    )
                ))
            ->add('save', SubmitType::class, array('label' => 'registration.submit', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

    }

    public function getBlockPrefix()
    {
        return 'hlin601_user_registration';
    }
}