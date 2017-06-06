<?php

namespace HLIN601\MOOCBundle\Controller;

use HLIN601\MOOCBundle\Entity\Classe;
use HLIN601\MOOCBundle\Entity\Matiere;
use HLIN601\MOOCBundle\Entity\Chapitre;
use HLIN601\MOOCBundle\Entity\Qcm;
use HLIN601\MOOCBundle\Entity\Note;
use HLIN601\MOOCBundle\Form\ReponseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use \DateTime;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;


/**
 * Qcm controller.
 *
 * @Route("schoolmooc/{classeSlug}/{matiereSlug}/{chapitreSlug}/{type}")
 */
class QcmController extends Controller
{

    /**
     * Lists all qcm entities.
     *
     * @Route("/", name="qcm_index")
     * @Method("GET")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function indexAction($classeSlug,$matiereSlug,$chapitreSlug,$type)
    {
        $user = $this->getUser();
        $dateExpiration = $user->getDateExpiration();
        if($dateExpiration && $dateExpiration < new DateTime()){
            return $this->redirectToRoute('fos_user_security_logout');   
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneBySlug($matiereSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);

        if(in_array('ROLE_ETU',$user->getRoles()) && !$user->getMatieres()->contains($matiere)){
            return $this->redirectToRoute('hlin601_mooc_classe',array('classeSlug' => $classeSlug));
        }

        if($type == 'cours-video'){
            $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Video');
            $video = $chapitre->getVideo();

            return $this->render('HLIN601MOOCBundle:Cours:video.html.twig',array(
                'user' => $user,
                'classe' => $classe,
                'matiere' => $matiere,
                'chapitre' => $chapitre,
                'type' => $type,
                'video' => $video
                ));
        }
        elseif($type == 'fiche-de-cours'){
            $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Cours');
            $cours = $chapitre->getCours();

            return $this->render('HLIN601MOOCBundle:Cours:cours.html.twig',array(
                'user' => $user,
                'classe' => $classe,
                'matiere' => $matiere,
                'chapitre' => $chapitre,
                'type' => $type,
                'cours' => $cours
                ));
        }
        elseif($type == 'qcm'){

            $em = $this->getDoctrine()->getManager();

            $qcms = $em->getRepository('HLIN601MOOCBundle:Qcm')->findByChapitre($chapitre);

            return $this->render('qcm/index.html.twig', array(
                'user' => $user,
                'classe' => $classe,
                'matiere' => $matiere,
                'chapitre' => $chapitre,
                'type' => $type,
                'qcms' => $qcms,
            ));
        }
        elseif($type == 'fiche-de-synthese'){
            return $this->render('HLIN601MOOCBundle:Cours:synthese.html.twig',array(
                'user' => $user,
                'classe' => $classe,
                'matiere' => $matiere,
                'chapitre' => $chapitre,
                'type' => $type
                ));
        }
        elseif($type == 'streaming'){
            return $this->render('HLIN601MOOCBundle:Cours:streaming.html.twig',array(
                'user' => $user,
                'classe' => $classe,
                'matiere' => $matiere,
                'chapitre' => $chapitre,
                'type' => $type
                ));
        }
        else{
            return $this->redirectToRoute('hlin601_mooc_classe',array('classeSlug' => $classe->getSlug()));
        }
    }

    /**
     * Creates a new qcm entity.
     *
     * @Route("/new", name="qcm_new")
     * @Method({"GET", "POST"})
     */
    public function newAction($classeSlug,$matiereSlug,$chapitreSlug,$type, Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneBySlug($matiereSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);

        $qcm = new Qcm();
        $qcm->setChapitre($chapitre);
        $qcm->setProfesseur($user);

        $form = $this->createForm('HLIN601\MOOCBundle\Form\QcmType', $qcm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questions = $qcm->getQuestions();
            foreach ($questions as $question) {
                $reponses = $question->getReponses();
                foreach($reponses as $reponse){
                    $reponse->setQuestion($question);
                }
                $question->setQcm($qcm);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($qcm);

            $em->flush();

            return $this->redirectToRoute('qcm_show', array(
                'classeSlug' => $classe->getSlug(),
                'matiereSlug' => $matiere->getSlug(),
                'chapitreSlug' => $chapitre->getSlug(),
                'type' => $type,
                'id' => $qcm->getId()
            ));
        }

        return $this->render('qcm/new.html.twig', array(
            'classe' => $classe,
            'matiere' => $matiere,
            'chapitre' => $chapitre,
            'type' => $type,
            'qcm' => $qcm,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qcm entity.
     *
     * @Route("/{id}", name="qcm_show")
     * @Method("GET")
     */
    public function showAction($classeSlug,$matiereSlug,$chapitreSlug,$type, Qcm $qcm)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneBySlug($matiereSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);

        $deleteForm = $this->createDeleteForm($classeSlug,$matiereSlug,$chapitreSlug,$type,$qcm);

        return $this->render('qcm/show.html.twig', array(
            'classe' => $classe,
            'matiere' => $matiere,
            'chapitre' => $chapitre,
            'type' => $type,
            'qcm' => $qcm,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qcm entity.
     *
     * @Route("/{id}/edit", name="qcm_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($classeSlug,$matiereSlug,$chapitreSlug,$type, Request $request, Qcm $qcm)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneBySlug($matiereSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);

        $deleteForm = $this->createDeleteForm($classeSlug,$matiereSlug,$chapitreSlug,$type,$qcm);
        $editForm = $this->createForm('HLIN601\MOOCBundle\Form\QcmType', $qcm);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('qcm_edit', array(
                'classeSlug' => $classe->getSlug(),
                'matiereSlug' => $matiere->getSlug(),
                'chapitreSlug' => $chapitre->getSlug(),
                'type' => $type,
                'id' => $qcm->getId()
            ));
        }

        return $this->render('qcm/edit.html.twig', array(
            'classe' => $classe,
            'matiere' => $matiere,
            'chapitre' => $chapitre,
            'type' => $type,
            'qcm' => $qcm,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qcm entity.
     *
     * @Route("/{id}", name="qcm_delete")
     * @Method("DELETE")
     */
    public function deleteAction($classeSlug,$matiereSlug,$chapitreSlug,$type, Request $request, Qcm $qcm)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneBySlug($matiereSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);

        $form = $this->createDeleteForm($classeSlug,$matiereSlug,$chapitreSlug,$type,$qcm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qcm);
            $em->flush();
        }

        return $this->redirectToRoute('qcm_index',array(
            'classeSlug' => $classe->getSlug(),
            'matiereSlug' => $matiere->getSlug(),
            'chapitreSlug' => $chapitre->getSlug(),
            'type' => $type
        ));
    }

    /**
     * Creates a form to delete a qcm entity.
     *
     * @param Qcm $qcm The qcm entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($classeSlug,$matiereSlug,$chapitreSlug,$type,Qcm $qcm)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneBySlug($matiereSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('qcm_delete', array(
                'classeSlug' => $classe->getSlug(),
                'matiereSlug' => $matiere->getSlug(),
                'chapitreSlug' => $chapitre->getSlug(),
                'type' => $type,
                'id' => $qcm->getId()
            )))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Passer un qcm
     *
     * @Route("/{id}/faire", name="qcm_faire")
     * @Method({"GET", "POST"})
     */
    public function faireQcm($classeSlug,$matiereSlug,$chapitreSlug,$type, Qcm $qcm, Request $request){
        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneBySlug($matiereSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);

        $em = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Question');
        $questions = $em->findByQcm($qcm);
        $formBuilderQuestionnaire = $this->createFormBuilder();
        $i = 0;
        foreach ($questions as $question) {
            /* @var $question Question */
            $formBuilder = $this->get('form.factory')->createNamedBuilder($i, FormType::class, $questions);
            $formBuilder
                ->add('rep', EntityType::class, [
                    'class' => 'HLIN601\MOOCBundle\Entity\Reponse',
                    'expanded' => true,
                    'multiple' => true,
                    'label' => $question->getIntitule(),
                    'query_builder' => function (EntityRepository $er) use ($question) {
                        return $er->createQueryBuilder('reponse')
                            ->join('reponse.question', 'question')
                            ->where('reponse.question = :questionId')
                            ->setParameter('questionId', $question->getId());
                    },
                ]);
            $formBuilderQuestionnaire->add($formBuilder);
            $i++;
        }
        $form = $formBuilderQuestionnaire->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $j = 0;
            $res = 0;
            $questions = $qcm->getQuestions();
            foreach ($questions as $question) {
                $rep = $data[$j]['rep'];    
                $reponses = $question->getReponses();
                $nbT = 0;
                $nbF = 0;
                foreach($reponses as $reponse){
                    if($reponse->getValeur() == true)
                        $nbT++;
                    else
                        $nbF++;
                }
                foreach($reponses as $reponse){
                    if($rep->contains($reponse) && $reponse->getValeur() == true){
                        $res += 1/$nbT;
                    }
                    if($rep->contains($reponse) && $reponse->getValeur() == false){
                        $res += -1/$nbF;
                    }
                }
                $j++;
            }
            if($res < 0)
                $res = 0;

            $temp = $res - floor($res);
            if($temp < 0.125)
                $res = floor($res);
            elseif($temp < 0.375)
                $res = floor($res) + 0.25;
            elseif($temp < 0.625)
                $res = floor($res) + 0.5;
            elseif($temp < 0.875)
                $res = floor($res) + 0.75;
            else
                $res = ceil($res);

            $em = $this->getDoctrine()->getManager();

            $note = new Note();
            $note->setValeur($res);

            $coef = count($questions)/20;
            $note->setCoef($coef);

            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $note->setEtudiant($user);

            $note->setQcm($qcm);

            $em->flush();


            return $this->redirectToRoute('qcm_correction', array(
                'classeSlug' => $classe->getSlug(),
                'matiereSlug' => $matiere->getSlug(),
                'chapitreSlug' => $chapitre->getSlug(),
                'type' => $type,
                'id' => $qcm->getId(),
                'note' => $note->getId()
            ));
        }

        return $this->render('qcm/faire.html.twig', array(
            'classe' => $classe,
            'matiere' => $matiere,
            'chapitre' => $chapitre,
            'type' => $type,
            'qcm' => $qcm,
            'questions' => $questions,
            'faire_form' => $form->createView(),
        ));
    }

    /**
     * Correction du qcm
     *
     * @Route("/{id}/correction", name="qcm_correction")
     * @Method({"GET", "POST"})
     */
    public function correctionQcm($classeSlug,$matiereSlug,$chapitreSlug,$type, Qcm $qcm, Request $request){
        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Classe');
        $classe = $repository->findOneBySlug($classeSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Matiere');
        $matiere = $repository->findOneBySlug($matiereSlug);

        $repository = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Chapitre');
        $chapitre = $repository->findOneBySlug($chapitreSlug);

        $em = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Question');
        $questions = $em->findByQcm($qcm);
        $formBuilderQuestionnaire = $this->createFormBuilder();
        $i = 0;
        foreach ($questions as $question) {
            /* @var $question Question */
            $formBuilder = $this->get('form.factory')->createNamedBuilder($i, FormType::class, $questions);
            $formBuilder
                ->add('rep', EntityType::class, [
                    'class' => 'HLIN601\MOOCBundle\Entity\Reponse',
                    'expanded' => true,
                    'multiple' => true,
                    'label' => $question->getIntitule(),
                    'disabled' => true,
                    'query_builder' => function (EntityRepository $er) use ($question) {
                        return $er->createQueryBuilder('reponse')
                            ->join('reponse.question', 'question')
                            ->where('reponse.question = :questionId')
                            ->setParameter('questionId', $question->getId());
                    },
                ]);
            $formBuilderQuestionnaire->add($formBuilder);
            $i++;
        }
        $form = $formBuilderQuestionnaire->getForm();

        $j = 0;
        $res = 0;
        $questions = $qcm->getQuestions();
        foreach ($questions as $question) {
            $k = 0;
            $rep = $form[$j]['rep'];    
            $reponses = $question->getReponses();
            foreach($rep as $reponse){
                if($reponses[$k]->getValeur() == true)
                    $reponse->setData(true);
                if($reponses[$k]->getValeur() == false)
                    $reponse->setData(false);
                $k++;
            }
            $j++;
        }

        $em = $this->getDoctrine()->getManager()->getRepository('HLIN601MOOCBundle:Note');
        $note = $em->findOneById($request->query->get('note'));

        return $this->render('qcm/correction.html.twig', array(
            'classe' => $classe,
            'matiere' => $matiere,
            'chapitre' => $chapitre,
            'type' => $type,
            'qcm' => $qcm,
            'questions' => $questions,
            'faire_form' => $form->createView(),
            'note' => $note
        ));
    }
}
