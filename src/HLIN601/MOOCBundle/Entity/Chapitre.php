<?php

namespace HLIN601\MOOCBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chapitre
 *
 * @ORM\Table(name="chapitre")
 * @ORM\Entity(repositoryClass="HLIN601\MOOCBundle\Repository\ChapitreRepository")
 */
class Chapitre
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
     * @ORM\ManyToOne(targetEntity="HLIN601\MOOCBundle\Entity\Matiere", cascade={"persist"},inversedBy="chapitres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @ORM\OneToOne(targetEntity="HLIN601\MOOCBundle\Entity\Cours", cascade={"persist","remove"})
     */
    private $cours;

    /**
     * @ORM\OneToOne(targetEntity="HLIN601\MOOCBundle\Entity\Video", cascade={"persist","remove"})
     */
    private $video;
   

   
   
    

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
     * @return Chapitre
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
     * @return Chapitre
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
     * Set matiere
     *
     * @param \HLIN601\MOOCBundle\Entity\Matiere $matiere
     *
     * @return Chapitre
     */
    public function setMatiere(\HLIN601\MOOCBundle\Entity\Matiere $matiere = null)
    {
        $this->matiere = $matiere;

        $matiere->addChapitre($this);

        return $this;
    }

    /**
     * Get matiere
     *
     * @return \HLIN601\MOOCBundle\Entity\Matiere
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * Set cours
     *
     * @param \HLIN601\MOOCBundle\Entity\Cours $cours
     *
     * @return Chapitre
     */
    public function setCours(\HLIN601\MOOCBundle\Entity\Cours $cours = null)
    {
        $this->cours = $cours;

        return $this;
    }

    /**
     * Get cours
     *
     * @return \HLIN601\MOOCBundle\Entity\Cours
     */
    public function getCours()
    {
        return $this->cours;
    }

    /**
     * Set video
     *
     * @param \HLIN601\MOOCBundle\Entity\Video $video
     *
     * @return Chapitre
     */
    public function setVideo(\HLIN601\MOOCBundle\Entity\Video $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \HLIN601\MOOCBundle\Entity\Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getIntitule();
    }
}
