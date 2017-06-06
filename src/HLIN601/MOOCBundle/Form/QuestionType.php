<?php

namespace HLIN601\MOOCBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use HLIN601\MOOCBundle\Entity\Reponse;

class QuestionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('intitule')
        ->add('reponses', CollectionType::class, array(
            'entry_type'   => ReponseType::class,
            'allow_add'    => true,
            'allow_delete' => true,
            'prototype' => true,
            'prototype_name' => '__reponse__'
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HLIN601\MOOCBundle\Entity\Question'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hlin601_moocbundle_question';
    }


}
