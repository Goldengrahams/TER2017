<?php

namespace HLIN601\MOOCBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Qcm
 *
 * @ORM\Table(name="qcm")
 * @ORM\Entity(repositoryClass="HLIN601\MOOCBundle\Repository\QcmRepository")
 */
class Qcm
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime")
     *
     * @Assert\GreaterThan("now")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime")
     *
     * @Assert\Expression(
     *     "this.getDateFin() >= this.getDateDebut()",
     *     message="Cette valeur doit être supérieure à la date de début"
     * )
     */
    private $dateFin;
    
    /**
     * @ORM\ManyToOne(targetEntity="HLIN601\MOOCBundle\Entity\Chapitre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapitre;

    /**
     * @ORM\ManyToOne(targetEntity="HLIN601\UserBundle\Entity\Professeur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $professeur;

    /**
     * @ORM\OneToMany(targetEntity="HLIN601\MOOCBundle\Entity\Question", cascade={"persist"},mappedBy="qcm")
     */
    private $questions;


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Qcm
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Qcm
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
     * Set chapitre
     *
     * @param \HLIN601\UserBundle\Entity\Chapitre $chapitre
     *
     * @return Qcm
     */
    public function setChapitre(\HLIN601\MOOCBundle\Entity\Chapitre $chapitre)
    {
        $this->chapitre = $chapitre;

        return $this;
    }

    /**
     * Get chapitre
     *
     * @return \HLIN601\MOOCBundle\Entity\Chapitre
     */
    public function getChapitre()
    {
        return $this->chapitre;
    }

    /**
     * Set professeur
     *
     * @param \HLIN601\UserBundle\Entity\Professeur $professeur
     *
     * @return Qcm
     */
    public function setProfesseur(\HLIN601\UserBundle\Entity\Professeur $professeur)
    {
        $this->professeur = $professeur;

        return $this;
    }

    /**
     * Get professeur
     *
     * @return \HLIN601\UserBundle\Entity\Professeur
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Question
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
     * Add question
     *
     * @param \HLIN601\MOOCBundle\Entity\Question $question
     *
     * @return Qcm
     */
    public function addQuestion(\HLIN601\MOOCBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \HLIN601\MOOCBundle\Entity\Question $question
     */
    public function removeQuestion(\HLIN601\MOOCBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    public function __toString() {
        return $this->intitule;
    }
}
