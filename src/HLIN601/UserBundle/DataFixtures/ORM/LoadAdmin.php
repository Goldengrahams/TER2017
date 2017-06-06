<?php
// src/HLIN601/UserBundle/DataFixtures/ORM/LoadUser.php

namespace HLIN601\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HLIN601\UserBundle\Entity\Admin;

class LoadAdmin extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listUsernames = array('LucasAdmin', 'DavidAdmin', 'SabrineAdmin', 'EmileAdmin');

    $listeNames = array(
      array('nom' => 'Creuzet','prenom' => 'Lucas'),
      array('nom' => 'Cossard','prenom' => 'David'),
      array('nom' => 'El Manouny','prenom' => 'Sabrine'),
      array('nom' => 'Kaba','prenom' => 'Emile')
      );
    $i = 0;
    foreach ($listUsernames as $username) {
      $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
      $discriminator->setClass('HLIN601\UserBundle\Entity\Admin');

      $userManager = $this->container->get('pugx_user_manager');

      $admin = $userManager->createUser();

      $admin->setUsername($username);
      $admin->setEmail($username);
      $admin->setPlainPassword('admin');
      $admin->setNom($listeNames[$i]['nom']);
      $admin->setprenom($listeNames[$i]['prenom']);
      $admin->setEnabled(true);

      $userManager->updateUser($admin, true);
      $i++;
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }

  public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

  public function getOrder() {
    return 4;
  }
}