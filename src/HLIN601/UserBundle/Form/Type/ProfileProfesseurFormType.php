<?php

namespace HLIN601\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfileProfesseurFormType extends AbstractType
{

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'hlin601_user_profile';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, array('label' => 'form.first_name', 'translation_domain' => 'FOSUserBundle'))
            ->add('prenom', null, array('label' => 'form.last_name', 'translation_domain' => 'FOSUserBundle'))
            ->add('duree', ChoiceType::class, array(
                'required' => false,
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
            ->add('save', SubmitType::class, array('label' => 'profile.edit.submit', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';

    }
}
