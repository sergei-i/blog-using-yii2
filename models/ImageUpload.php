<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg,jpeg,png']
        ];
    }

    public function uploadFile(UploadedFile $file, $currentImage)
    {
        $this->image = $file;

        if ($this->validate()) {

            $this->deleteCurrentImage($currentImage);
            return $this->saveImage();
        }
    }

    private function getFolder()
    {
        return \Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFileName()
    {
        return strtolower(md5(uniqId($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($currentImage)
    {
        $currentImageLink = $this->getFolder() . $currentImage;

        if ($this->fileExists($currentImageLink)) {
            unlink($currentImageLink);
        }
    }

    public function fileExists($currentFileLink)
    {
        if (!empty($currentFileLink) && $currentFileLink != null) {
            return is_file($currentFileLink) && file_exists($currentFileLink);
        }

    }

    public function saveImage()
    {
        $filename = $this->generateFileName();

        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }
}