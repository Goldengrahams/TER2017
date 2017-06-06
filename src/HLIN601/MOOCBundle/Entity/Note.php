<?php

namespace HLIN601\MOOCBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="HLIN601\MOOCBundle\Repository\NoteRepository")
 */
class Note
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
     * @var float
     *
     * @ORM\Column(name="valeur", type="float")
     */
    private $valeur;

    /**
     * @var float
     *
     * @ORM\Column(name="coef", type="float")
     */
    private $coef;

    /**
     * @ORM\ManyToOne(targetEntity="HLIN601\UserBundle\Entity\Etudiant", cascade={"persist"},inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etudiant;

    /**
     * @ORM\ManyToOne(targetEntity="HLIN601\MOOCBundle\Entity\Qcm", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $qcm;


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
     * Set valeur
     *
     * @param float $valeur
     *
     * @return Note
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return float
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set coef
     *
     * @param float $coef
     *
     * @return Note
     */
    public function setCoef($coef)
    {
        $this->coef = $coef;

        return $this;
    }

    /**
     * Get coef
     *
     * @return float
     */
    public function getCoef()
    {
        return $this->coef;
    }

    /**
     * Set etudiant
     *
     * @param \HLIN601\UserBundle\Entity\Etudiant $etudiant
     *
     * @return Note
     */
    public function setEtudiant(\HLIN601\UserBundle\Entity\Etudiant $etudiant)
    {
        $this->etudiant = $etudiant;

        $etudiant->addNote($this);

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return \HLIN601\UserBundle\Entity\Etudiant
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set qcm
     *
     * @param \HLIN601\MOOCBundle\Entity\Qcm $qcm
     *
     * @return Note
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
}
