<?php

namespace HLIN601\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProfileEtudiantFormType extends AbstractType
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
            ->add('formule', ChoiceType::class, array(
                'label' => 'form.package.title',
                'choices' => array(
                    'form.package.all' => 'all',
                    'form.package.class' => 'class',
                    'form.package.choose' => 'choose',
                    'form.package.one' => 'one'),
                'translation_domain' => 'FOSUserBundle'
                ))
            ->add('duree', ChoiceType::class, array(
                'required' => false,
                'label'=> 'Alonger la durée',
                'mapped' => false,
                'placeholder' => 'Choisissez une durée à ajouter',
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

        $formModifier = function (FormInterface $form, $formule) {
            if($formule === 'choose'){
                $form->add('matieres', EntityType::class, array(
                    'multiple' => true,
                    'group_by' => 'classe',
                    'label' => 'form.subjects.titles',
                    'translation_domain' => 'FOSUserBundle',
                    'class' => 'HLIN601MOOCBundle:Matiere',
                    'choice_label' => 'intitule'));
            }
            elseif($formule === 'one'){
                $form->add('singleMatiere', EntityType::class, array(
                    'group_by' => 'classe',
                    'label' => 'form.subjects.title',
                    'translation_domain' => 'FOSUserBundle',
                    'placeholder' => 'form.subjects.choice',
                    'class' => 'HLIN601MOOCBundle:Matiere',
                    'choice_label' => 'intitule'
                    ));
            }
            elseif($formule === 'class'){
                $form->add('classe', EntityType::class, array(
                    'label' => 'form.class.title',
                    'translation_domain' => 'FOSUserBundle',
                    'placeholder' => 'form.class.choice',
                    'class' => 'HLIN601MOOCBundle:Classe',
                    'choice_label' => 'intitule'));
            }
            elseif($formule === 'all'){
                $form->add('matieres', null, array(
                    'mapped' => false,
                    'data' => 'Inscription à toutes les matières',
                    'label' => ' ',
                    'disabled' => true
                    ));
            }
        };

        $builder->get('formule')->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $formule = $event->getForm()->getData();

                $formModifier($event->getForm()->getParent(), $formule);
            }
        );

        $builder->get('formule')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $formule = $event->getForm()->getData();

                $formModifier($event->getForm()->getParent(), $formule);
            }
        );
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';

    }
}
