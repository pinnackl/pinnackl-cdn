<?php

namespace Pinnackl\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * 
 */
class Cdn
{
    public function uploadAction(\Silex\Application $app, Request $request)
    {
        $success = array();
        $error = array();
        $imagePath = realpath(__DIR__.'/../../../web/images');
        $target_dir = $imagePath . "/";
        $target_file = $target_dir . basename($request->files->get('cover')->getClientOriginalName());
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        $check = $request->files->get('cover')->getSize();
        if($check !== false) {
            // echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $error[] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $error[] = "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($request->files->get('cover')->getSize() > 500000) {
            $error[] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $error[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an erroror
        if ($uploadOk == 0) {
            $error[] = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            $newName = uniqid(). "." . $imageFileType;
            if (move_uploaded_file($request->files->get('cover')->getpathName(), $target_dir . $newName)) {
                $success[] = "The file ". basename($newName). " has been uploaded.";
            } else {
                $error[] = "Sorry, there was an error uploading your file.";
            }
        }

        $data = array(
            "name" => $newName,
            "success" => $success,
            "error" => $error,
        );
        return $app->json($data, 200);
    }
}