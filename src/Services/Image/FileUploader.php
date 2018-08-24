<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 21:05
 */

namespace App\Services\Image;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @param UploadedFile $file
     * @param $targetDirectory
     * @return string
     */
    public function upload(UploadedFile $file, $targetDirectory): string
    {
        $file_name = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $targetDirectory,
            $file_name
        );
        return $file_name;
    }
}
