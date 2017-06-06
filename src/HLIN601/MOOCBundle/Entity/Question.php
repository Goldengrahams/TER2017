<?php

namespace HLIN601\MOOCBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="HLIN601\MOOCBundle\Repository\QuestionRepository")
 */
class Question
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
     * @ORM\ManyToOne(targetEntity="HLIN601\MOOCBundle\Entity\Qcm", cascade={"persist"}, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $qcm;

    /**
     * @ORM\OneToMany(targetEntity="HLIN601\MOOCBundle\Entity\Reponse", cascade={"persist"},mappedBy="question")
     */
    private $reponses;

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reponses = new ArrayCollection();
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
     * Set qcm
     *
     * @param \HLIN601\MOOCBundle\Entity\Qcm $qcm
     *
     * @return Question
     */
    public function setQcm(\HLIN601\MOOCBundle\Entity\Qcm $qcm)
    {
        $this->qcm = $qcm;

        return $this;
    }

    /**
     * Get qcm
     *
     * @return \HLIN601\MOOCBundle\Entity\Qcm
     */
    public function getQcm()
    {
        return $this->qcm;
    }

    /**
     * Add reponse
     *
     * @param \HLIN601\MOOCBundle\Entity\Reponse $reponse
     *
     * @return Question
     */
    public function addReponse(\HLIN601\MOOCBundle\Entity\Reponse $reponse)
    {
        $this->reponses[] = $reponse;

        return $this;
    }

    /**
     * Remove reponse
     *
     * @param \HLIN601\MOOCBundle\Entity\Reponse $reponse
     */
    public function removeReponse(\HLIN601\MOOCBundle\Entity\Reponse $reponse)
    {
        $this->reponses->removeElement($reponse);
    }

    /**
     * Get reponses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReponses()
    {
        return $this->reponses;
    }

    public function __toString() {
        return $this->intitule;
    }
}
