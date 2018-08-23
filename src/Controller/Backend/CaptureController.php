<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\User;
use App\Services\Form\FormManager;
use App\Services\Image\ImageManager;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\User\NAOUserManager;
use App\Form\Capture\ParticularCaptureType;
use App\Form\Capture\NaturalistCaptureType;
use App\Form\Capture\ValidateCaptureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CaptureController extends Controller
{
    /**
     * @Route("/ajouter-observation", name="add_capture")
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOUserManager $naoUserManager
     * @param ValidatorInterface $validator
     * @param FormManager $formManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addCaptureAction(Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOUserManager $naoUserManager, ValidatorInterface $validator, FormManager $formManager)
    {
        $capture = new Capture();
        $current_user = $this->getUser();
        $user = $naoUserManager->getCurrentUser($current_user->getUsername());
        $userRole = $naoUserManager->getRoleFR($user);
        $role = $naoUserManager->getNaturalistOrParticularRole($user);
        $form = $formManager->getCaptureForm($userRole, $capture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Capture $capture */
            $capture = $naoCaptureManager->setStatusOnCapture($form->getData(), $user);
            $listErrors = $validator->validate($capture);
            if (count($listErrors) > 0) {
                return $this->redirectToRoute('ajouterObservation', array('list_errors' => (string)$listErrors));
            } else {
                $naoManager->addOrModifyEntity($capture);
                $this->addFlash('success', "Votre observation a été ajoutée avec succès !");
                return $this->redirectToRoute('add_image_on_capture', ['id' => $capture->getId()]);
            }
        }
        $title = 'Ajouter une observation';

        return $this->render(
            'Capture\addModifyOrValidateCapture.html.twig',
            array(
                'form' => $form->createView(),
                'userRole' => $userRole,
                'role' => $role,
                'title' => $title
            )
        );
    }

    /**
     * @Route(path="ajout-image-observation/{id}", name="add_image_on_capture")
     * @param Request $request
     * @param Capture $capture
     * @param ImageManager $imageManager
     * @ParamConverter("capture", class="App\Entity\Capture")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addImageOnCapture(Request $request, Capture $capture, ImageManager $imageManager)
    {
        $form = $this->createForm(CaptureImageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageManager->addImageOnCapture($form->getData()['image'], $capture);
            $this->addFlash('success', "Image ajoutée à l'observation !");
            return $this->redirectToRoute('compteUtilisateur');
        }
        return $this->render(
            'Capture/addImageOnCapture.html.twig',
            array(
                'form' => $form->createView(),
                'capture' => $capture
            )
        );
    }

    /**
     * @Route("/valider-observation/{id}", name="validate_capture", requirements={"id" = "\d+"})
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOUserManager $naoUserManager
     * @param $id
     * @return Response
     */
    public function validateCaptureAction(Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOUserManager $naoUserManager, $id)
    {
        $capture = $naoManager->getEm()->getRepository(Capture::class)->findOneById($id);

        $form = $this->get('form.factory')->create(validateCaptureType::class, $capture);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {   
            $validator = $this->get('validator');
            $listErrors = $validator->validate($capture);
            if(count($listErrors) > 0) 
            {
                return new Response((string) $listErrors);
            } 
            else 
            {
                if ($form->get('validate')->isClicked())
                {
                    $naturalist = $this->getUser();

                    $naoCaptureManager->validateCapture($capture, $naturalist);
                    $naoManager->addOrModifyEntity($capture);
                }
                elseif ($form->get('waitingForValidation')->isClicked())
                {
                    $naoManager->addOrModifyEntity($capture);
                }
                elseif ($form->get('remove')->isClicked())
                {
                    $naoManager->removeEntity($capture);
                }

                return $this->redirectToRoute('admin_space');
            }
        }

        $user = $this->getUser();
        $userRole = $naoUserManager->getRoleFR($user);
        $title = 'Valider une observation';

        return $this->render('Capture\addModifyOrValidateCapture.html.twig', 
            array
            (
                'form' => $form->createView(), 
                'userRole' => $userRole, 
                'titre' => $title
            )); 
    }

    /**
     * @Route("/modifier-observation", name="modify_capture")
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOUserManager $naoUserManager
     * @param $capture
     * @return Response
     */
    public function modifyCaptureAction(Request $request, NAOManager $naoManager, NAOUserManager $naoUserManager, $capture)
    {
        $capture = $naoManager->getEm()->getRepository(Capture::class)->find($capture); 
        $form = $this->createForm(NaturalistCaptureType::class, $capture);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $validator = $this->get('validator');
            $listErrors = $validator->validate($capture);
            if(count($listErrors) > 0) 
            {
                return new Response((string) $listErrors);
            } 
            else 
            {
                $naoManager->addOrModifyEntity($capture);

                return new Response('L\'observation a été modifiée');
            }
        }

        $user = $this->getUser();
        $userRole = $naoUserManager->getRoleFR($user);
        $title = 'Modifier une observation';

        return $this->render('Capture\addModifyOrValidateCapture.html.twig', 
            array
            (
                'form' => $form->createView(), 
                'userRole' => $userRole, 
                'titre' => $title
            )); 
    }
}
