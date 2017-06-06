<?php

namespace HLIN601\UserBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use HLIN601\MOOCBundle\Entity\Classe;

class ProfileController extends BaseController
{

    /**
     * Show the user.
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $em = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classes = $em->findAll();

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
            'listeClasses' => $classes
        ));
    }

    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            if($form->has('formule')){
                $formule = $form->get('formule')->getData();
                if($formule === 'all' or $formule === 'class'){
                    $matieresU = $user->getMatieres();
                    foreach($matieresU as $matiereU){
                        $user->removeMatiere($matiereU);
                    }
                }
                if($formule === 'all'){
                    $em = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
                    $matieres = $em->findAll();

                    foreach($matieres as $matiere){
                        $user->addMatiere($matiere);
                    }
                }
                elseif($formule === 'class'){
                    $em = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
                    $classe = $em->findOneByIntitule($form->get('classe')->getData()->getIntitule());
                    $matieres = $classe->getMatieres();

                    foreach($matieres as $matiere){
                        $user->addMatiere($matiere);
                    }
                }
            }

            $duree = $form->get('duree')->getData();
            $dateExpiration = $user->getDateExpiration();
            $user->setDateExpiration(null);

            $userManager->updateUser($user);

            if(is_null($dateExpiration)){
                if($duree === 'week'){
                    $user->setDateExpiration(new \DateTime('+1 week'));
                }
                if($duree === 'month'){
                    $user->setDateExpiration(new \DateTime('+1 month'));
                }
                if($duree === 'months'){
                    $user->setDateExpiration(new \DateTime('+6 months'));
                }
                if($duree === 'year'){
                    $user->setDateExpiration(new \DateTime('+1 year'));
                }
            }
            else{
                if($duree === 'week'){
                    $user->setDateExpiration($dateExpiration->modify('+1 week'));
                }
                if($duree === 'month'){
                    $user->setDateExpiration($dateExpiration->modify('+1 month'));
                }
                if($duree === 'months'){
                    $user->setDateExpiration($dateExpiration->modify('+6 months'));
                }
                if($duree === 'year'){
                    $user->setDateExpiration($dateExpiration->modify('+1 year'));
                }
            }

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('@FOSUser/Profile/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}