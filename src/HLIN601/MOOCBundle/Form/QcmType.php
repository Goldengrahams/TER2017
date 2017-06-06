<?php

namespace HLIN601\MOOCBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use HLIN601\MOOCBundle\Entity\Question;

class QcmType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $question = new Question();
        $builder->add('intitule')
        ->add('dateDebut', null, array(
            'label' => 'Date de début',
            'years' => range(date('Y'),date('Y')+1),
            'with_minutes' => false
            ))
        ->add('dateFin', null, array(
            'label' => 'Date de fin',
            'years' => range(date('Y'),date('Y')+1),
            'with_minutes' => false
            ))
        ->add('questions', CollectionType::class, array(
            'entry_type'   => QuestionType::class,
            'allow_add'    => true,
            'allow_delete' => true,
            'prototype' => true,
            'prototype_data' => $question
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HLIN601\MOOCBundle\Entity\Qcm'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hlin601_moocbundle_qcm';
    }


}
