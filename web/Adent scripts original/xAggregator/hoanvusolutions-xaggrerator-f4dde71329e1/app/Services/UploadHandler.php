<?php

namespace App\Services;

use stdClass;
use Image;
use URL;

class UploadHandler {
  const UPLOAD_URL = '/uploads/';
  public static function uploadImageBase64File($base64File, $fileName, $folderName) {
    if (strpos($base64File, 'image') !== false) {
      $base64File = substr($base64File, strpos($base64File, ",") + 1);
      $decodedFile = base64_decode($base64File);
      if (strlen($decodedFile) < 3000000) {
        $imgName = uniqid() . '-' . self::cleanFileName($fileName);
        file_put_contents(dirname($_SERVER['SCRIPT_FILENAME']) . '/public/upload/' . $folderName . '/' . $imgName, $decodedFile); //create an image
        return array(
            'isSuccess' => true,
            'data' => $imgName
        );
      } else {
        return array(
            'isSuccess' => false,
            'data' => 'Please upload a file size < 3MB'
        );
      }
    } else {
      return array(
          'isSuccess' => false,
          'data' => 'Please upload a valid image file'
      );
    }
  }

  public static function cleanFileName($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9.\-]/', '', $string); // Removes special chars.
  }

  public static function removeImage($folderName, $fileName) {
    $filePath = dirname($_SERVER['SCRIPT_FILENAME']) . '/public/upload/' . $folderName . '/' . $fileName;
    $file = pathinfo($fileName);
    $thumbFolder = dirname($_SERVER['SCRIPT_FILENAME']) . '/public/upload/' . $folderName . '/thumbnails/' . $file['filename'] . '/';

    if (file_exists($filePath)) {
      unlink($filePath);

      if (is_dir($thumbFolder)) {
        $files = array_diff(scandir($thumbFolder), array('.', '..'));
        foreach ($files as $file) {
          $filePath = $thumbFolder . $file;
          if (file_exists($filePath)) {
            unlink($filePath);
          }
        }
        return rmdir($thumbFolder);
      }
    }
  }

  public static function uploadImage($uploadFile, $folderName) {
    $fileInfo = pathinfo($uploadFile['name']);
    $validExt = ['png', 'jpg', 'jpeg'];
    if (array_search($fileInfo['extension'], $validExt) !== false) {
      if ($uploadFile['size'] <= 3000000) {
          $fileName = self::cleanFileName($fileInfo['filename']) . '-' . date('d-m-Y') . '.' . $fileInfo['extension'];
          if(strtolower($fileInfo['filename']) == 'favicon'){
              $fileName = $uploadFile['name'];
          }
        if (move_uploaded_file($uploadFile['tmp_name'], self::getUploadPath() . $folderName . '/' . $fileName)) {
          return array(
              'isSuccess' => true,
              'data' => $fileName
          );
        }
      } else {
        return array(
            'isSuccess' => false,
            'data' => 'Please upload a file size < 3MB'
        );
      }
    } else {
      return array(
          'isSuccess' => false,
          'data' => 'Please upload a valid image file'
      );
    }
  }

  public static function getThumbnailUrl($fileName, $folder, $width = 0, $height = 0) {
    $uploadPath = dirname($_SERVER['SCRIPT_FILENAME']) . '/public/upload/';
    $uploadUrl = URL::asset('public/upload') . '/';
    if ($fileName) {
      if ($width && $height) {
        $file = pathinfo($fileName);
        $thumbPath = $uploadPath . $folder . '/thumbnails/' . $file['filename'] . '/';
        if (file_exists($uploadPath . $folder . '/' . $fileName)) {
          //use file name to create new folder for thumbnail
          if (!is_dir($thumbPath)) {
            mkdir($thumbPath, 0777);
            chmod($thumbPath, 0777);
          }
          //create new thumbnail
          $thumbnail = $file['filename'] . '_' . $width . '_' . $height . '.' . $file['extension'];
          if (!file_exists($thumbPath . $thumbnail)) {
            $img = Image::make($uploadPath . $folder . '/' . $fileName);
            $img->resize($width, $height);
            $img->save($thumbPath . $thumbnail);
          }
          return $uploadUrl . $folder . '/thumbnails/' . $file['filename'] . '/' . $thumbnail;
        } else {
          return URL::asset('public/frontend/images/no-image.png');
        }
      } else {
        return $uploadUrl . $folder . '/' . $fileName;
      }
    } else {
      return URL::asset('public/frontend/images/no-image.png');
    }
  }

  public static function removeFolder($path) {
    if (is_dir($path)) {
      $files = array_diff(scandir($path), array('.', '..'));
      foreach ($files as $file) {
        $filePath = $path . $file;
        if (file_exists($filePath)) {
          unlink($filePath);
        }
      }
      return rmdir($path);
    }
  }

  public static function uploadFile($uploadFile, $folderName) {
    $fileName = str_replace('.csv', '_' . Date('dmyHis') . '.csv', self::cleanFileName($uploadFile['name']));
    if (move_uploaded_file($uploadFile['tmp_name'], self::getUploadPath() . $folderName . '/' . $fileName)) {
      return array(
          'isSuccess' => true,
          'data' => $fileName
      );
    }
  }

  public static function getUploadPath() {
    return dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/';
  }
  
  

}
