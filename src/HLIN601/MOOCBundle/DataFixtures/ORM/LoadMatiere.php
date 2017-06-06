<?php
// src/HLIN601/MOOCBundle/DataFixtures/ORM/LoadMatiere.php

namespace HLIN601\MOOCBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HLIN601\MOOCBundle\Entity\Classe;
use HLIN601\MOOCBundle\Entity\Matiere;
use Cocur\Slugify\Slugify;

class LoadMatiere extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des classes existantes
    $repo = $manager->getRepository('HLIN601MOOCBundle:Classe');
    $classes = $repo->findAll();

    // Liste des noms de matières à ajouter

    $matieres = array(
        array(
            array('code' => 'HLIN101','intitule' => 'Introduction à l\'Algorithmique et à la Pogrammation'),
            array('code' => 'HLIN102','intitule' => 'Du Binaire au Web'),
            array('code' => 'HLEE202','intitule' => 'Architecture des Ordinateurs'),
            array('code' => 'HLIN201','intitule' => 'De la Combinatoire aux Graphes'),
            array('code' => 'HLIN202','intitule' => 'Programmation Impérative')
            ),
        array(
            array('code' => 'HLIN301','intitule' => 'Algorithmes et Structure des Données Linéaires'),
            array('code' => 'HLIN302','intitule' => 'Programmation impérative avancée'),
            array('code' => 'HLIN304','intitule' => 'Systèmes d\'Information et Bases de Données 1'),
            array('code' => 'HLIN401','intitule' => 'Algorithmes et Complexité'),
            array('code' => 'HLIN402','intitule' => 'Logique 1'),
            array('code' => 'HLIN403','intitule' => 'Programmation applicative'),
            array('code' => 'HLIN406','intitule' => 'Modélisation et programmation par objet 1')
            ),
        array(
            array('code' => 'HLIN502','intitule' => 'Langages Formels'),
            array('code' => 'HLIN505','intitule' => 'Modélisation et Programmation par Objets 2'),
            array('code' => 'HLIN511','intitule' => 'Systèmes et Base de Données 2'),
            array('code' => 'HLIN602','intitule' => 'Logique 2'),
            array('code' => 'HLIN603','intitule' => 'Objets avancés'),
            array('code' => 'HLIN611','intitule' => 'Réseaux')
            )
        );

    $i = 0;
    foreach ($classes as $classe){
        foreach($matieres[$i] as $var){
            $matiere = new Matiere();
            $matiere->setCode($var['code']);
            $matiere->setIntitule($var['intitule']);

            $slug = new Slugify();
            $matiere->setSlug($slug->slugify($var['code']));

            $matiere->setClasse($classe);

            $manager->persist($matiere);
        }
        $i++;
    }

    $manager->flush();
  }

  public function getOrder() {
    return 2;
  }
}