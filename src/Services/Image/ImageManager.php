<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 09/08/18
 * Time: 17:48
 */

namespace App\Services\Image;


use App\Entity\Capture;
use App\Entity\Image;
use App\Entity\User;
use App\Services\NAOManager;
use App\Services\User\NAOUserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageManager
{
    private $manager;

    private $user;

    private $container;

    private $fileUploader;

    public function __construct(NAOManager $manager, NAOUserManager $user, ContainerInterface $container, FileUploader $fileUploader)
    {
        $this->manager = $manager;
        $this->user = $user;
        $this->container = $container;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param string $username
     * @return bool
     * @throws \Exception
     */
    public function removeCurrentAvatar(string $username): bool
    {
        // get current avatar form database
        $user = $this->user->getCurrentUser($username);
        $current_image = $this->manager->getEm()->getRepository(Image::class)->findOneBy(['id' => $user->getAvatar()]);
        if ($current_image instanceof Image) {
            $current_image_filename = $current_image->getFilename();
            $current_avatar = $this->getContainer()->getParameter('avatar_directory').'/'.$current_image_filename;
            self::deleteFile($current_avatar);
            $user->setAvatar(null);
            $this->getManager()->addOrModifyEntity($user);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Capture $capture
     * @param Image $image
     * @return bool
     */
    public function removeCaptureImage(Capture $capture, Image $image): bool
    {
        $capture->removeImage();
        $current_image = $this->container->getParameter('bird_directory').'/'.$image->getFileName();
        self::deleteFile($current_image);
        $this->manager->removeEntity($image);
        $this->manager->addOrModifyEntity($capture);
        return true;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string $directory
     * @return Image
     */
    public function buildImage(UploadedFile $uploadedFile, string $directory): Image
    {
        $image = new Image();
        $image->setPath($directory);
        $image->setMimeType($uploadedFile->getMimeType());
        $image->setExtension($uploadedFile->guessExtension());
        $image->setSize($uploadedFile->getSize());
        return $image;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param Capture $capture
     */
    public function addImageOnCapture(UploadedFile $uploadedFile, Capture $capture)
    {
        $image = $this->buildImage($uploadedFile, $this->container->getParameter('bird_directory'));
        $file_name = $this->fileUploader->upload($uploadedFile, $this->container->getParameter('bird_directory'));
        $image->setFileName($file_name);
        $capture->setImage($image);
        $this->getManager()->addOrModifyEntity($capture);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param User $user
     */
    public function addAvatarOnUser(UploadedFile $uploadedFile, User $user)
    {
        $image = $this->buildImage($uploadedFile, $this->container->getParameter('avatar_directory'));
        $file_name = $this->fileUploader->upload($uploadedFile, $this->container->getParameter('avatar_directory'));
        $image->setFileName($file_name);
        $user->setAvatar($image);
        $this->getManager()->addOrModifyEntity($user);
    }

    /**
     * @param $file
     * @return bool
     */
    public function deleteFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return NAOManager
     */
    public function getManager(): NAOManager
    {
        return $this->manager;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
