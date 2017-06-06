<?php

namespace HLIN601\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegistrationEtudiantController extends Controller
{
    public function registerAction()
    {
        return $this->container
                    ->get('pugx_multi_user.registration_manager')
                    ->register('HLIN601\UserBundle\Entity\Etudiant');
    }
}