<?php

namespace HLIN601\MOOCBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Streaming
 *
 * @ORM\Table(name="streaming")
 * @ORM\Entity(repositoryClass="HLIN601\MOOCBundle\Repository\StreamingRepository")
 */
class Streaming
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
     * @var \DateTime
     *
     * @ORM\Column(name="datePrevue", type="datetime")
     */
    private $datePrevue;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPlaces", type="integer")
     */
    private $nbPlaces;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime")
     */
    private $dateFin;
    
    /**
     * @ORM\ManyToOne(targetEntity="HLIN601\UserBundle\Entity\Professeur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $professeurs;



    

     public function __construct()
  {
    $this->dateDebut = new \Datetime();
  }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datePrevue
     *
     * @param \DateTime $datePrevue
     *
     * @return Streaming
     */
    public function setDatePrevue($datePrevue)
    {
        $this->datePrevue = $datePrevue;

        return $this;
    }

    /**
     * Get datePrevue
     *
     * @return \DateTime
     */
    public function getDatePrevue()
    {
        return $this->datePrevue;
    }

    /**
     * Set nbPlaces
     *
     * @param integer $nbPlaces
     *
     * @return Streaming
     */
    public function setNbPlaces($nbPlaces)
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    /**
     * Get nbPlaces
     *
     * @return int
     */
    public function getNbPlaces()
    {
        return $this->nbPlaces;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Streaming
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set professeurs
     *
     * @param \HLIN601\UserBundle\Entity\Professeur $professeurs
     *
     * @return Streaming
     */
    public function setProfesseurs(\HLIN601\UserBundle\Entity\Professeur $professeurs)
    {
        $this->professeurs = $professeurs;

        return $this;
    }

    /**
     * Get professeurs
     *
     * @return \HLIN601\UserBundle\Entity\Professeur
     */
    public function getProfesseurs()
    {
        return $this->professeurs;
    }
}
