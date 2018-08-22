<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 08/08/18
 * Time: 16:36
 */

namespace App\Controller\Backend;

use App\Entity\Image;
use App\Entity\User;
use App\Entity\Capture;
use App\Entity\Comment;
use App\Form\Image\AvatarType;
use App\Form\User\BiographyType;
use App\Form\Password\ChangePasswordType;
use App\Services\Image\FileUploader;
use App\Services\Image\ImageManager;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Capture\NAOCountCaptures;
use App\Services\Pagination\NAOPagination;
use App\Services\User\NAOUserManager;
use App\Services\User\PasswordManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AccountController
 * @package App\Controller\Backend
 * @Route("/mon-compte")
 */
class AccountController extends Controller
{
    /**
     * @Route("/{page}", defaults={"page" = 1}, name="user_account")
     * @param $page
     * @param NAOPagination $naoPagination
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param Request $request
     * @param NAOUserManager $userManager
     * @param PasswordManager $passwordManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function showUserAccount($page, NAOPagination $naoPagination, NAOCaptureManager $naoCaptureManager, NAOCountCaptures $naoCountCaptures, Request $request, NAOUserManager $userManager, PasswordManager $passwordManager)
    {
        $user = $this->getUser();
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfUserCaptures = $naoCountCaptures->countUserCaptures($user);
        $numberOfUserCapturesPages = $naoPagination->CountNbPages($numberOfUserCaptures, $numberOfElementsPerPage);
        $captures = $naoCaptureManager->getUserCapturesPerPage($page, $numberOfUserCaptures, $numberOfElementsPerPage, $user);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);
        $biographyType = $this->createForm(BiographyType::class);
        $user = $this->getDoctrine()->getRepository(User::class)->findUserByEmail($this->getUser()->getEmail());
        $biographyType->handleRequest($request);
        $account_type = $userManager->getRoleFR($user);
        if ($biographyType->isSubmitted() && $biographyType->isValid()) {
            $userManager->changeBiography($user, $biographyType->getData()['biography']);
            return $this->redirectToRoute('compteUtilisateur');
        }
        $changePasswordType = $this->createForm(ChangePasswordType::class);
        $changePasswordType->handleRequest($request);
        if ($changePasswordType->isSubmitted() && $changePasswordType->isValid()) {
            $passwordManager->changePassword($user, $changePasswordType->getData()['new_password']);
            return $this->redirectToRoute('compteUtilisateur');
        }
        return $this->render(
            'account/account.html.twig',
            array(
                'captures' => $captures,
                'account_type' => $account_type,
                'biography_form' => $biographyType->createView(),
                'change_password_form' => $changePasswordType->createView(),
                'page' => $page,
                'nbCapturesPages' => $numberOfUserCapturesPages,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage
            )
        );
    }

    /**
     * @Route("/change-avatar", name="change_avatar")
     * @param Request $request
     * @param ImageManager $imageManager
     * @param NAOUserManager $userManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function changeAvatar(Request $request, ImageManager $imageManager, NAOUserManager $userManager)
    {
        $current_user = $this->getUser();
        $user = $userManager->getCurrentUser($current_user->getUsername());
        $avatar_form = $this->createForm(AvatarType::class);
        $avatar_form->handleRequest($request);
        if ($avatar_form->isSubmitted() && $avatar_form->isValid()) {
            if ($user->getAvatar() !== null) {
                $imageManager->removeCurrentAvatar($this->getUser()->getUsername());
            }
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $avatar_form->getData()['avatar'];
            $imageManager->addAvatarOnUser($uploadedFile, $user);
            $this->addFlash('success', "Votre avatar a bien été changé !");
            return $this->redirectToRoute('account');
        }
        return $this->render(
            'account/change_avatar.html.twig',
            array(
                'form' => $avatar_form->createView()
            )
        );
    }

    /**
     * @Route(path="/upgrade/{username}/{role}", name="upgrade")
     * @ParamConverter("user", class="App\Entity\User")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function upgrade(User $user, $role, NAOManager $manager)
    {
        $user->addRole($role);
        $manager->addOrModifyEntity($user);
        return $this->redirectToRoute('compteUtilisateur');
    }
}
