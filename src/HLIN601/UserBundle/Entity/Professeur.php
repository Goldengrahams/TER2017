<?php

namespace HLIN601\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="professeur")
 * @UniqueEntity(fields = "username", targetClass = "HLIN601\UserBundle\Entity\User", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "HLIN601\UserBundle\Entity\User", message="fos_user.email.already_used")
 */
class Professeur extends User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="HLIN601\MOOCBundle\Entity\Matiere", cascade={"persist"})
     */   
    private $matieres;


    
   
   
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setRoles(array('ROLE_PROF'));
        $this->matieres = new ArrayCollection();
    }

    /**
     * Add matiere
     *
     * @param \HLIN601\MOOCBundle\Entity\Matiere $matiere
     *
     * @return Professeur
     */
    public function addMatiere(\HLIN601\MOOCBundle\Entity\Matiere $matiere)
    {
        $this->matieres[] = $matiere;

        return $this;
    }

    /**
     * Remove matiere
     *
     * @param \HLIN601\MOOCBundle\Entity\Matiere $matiere
     */
    public function removeMatiere(\HLIN601\MOOCBundle\Entity\Matiere $matiere)
    {
        $this->matieres->removeElement($matiere);
    }

    /**
     * Get matieres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatieres()
    {
        return $this->matieres;
    }
}
