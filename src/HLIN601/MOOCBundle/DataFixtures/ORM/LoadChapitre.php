<?php
// src/HLIN601/MOOCBundle/DataFixtures/ORM/LoadChapitre.php

namespace HLIN601\MOOCBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HLIN601\MOOCBundle\Entity\Classe;
use HLIN601\MOOCBundle\Entity\Matiere;
use HLIN601\MOOCBundle\Entity\Chapitre;
use Cocur\Slugify\Slugify;

class LoadChapitre extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des classes existantes
    $repo = $manager->getRepository('HLIN601MOOCBundle:Classe');
    $classe = $repo->findByIntitule('Licence 3: Informatique');

    $repo = $manager->getRepository('HLIN601MOOCBundle:Matiere');
    $matieres = $repo->findByClasse($classe);

    // Liste des noms de matières à ajouter

    $chapitres = array(
        array('Mots et Langages','Grammaires','Automates','ε-transitions','Détermination d\'automate','Expressions rationnelles','Minimisaton d\'automate complet','Lemme de la pompe'),
        array('Héritage en Java','Test unitaire avec JUnit','Vérifications de programmes Java','Interfaces graphiques','Diagrammes dynamiques en UML','Introspection et Annotations','Généricité'),
        array('Le modèle relationnel','Le langage algébrique','Le langage SQL (LMD)','Le modèle Entité Association','Le langage SQL (LDD)','Dépendances Fonctionnelles et Formes Normales','Les transactions','PL/SQL','Les triggers'),
        array('Syntaxe','Semantique'),
        array('Introduction','Classes et messages en C++','Spécialisation/généralisation et héritage simple','Inititalisation, affectation et conversion','Attributs et méthodes de classes','Généricité paramétrique','STL: la librairie standard C++','Spécialisation/généricité et héritage multiple','Gestion des exceptions','Controle d\'accès statique'),
        array('Introduction générale : éléments de base, architecture, les couches','Monde Internet : caractéristiques. Du problèmes des adresses','Ethernet : Protocoles d’adressage, d’erreurs et contrôles','Couche transport UDP et TCP. Programmation','Types de serveurs','Modes de connexion, protocoles sous-jacents','Configuration de réseaux et sous-réseaux. Problèmes de routage','Les protocoles d’application, messagerie, transfert de fichiers')
        );

    $i = 0;
    foreach ($matieres as $matiere){
        foreach($chapitres[$i] as $var){
            $chapitre = new Chapitre();
            $chapitre->setIntitule($var);

            $slug = new Slugify();
            $chapitre->setSlug($slug->slugify($var));

            $chapitre->setMatiere($matiere);

            $manager->persist($chapitre);
        }
        $i++;
    }

    $manager->flush();
  }

  public function getOrder() {
    return 3;
  }
}