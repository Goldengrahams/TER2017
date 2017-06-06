<?php
// src/HLIN601/UserBundle/DataFixtures/ORM/LoadProfesseur.php

namespace HLIN601\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HLIN601\UserBundle\Entity\Professeur;
use HLIN601\UserBundle\Entity\Classe;

class LoadProfesseur extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  public function load(ObjectManager $manager)
  {
    $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
    $discriminator->setClass('HLIN601\UserBundle\Entity\Professeur');

    $userManager = $this->container->get('pugx_user_manager');

    $professeur = $userManager->createUser();

    $professeur->setUsername('Professeur');
    $professeur->setEmail('Professeur');
    $professeur->setPlainPassword('prof');
    $professeur->setNom('Test');
    $professeur->setprenom('Professeur');
    $professeur->setEnabled(true);

    $repo = $manager->getRepository('HLIN601MOOCBundle:Classe');
    $classe = $repo->findOneByIntitule('Licence 3: Informatique');
    foreach($classe->getMatieres() as $matiere){
      $professeur->addMatiere($matiere);
    }

    $userManager->updateUser($professeur, true);

    // On déclenche l'enregistrement
    $manager->flush();
  }

  public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

  public function getOrder() {
    return 6;
  }
}