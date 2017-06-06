<?php

namespace HLIN601\MOOCBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Matiere
 *
 * @ORM\Table(name="matiere")
 * @ORM\Entity(repositoryClass="HLIN601\MOOCBundle\Repository\MatiereRepository")
 */
class Matiere
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

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
     * @ORM\OneToMany(targetEntity="HLIN601\MOOCBundle\Entity\Chapitre", cascade={"persist"},mappedBy="matiere")
     */
    private $chapitres;

    /**
     * @ORM\ManyToOne(targetEntity="HLIN601\MOOCBundle\Entity\Classe", cascade={"persist"},inversedBy="matieres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;
   

   
   
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chapitres = new ArrayCollection();
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
     * Set code
     *
     * @param string $code
     *
     * @return Matiere
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Matiere
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
     * @return Matiere
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
     * Add chapitre
     *
     * @param \HLIN601\MOOCBundle\Entity\Chapitre $chapitre
     *
     * @return Matiere
     */
    public function addChapitre(\HLIN601\MOOCBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres[] = $chapitre;

        return $this;
    }

    /**
     * Remove chapitre
     *
     * @param \HLIN601\MOOCBundle\Entity\Chapitre $chapitre
     */
    public function removeChapitre(\HLIN601\MOOCBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitres->removeElement($chapitre);
    }

    /**
     * Get chapitres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChapitres()
    {
        return $this->chapitres;
    }

    /**
     * Set classe
     *
     * @param \HLIN601\MOOCBundle\Entity\Classe $classe
     *
     * @return Matiere
     */
    public function setClasse(\HLIN601\MOOCBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        $classe->addMatiere($this);

        return $this;
    }

    /**
     * Get classe
     *
     * @return \HLIN601\MOOCBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    public function __toString()
    {
        return (string) $this->getIntitule();
    }
}
