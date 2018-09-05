<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\Image;
use App\Form\Image\ImageType;
use App\Services\Form\FormManager;
use App\Services\Image\ImageManager;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\User\NAOUserManager;
use App\Form\Capture\NaturalistCaptureType;
use App\Form\Capture\ValidateCaptureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CaptureController
 * @package App\Controller\Backend
 * @Route("/observations")
 */
class CaptureController extends Controller
{
    /**
     * @Route("/ajouter", name="add_capture")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOUserManager $naoUserManager
     * @param ValidatorInterface $validator
     * @param FormManager $formManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addCapture(Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOUserManager $naoUserManager, ValidatorInterface $validator, FormManager $formManager)
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
            $capture = $naoCaptureManager->setStatusOnCapture($capture, $user, $role);
            $listErrors = $validator->validate($capture);
            if (count($listErrors) > 0) {
                return $this->redirectToRoute('add_capture', array('list_errors' => (string)$listErrors));
            } else {
                $capture->setUser($user);
                $naoManager->addOrModifyEntity($capture);
                $this->addFlash('success', "Votre observation a été ajoutée avec succès !");
                return $this->redirectToRoute('add_image_on_capture', ['id' => $capture->getId()]);
            }
        }
        $title = 'Ajouter une observation';

        return $this->render(
            'capture\form_capture.html.twig',
            array(
                'form' => $form->createView(),
                'userRole' => $userRole,
                'role' => $role,
                'titre' => $title
            )
        );
    }

    /**
     * @Route("/valider/{id}", name="validate_capture", requirements={"id" = "\d+"})
     * @Security("has_role('ROLE_NATURALIST')")
     * @ParamConverter("capture", class="App\Entity\Capture")
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOUserManager $naoUserManager
     * @param Capture $capture
     * @param ValidatorInterface $validator
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function validateCapture(Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOUserManager $naoUserManager, Capture $capture, ValidatorInterface $validator, Session $session)
    {
        $form = $this->createForm(ValidateCaptureType::class, $capture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $listErrors = $validator->validate($capture);
            if(count($listErrors) > 0) {
                return $this->redirectToRoute('validate_capture', ['id' => $capture->getId(), 'list_errors' => (string)$listErrors]);
            } else {
                if ($form->get('validate')->isClicked()) {
                    $naturalist = $this->getUser();
                    $naoCaptureManager->validateCapture($capture, $naturalist);
                    $naoManager->addOrModifyEntity($capture);
                } elseif ($form->get('waiting_for_validation')->isClicked()) {
                    $naoManager->addOrModifyEntity($capture);
                } elseif ($form->get('remove')->isClicked()) {
                    $naoManager->removeEntity($capture);
                }

                return $this->redirectToRoute('admin_space');
            }
        }

        $user = $this->getUser();
        $userRole = $naoUserManager->getRoleFR($user);
        $role = $naoUserManager->getNaturalistOrParticularRole($user);
        $title = 'Valider une observation';
        $session->set('status', "validating_capture");

        return $this->render('capture\form_capture.html.twig',
            array
            (
                'form' => $form->createView(), 
                'userRole' => $userRole,
                'role' => $role,
                'titre' => $title,
                'capture' => $capture
            )
        );
    }

    /**
     * @Route("/modifier/{id}", name="modify_capture")
     * @Security("has_role('ROLE_NATURALIST')")
     * @ParamConverter("capture", class="App\Entity\Capture")
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOUserManager $naoUserManager
     * @param Capture $capture
     * @param ValidatorInterface $validator
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function modifyCapture(Request $request, NAOManager $naoManager, NAOUserManager $naoUserManager, Capture $capture, ValidatorInterface $validator, Session $session)
    {
        $form = $this->createForm(NaturalistCaptureType::class, $capture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $listErrors = $validator->validate($capture);
            if(count($listErrors) > 0) {
                return $this->redirectToRoute('modify_capture', ['id' => $capture->getId(), 'list_errors' => (string)$listErrors]);
            } else {
                $naoManager->addOrModifyEntity($capture);
                $this->addFlash('success', "L'observation a bien été modifiée !");
                return $this->redirectToRoute('user_account');
            }
        }

        $user = $this->getUser();
        $userRole = $naoUserManager->getRoleFR($user);
        $role = $naoUserManager->getNaturalistOrParticularRole($user);
        $title = 'Modifier une observation';
        $session->set('status', "modifying_capture");

        return $this->render('capture\form_capture.html.twig',
            array
            (
                'form' => $form->createView(),
                'userRole' => $userRole,
                'role' => $role,
                'capture' => $capture,
                'titre' => $title
            ));
    }

    /**
     * @Route(path="ajout-image/{id}", name="add_image_on_capture")
     * @ParamConverter("capture", class="App\Entity\Capture")
     * @param Request $request
     * @param Capture $capture
     * @param ImageManager $imageManager
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addImageOnCapture(Request $request, Capture $capture, ImageManager $imageManager, Session $session)
    {
        $form = $this->createForm(ImageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageManager->addImageOnCapture($form->getData()['image'], $capture);
            $this->addFlash('success', "Image ajoutée à l'observation !");
            if ($session->has('status') && $session->get('status') === "validating_capture")
                return $this->redirectToRoute('validate_capture', ['id' => $capture->getId()]);
            elseif ($session->has('status') && $session->get('status') === "modifying_capture")
                return $this->redirectToRoute('modify_capture', ['id' => $capture->getId()]);
            return $this->redirectToRoute('user_account');
        }
        return $this->render(
            'capture/add_image_on_capture.html.twig',
            array(
                'form' => $form->createView(),
                'capture' => $capture
            )
        );
    }

    /**
     * @Route(path="supprimer/{id}", name="delete_capture")
     * @ParamConverter("capture", class="App\Entity\Capture")
     * @param Capture $capture
     * @param NAOManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCapture(Capture $capture, NAOManager $manager)
    {
        if (!$capture) {
            throw $this->createNotFoundException("Observation non trouvée...");
        }
        $manager->removeEntity($capture);
        $this->addFlash('success',"Observation supprimée avec succès !");
        return $this->redirectToRoute('user_account');
    }

    /**
     * @Route(path="supprimer-image", name="delete_capture_image")
     * @param Request $request
     * @param ImageManager $imageManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCaptureImage(Request $request, ImageManager $imageManager)
    {
        if ($request->isMethod("POST")) {
            $capture = $this->getDoctrine()->getRepository(Capture::class)->find($request->get('capture'));
            $image = $this->getDoctrine()->getRepository(Image::class)->find($request->get('image'));
            if ($capture instanceof Capture && $image instanceof Image) {
                $imageManager->removeCaptureImage($capture, $image);
                $this->addFlash('success', "L'image de l'observation a été supprimée !");
            } else {
                $this->addFlash('error', "L'image n'a pas pu être supprimée...");
            }
            return $this->redirectToRoute('validate_capture', array('id' => $capture->getId()));
        } else {
            $this->addFlash('error', "Une erreur est survenue.");
            return $this->redirectToRoute('user_account');
        }
    }
}
