<?php

namespace maks757\imagable\instances;

use Codeception\Util\Debug;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Imagine\Imagick\Imagine;
use maks757\imagable\helpers\FileHelper;
use maks757\imagable\interfaces\CreateImageInterface;
use yii\base\Object;
use yii\imagine\Image;

/**
 * Description of CreateImageImagine
 * @author Ruslan Saiko <ruslan.saiko.dev@gmail.com>
 */
class CreateImageImagine extends Object implements CreateImageInterface
{
    /**
     * @var Imagine
     */
    private $imagine;

    /**
     * @var ManipulatorInterface
     */
    private $openImage = null;

    public function init()
    {
        $this->imagine = new Imagine();
        return parent::init();
    }

    public function save($saveTo)
    {
        $this->openImage->save($saveTo);
    }

    public function thumbnail($pathToImage, $width, $height)
    {
        try {
            $this->openImage = $this->imagine->open(FileHelper::normalizePath($pathToImage))->thumbnail(new Box($width,
                $height));
        } catch (\Exception $ex) {
            Debug::debug($ex->getMessage());
        }
    }
}
