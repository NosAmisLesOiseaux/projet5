<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\Image;
use App\Entity\User;
use App\Form\Capture\CaptureImageType;
use App\Services\ImageManager;
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
     * @Route("/ajouter-observation", name="ajouterObservation")
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOUserManager $naoUserManager
     * @return Response
     */
    public function addCaptureAction(Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOUserManager $naoUserManager, ValidatorInterface $validator)
    {
        $capture = new Capture();
        $current_user = $this->getUser();
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
            array(
                'email' => $current_user->getEmail()
            )
        );
        $userRole = $naoUserManager->getRoleFR($user);
        $role = $naoUserManager->getNaturalistOrParticularRole($user);
        /** Optimiser cette partie */
        if ($userRole === 'particulier')
        {
            $form = $this->createForm(ParticularCaptureType::class, $capture);
        }
        elseif ($userRole === 'naturaliste' || $userRole === 'administrateur')
        {
            $form = $this->createForm(NaturalistCaptureType::class, $capture);
        }
        $form->add('Enregistrer',      SubmitType::class);
        /**  */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Capture $capture */
            $capture = $naoCaptureManager->setStatusOnCapture($form->getData(), $user);
            $capture->setUser($user);
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
     * @ParamConverter("capture", class="App\Entity\Capture")
     * @param Request $request
     * @param Capture $capture
     * @param NAOManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function addImageOnCapture(Request $request, Capture $capture, NAOManager $manager, ImageManager $imageManager)
    {
        $form = $this->createForm(CaptureImageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            $uploadedFile = $form->getData()['image'];
            $image = $imageManager->buildImage($uploadedFile, $this->getParameter('bird_directory'));
            $capture->setImage($image);
            $manager->addOrModifyEntity($capture);
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
     * Optimiser les paramètres
     * @Route("/valider-observation/{id}", name="validerObservation", requirements={"id" = "\d+"})
     * @param Request $request
     * @param Capture $capture
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOUserManager $naoUserManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function validateCaptureAction(Request $request, Capture $capture, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOUserManager $naoUserManager, $id, ValidatorInterface $validator)
    {
        $form_image = $this->createForm(CaptureImageType::class);
        $form = $this->createForm(ValidateCaptureType::class, $capture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $list_errors = $validator->validate($capture);
            if (count($list_errors) > 0) {
                return $this->redirectToRoute('validerObservation', array('list_errors' => $list_errors));
            }
            if ($form->get('validate')->isClicked()) {
                $current_user = $this->getUser();
                /** @var User $user */
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                    array(
                        'email' => $current_user->getEmail()
                    )
                );
                $naoCaptureManager->validateCapture($capture, $user);
                $naoManager->addOrModifyEntity($capture);
                $this->addFlash('success', "Observation validée !");
            } elseif ($form->get('waitingForValidation')->isClicked()) {
                $capture->setStatus('waiting_for_validation');
                $naoManager->addOrModifyEntity($capture);
                $this->addFlash('success', "Observation en attente de validation !");
            } elseif ($form->get('remove')->isClicked()) {
                $naoManager->removeEntity($capture);
                $this->addFlash('success', "Observation supprimée !");
            } else {
                return $this->redirectToRoute('compteUtilisateur');
            }
            return $this->redirectToRoute('espaceAdministration');
        }
        $user = $this->getUser();
        $userRole = $naoUserManager->getRoleFR($user);
        $role = $naoUserManager->getNaturalistOrParticularRole($user);
        $title = "Valider une observation";

        return $this->render(
            'Capture\validate_capture.html.twig',
            array(
                'form' => $form->createView(),
                'form_image' => $form_image->createView(),
                'capture' => $capture,
                'title' => $title,
                'userRole' => $userRole,
                'role' => $role
            )
        );
    }

    /**
     * Tester cette route
     * @Route(path="delete-capture-image", name="delete_capture_image")
     * @param Request $request
     * @param NAOManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCaptureImage(Request $request, NAOManager $manager, ImageManager $imageManager)
    {
        if ($request->isMethod("POST")) {
            $capture = $this->getDoctrine()->getRepository(Capture::class)->find($request->get('capture'));
            $image = $this->getDoctrine()->getRepository(Image::class)->find($capture->getImage());
            $imageManager->removeCaptureImage($capture, $image, $manager);
            $this->addFlash('success', "L'image de l'observation a été supprimée !");
            return $this->redirectToRoute('validerObservation', array('id' => $capture->getId()));
        }
    }

    /**
     * @Route("/modifier-observation/{capture}", name="modifierObservation")
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOUserManager $naoUserManager
     * @param Capture $capture
     * @return Response
     */
    public function modifyCaptureAction(Request $request, NAOManager $naoManager, NAOUserManager $naoUserManager, Capture $capture, ValidatorInterface $validator)
    {
        $form = $this->createForm(NaturalistCaptureType::class, $capture);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $listErrors = $validator->validate($capture);
            if(count($listErrors) > 0)
            {
                return $this->redirectToRoute('modifierObservation', array('list_errors' => (string)$listErrors));
            }
            else
            {
                $naoManager->addOrModifyEntity($capture);
                $this->addFlash('success', "L'observation a été modifiée !");
                return $this->redirectToRoute('modifierObservation', array('capture' => $capture->getId()));
            }
        }
        $user = $this->getUser();
        $userRole = $naoUserManager->getRoleFR($user);
        $role = $naoUserManager->getNaturalistOrParticularRole($user);
        $title = 'Modifier une observation';
        return $this->render(
            'Capture\addModifyOrValidateCapture.html.twig',
            array(
                'form' => $form->createView(),
                'capture' => $capture,
                'userRole' => $userRole,
                'role' => $role,
                'title' => $title
            )
        );
    }
}
