<?php

namespace HLIN601\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfileAdminFormType extends AbstractType
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
            ->add('save', SubmitType::class, array('label' => 'profile.edit.submit', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';

    }
}
