<?php

namespace HLIN601\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="admin")
 * @UniqueEntity(fields = "username", targetClass = "HLIN601\UserBundle\Entity\User", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "HLIN601\UserBundle\Entity\User", message="fos_user.email.already_used")
 */
class Admin extends User
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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setRoles(array('ROLE_ADMIN'));
    }
}
