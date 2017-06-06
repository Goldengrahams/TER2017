<?php

namespace HLIN601\MOOCBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Classe
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity(repositoryClass="HLIN601\MOOCBundle\Repository\ClasseRepository")
 */
class Classe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=255)
     */
    private $intitule;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="HLIN601\MOOCBundle\Entity\Matiere", cascade={"persist"},mappedBy="classe")
     */
    private $matieres;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matieres = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Classe
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Classe
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add matiere
     *
     * @param \HLIN601\MOOCBundle\Entity\Matiere $matiere
     *
     * @return Classe
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

    public function __toString()
    {
        return (string) $this->getIntitule();
    }

}
