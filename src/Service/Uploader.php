<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * $filaname = $this->uploader->upload($file);
     */
    public function upload(UploadedFile $image)
    {
        //génére nom de l'image
        $fileName = uniqid() . '.' . $image->guessExtension();
        //déplace l'image
        $image->move($this->uploadDir, $fileName);

        return $fileName;
    }

    public function remove($fileName)
    {
        // Supprimer le ficher
        $fs = new Filesystem();
        $file = $this->uploadDir.'/'.$fileName;
        if ($fs->exists($file)){
            $fs->remove($file);
        }
    }
}