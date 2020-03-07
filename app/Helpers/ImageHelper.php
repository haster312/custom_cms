<?php

namespace App\Helpers;


use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class ImageHelper
{
    protected $uploadFolder = "uploads/media/";
    public $originalDestination;
    protected $thumbDestination;
    public $manager;
    private $maxSize;
    protected $allowMime = [
        'gif', 'png', 'jpg', 'jpeg'
    ];

    public function __construct()
    {
        $this->originalDestination = $this->uploadFolder . date('Ym');
        $this->thumbDestination = $this->originalDestination . "/thumb";
        $this->maxSize = config('const.Image.MaxSize');
    }

    public function getDataBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }

    public function getImage($file, $rotate)
    {
        $mimeType = $file->getMimeType();
        $original = $this->uploadImage($file);
        $extension = $file->getClientOriginalExtension();
        $this->rotateImage($this->originalDestination . '/' . $original, $rotate, $extension);

        if ($original) {
            //Resize large image
            $fileName = $this->originalDestination . '/' . $original;

            $thumbnail = $this->generateThumbnail($original);

            return [
                'main' => $fileName,
                'thumbnail' => $thumbnail,
                'mime' => $mimeType
            ];
        }

        return null;
    }

    /**
     * Get dimension of main, thumbnail image
     * @param $photo
     * @return array
     */
    public function getDimension($photo)
    {
        list($mainWidth, $mainHeight) = getimagesize($photo['main']);
        list($thumbWidth, $thumbHeight) = getimagesize($photo['thumbnail']);

        return [
            'main_width' => $mainWidth,
            'main_height' => $mainHeight,
            'thumbnail_width' => $thumbWidth,
            'thumbnail_height' => $thumbHeight,
        ];
    }

    /**
     * Upload original image
     * @param $file
     * @return bool|array
     */
    public function uploadImage($file)
    {
        $oldMask = umask();
        umask(0);
        if (!is_dir('./' . $this->originalDestination)) {
            mkdir('./' . $this->originalDestination, 0777, true);
        }

        if (!is_dir('./' . $this->thumbDestination)) {
            mkdir('./' . $this->thumbDestination, 0777, true);
        }
        umask($oldMask);

        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, $this->allowMime)) {
            return false;
        }

        $fileName = rand(111, 999999999) . '.' . $extension;

        while (file_exists("./" . $this->originalDestination . '/' . $fileName)) {
            $fileName = rand(111, 999999999) . '.' . $extension;
        }

        $file->move($this->originalDestination, $fileName);

        // Add image watermark
        $this->addWatermark($this->originalDestination . '/' . $fileName, $extension);

        return $fileName;
    }


    public function rotateImage($imageUrl, $rotate, $extension)
    {
        $degrees = $rotate;
        $rotateFilename = public_path() . '/' . $imageUrl;

        if ($degrees != 0) {
            if ($extension == 'png' || $extension == 'PNG') {
                header('Content-type: image/png');
                $source = imagecreatefrompng($rotateFilename);
                // Rotate
                $rotate = imagerotate($source, $degrees, 0);
                imagesavealpha($rotate, true);
                imagepng($rotate, $rotateFilename);
                // Free the memory
                imagedestroy($source);
                imagedestroy($rotate);
            }

            if ($extension == 'jpg' || $extension == 'jpeg') {
                header('Content-type: image/jpeg');
                $source = imagecreatefromjpeg($rotateFilename);
                // Rotate
                $rotate = imagerotate($source, $degrees, 0);
                imagejpeg($rotate, $rotateFilename);
                // Free the memory
                imagedestroy($source);
                imagedestroy($rotate);
            }
        }

        return $rotateFilename;
    }

    /**
     * Generate thumbnail from file path
     * @param $fileName
     * @return bool|string
     */
    public function generateThumbnail($fileName)
    {
        // Set a maximum height and width
        $width = 500;
        $height = 500;

        // open an image file
        if (!$fileName) {
            return false;
        }

        list($originalWidth, $originalHeight) = getimagesize("./" . $this->originalDestination . "/" . $fileName);

        $ratio = $originalWidth / $originalHeight;

        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        //generate new thumbnail
        $image = \Image::make($this->originalDestination . "/" . $fileName);

        $thumbName = $this->thumbDestination . "/thumb_" . $fileName;

        // now you are able to resize the instance
        $image->resize($width, $height);

        $image->save($thumbName, 100);

        return $thumbName;
    }

    public function addWatermark($filePath, $extension)
    {
        // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefrompng(public_path('watermark.png'));

        switch ($extension) {
            case 'gif':
                $image = imagecreatefromgif($filePath);
                $contentType = "image/gif";
                break;
            case 'png':
                $image = imagecreatefrompng($filePath);
                $contentType = "image/png";
                break;
            case 'jpg':
            case 'jpeg':
            default:
                $image = imagecreatefromjpeg($filePath);
                $contentType = "image/jpeg";
                break;
        };

        // Set the margins for the stamp and get the height/width of the stamp image
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        $stampWidth = imagesx($stamp);
        $stampHeight = imagesy($stamp);
        $xLocation = (int)($imageWidth - $stampWidth) / 2;
        $yLocation = (int)($imageHeight - $stampHeight) / 2;

        // Copy the stamp image onto our photo using the margin offsets and the photo
        // width to calculate positioning of the stamp.
        imagecopy($image, $stamp,
            $xLocation,
            $yLocation,
            0, 0,
            $stampWidth, $stampHeight);

        header("Content-Type: $contentType");
        switch ($extension) {
            case 'gif':
                imagegif($image, $filePath);
                break;
            case 'png':
                imagepng($image, $filePath);
                break;
            case 'jpg':
            case 'jpeg':
            default:
                imagejpeg($image, $filePath);
                break;
        };

        imagedestroy($image);
        header("Content-Type: text/html");
    }
}
