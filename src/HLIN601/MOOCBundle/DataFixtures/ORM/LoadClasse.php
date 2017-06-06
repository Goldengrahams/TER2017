<?php
// src/HLIN601/MOOCBundle/DataFixtures/ORM/LoadClasse.php

namespace HLIN601\MOOCBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HLIN601\MOOCBundle\Entity\Classe;
use Cocur\Slugify\Slugify;

class LoadClasse extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {

    $classes = array('Licence 1: Informatique','Licence 2: Informatique','Licence 3: Informatique');

    foreach($classes as $var){
        $classe = new Classe();
        $classe->setIntitule($var);
        $slug = new Slugify();
        $classe->setSlug($slug->slugify($var));

        $manager->persist($classe);
    }

    $manager->flush();
  }

  public function getOrder() {
    return 1;
  }
}