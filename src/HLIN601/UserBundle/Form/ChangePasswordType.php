<?php

namespace HLIN601\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('save', SubmitType::class, array('label' => 'change_password.submit', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ChangePasswordFormType';

    }

    public function getBlockPrefix()
    {
        return 'hlin601_user_change_password';
    }
}