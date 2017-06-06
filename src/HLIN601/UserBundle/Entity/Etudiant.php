<?php

namespace HLIN601\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="etudiant")
 * @UniqueEntity(fields = "username", targetClass = "HLIN601\UserBundle\Entity\User", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "HLIN601\UserBundle\Entity\User", message="fos_user.email.already_used")
 */
class Etudiant extends User
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
     * @var string
     *
     * @ORM\Column(name="formule", type="string", length=6)
     *
     */
    private $formule;

    /**
     * @ORM\ManyToOne(targetEntity="HLIN601\MOOCBundle\Entity\Classe", cascade={"persist"})
     */
    private $classe;

    /**
     * @ORM\ManyToMany(targetEntity="HLIN601\MOOCBundle\Entity\Matiere", cascade={"persist"})
     */   
    private $matieres;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="HLIN601\MOOCBundle\Entity\Streaming", cascade={"persist"})
     */
    private $streaming;

    /**
     * @ORM\OneToMany(targetEntity="HLIN601\MOOCBundle\Entity\Note", cascade={"persist"},mappedBy="etudiant")
     */
    private $notes;
    
    /**
     * @ORM\ManyToMany(targetEntity="HLIN601\MOOCBundle\Entity\Video", cascade={"persist"})
     */ 
    private $videos;
  

    

  
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setRoles(array('ROLE_ETU'));
        $this->matieres = new ArrayCollection();
        $this->streaming = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    /**
     * Set formule
     *
     * @param integer $formule
     *
     * @return Etudiant
     */
    public function setFormule($formule)
    {
        $this->formule = $formule;

        return $this;
    }

    /**
     * Get formule
     *
     * @return integer
     */
    public function getFormule()
    {
        return $this->formule;
    }

    /**
     * Set classe
     *
     * @param \HLIN601\MOOCBundle\Entity\Classe $classe
     *
     * @return Etudiant
     */
    public function setClasse(\HLIN601\MOOCBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

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

    /**
     * Add matiere
     *
     * @param \HLIN601\MOOCBundle\Entity\Matiere $matiere
     *
     * @return Etudiant
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

    public function setSingleMatiere(\HLIN601\MOOCBundle\Entity\Matiere $matiere = null)
    {
        if (!$matiere) {
            return;
        }

        foreach($this->matieres as $temp){
            $this->removeMatiere($temp);
        }

        $this->addMatiere($matiere);
    }

    // Which one should it use for pre-filling the form's default data?
    // That's defined by this getter.  I think you probably just want the first?
    public function getSingleMatiere()
    {
        return $this->matieres->first();
    }

    /**
     * Add streaming
     *
     * @param \HLIN601\MOOCBundle\Entity\Streaming $streaming
     *
     * @return Etudiant
     */
    public function addStreaming(\HLIN601\MOOCBundle\Entity\Streaming $streaming)
    {
        $this->streaming[] = $streaming;

        return $this;
    }

    /**
     * Remove streaming
     *
     * @param \HLIN601\MOOCBundle\Entity\Streaming $streaming
     */
    public function removeStreaming(\HLIN601\MOOCBundle\Entity\Streaming $streaming)
    {
        $this->streaming->removeElement($streaming);
    }

    /**
     * Get streaming
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStreaming()
    {
        return $this->streaming;
    }

    /**
     * Add note
     *
     * @param \HLIN601\MOOCBundle\Entity\Note $note
     *
     * @return Etudiant
     */
    public function addNote(\HLIN601\MOOCBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \HLIN601\MOOCBundle\Entity\Note $note
     */
    public function removeNote(\HLIN601\MOOCBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    public function __toString()
    {
        return (string) $this->getIntitule();
    }

    /**
     * Add video
     *
     * @param \HLIN601\MOOCBundle\Entity\Video $video
     *
     * @return Etudiant
     */
    public function addVideo(\HLIN601\MOOCBundle\Entity\Video $video)
    {
        $this->videos[] = $video;

        return $this;
    }

    /**
     * Remove video
     *
     * @param \HLIN601\MOOCBundle\Entity\Video $video
     */
    public function removeVideo(\HLIN601\MOOCBundle\Entity\Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }
}
