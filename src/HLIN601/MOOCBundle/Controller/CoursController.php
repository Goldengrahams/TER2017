<?php

namespace HLIN601\MOOCBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use \DateTime;
use HLIN601\MOOCBundle\Entity\Etudiant;
use HLIN601\MOOCBundle\Entity\Classe;
use HLIN601\MOOCBundle\Entity\Matiere;
use HLIN601\MOOCBundle\Entity\Chapitre;
use HLIN601\MOOCBundle\Entity\Cours;
use HLIN601\MOOCBundle\Entity\Video;
use HLIN601\MOOCBundle\Entity\Qcm;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CoursController extends Controller
{       
    public function compteAction()
    {
        
        return $this->render('HLIN601MOOCBundle:Cours:compte.html.twig');
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function classeAction($classeSlug)
    {
        $user = $this->getUser();
        $dateExpiration = $user->getDateExpiration();
        if($dateExpiration && $dateExpiration < new DateTime()){
            return $this->redirectToRoute('fos_user_security_logout');   
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);
        $listeClasses = $repository->findAll();

        return $this->render('HLIN601MOOCBundle:Cours:classe.html.twig',array('user' => $user, 'classe' => $classe, 'listeClasses' => $listeClasses));
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function classeMatiereAction($classeSlug, $matiereSlug)
    {
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);
        $listeClasses = $repository->findAll();

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneByCode($matiereSlug);

        return $this->render('HLIN601MOOCBundle:Cours:classe.html.twig',array('user' => $user, 'classe' => $classe,'matiere' => $matiere, 'listeClasses' => $listeClasses));
    }

    public function choixclasseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $listeClasses = $em->getRepository('HLIN601MOOCBundle:Classe')->findAll();

        return $this->render('HLIN601MOOCBundle:Cours:choixclasse.html.twig',array('listeClasses' => $listeClasses));
    }

    public function matiereAction($classe,$matiere)
    {

        $listeChap = $matiere->getChapitres();

        return $this->render('HLIN601MOOCBundle:Cours:matiere.html.twig',array(
            'classe' => $classe,
            'matiere' => $matiere,
            'listeChap' => $listeChap
            ));
    }

    public function menuAction($classe)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ETU') && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $matieres = $user->getMatieres();

            $em = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');

            $listeMatieres = $em->findMatieresByUserAndClasse($matieres,$classe);
        }
        else{
            $listeMatieres = $classe->getMatieres();
        }

        return $this->render('HLIN601MOOCBundle:Cours:menu.html.twig', array('classe' => $classe, 'listeMatieres' => $listeMatieres));
    }

   /**
     *
     * @Method({"GET", "POST"})
     * @Route("/upload/file", name="upload_file")
     */
    public function uploadFileAction($chapitreSlug,$type, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $request->files->get('file');
        if($type === 'fiche-de-cours' && $media->getClientOriginalExtension() === 'pdf'){
            $document = new Cours();
        }
        elseif($type === 'cours-video' && $media->getClientOriginalExtension() === 'mp4'){
            $document = new Video();
        }

        $document->setFile($media);
        $document->setPath($media->getClientOriginalName());
        $dt = new DateTime();
        $document->setDatePoste($dt);
        $document->upload();

        $em->persist($document);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);
        $matiere = $chapitre->getMatiere();
        $classe = $matiere->getClasse();

        if($media->getClientOriginalExtension() === 'pdf'){
            $chapitre->setCours($document);
        }
        elseif($media->getClientOriginalExtension() === 'mp4'){
            $chapitre->setVideo($document);
        }

        $admins = $this->getDoctrine()->getManager()->getRepository('HLIN601UserBundle:Admin')->findAll();

        $url = $this->generateUrl('hlin601_mooc_chapitre',array(
            'classeSlug' => $classe->getSlug(),
            'matiereSlug' => $matiere->getSlug(),
            'chapitreSlug' => $chapitre->getSlug(),
            'type' => $type));

        $prof = $this->getUser();

        $manager = $this->get('mgilet.notification');
        foreach($admins as $admin){
            if($type === "fiche-de-cours"){
                $manager->createNotification($admin,
                    'Nouvelle fiche de cours',
                    $prof->getPrenom()." ".$prof->getNom()." dans ".$matiere->getCode()." - ".$chapitre->getIntitule(),
                    $url);
            }
            elseif($type === "cours-video"){
                $manager->createNotification($admin,
                    'Nouvelle vidéo',
                    $prof->getPrenom()." ".$prof->getNom()." dans ".$matiere->getCode()." - ".$chapitre->getIntitule(),
                    $url);
            }
        }

        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }

    /**
     *
     * @Method({"GET", "POST"})
     * @Route("/remove/file", name="remove_file")
     */
    public function removeFileAction($chapitreSlug,$type,Request $request){
        $em = $this->getDoctrine()->getManager();
        $chapitre = $em->getRepository('HLIN601MOOCBundle:Chapitre')->findOneBySlug($chapitreSlug);

        if($type === "fiche-de-cours"){
            $cours = $chapitre->getCours();
            $chapitre->setCours(null);
            $em->remove($cours);
        }
        elseif($type === "cours-video"){
            $video = $chapitre->getVideo();
            $chapitre->setVideo(null);
            $em->remove($video);
        }

        $em->flush();

        return new JsonResponse(array('success' => true));
    }

    /**
     *
     * @Method({"GET", "POST"})
     * @Route("/activer", name="activer")
     */
    public function activerAction($chapitreSlug,$type,Request $request){
        $em = $this->getDoctrine()->getManager();
        $chapitre = $em->getRepository('HLIN601MOOCBundle:Chapitre')->findOneBySlug($chapitreSlug);

        if($type === "fiche-de-cours"){
            $cours = $chapitre->getCours();
            $cours->setActive(true);
        }
        elseif($type === "cours-video"){
            $video = $chapitre->getVideo();
            $video->setActive(true);
        }

        $em->flush();

        return new JsonResponse(array('success' => true));
    }
}
