<?php
// src/HLIN601/UserBundle/DataFixtures/ORM/LoadEtudiant.php

namespace HLIN601\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HLIN601\UserBundle\Entity\Etudiant;
use HLIN601\MOOCBundle\Entity\Classe;
use HLIN601\MOOCBundle\Entity\Matière;

class LoadEtudiant extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  public function load(ObjectManager $manager)
  {
    $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
    $discriminator->setClass('HLIN601\UserBundle\Entity\Etudiant');

    $userManager = $this->container->get('pugx_user_manager');

    $etudiant = $userManager->createUser();

    $etudiant->setUsername('Etudiant');
    $etudiant->setEmail('Etudiant');
    $etudiant->setPlainPassword('etu');
    $etudiant->setNom('Test');
    $etudiant->setprenom('Etudiant');
    $etudiant->setEnabled(true);

    $repo = $manager->getRepository('HLIN601MOOCBundle:Classe');
    $classe = $repo->findOneByIntitule('Licence 3: Informatique');
    $etudiant->setFormule('class');
    $etudiant->setClasse($classe);
    $matieres = $classe->getMatieres();

    foreach($matieres as $matiere){
      $etudiant->addMatiere($matiere);
    }

    $userManager->updateUser($etudiant, true);

    // On déclenche l'enregistrement
    $manager->flush();
  }

  public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

  public function getOrder() {
    return 5;
  }
}