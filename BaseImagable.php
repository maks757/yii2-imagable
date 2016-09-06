<?php

namespace bl\imagable;

use bl\imagable\helpers\DirectoryHelper;
use yii\base\Component;

/**
 * Description of Imagable
 *
 * @method create($category, $path)
 * @method createMultiply(array $categories, $path)
 *
 * @method get($category, $type, $name, &$imageContent = null)
 * @method getThumb($category, $name, &$imageContent = null)
 * @method getSmall($category, $name, &$imageContent = null)
 * @method getBig($category, $name, &$imageContent = null)
 * @method getOriginal($category, $name, &$imageContent = null)
 * @method delete($category, $name, &$imageContent = null)
 *
 * @author Ruslan Saiko <ruslan.saiko.dev@gmail.com>
 */
class BaseImagable extends Component
{
    public $imageClass;
    public $categories;
    public $imagesPath = '@webroot/images';
    public $baseTemplate = [];
    public $nameClass = 'bl\imagable\name\OriginName';

    public function behaviors()
    {
        return [
            'getImage' => [
                'class' => 'bl\imagable\behaviors\GetImageBehavior'
            ],
            'createImage' => [
                'class' => 'bl\imagable\behaviors\CreateImageBehavior'
            ],
            'deleteImage' => [
                'class' => 'bl\imagable\behaviors\DeleteImageBehavior'
            ]
        ];
    }

    public function init()
    {
        DirectoryHelper::create(\Yii::getAlias($this->imagesPath), true);
        $this->imagesPath = \Yii::getAlias($this->imagesPath);
        $this->registerDependencies();
        $this->initBaseTemplate();
        return parent::init();
    }

    private function initBaseTemplate()
    {
        $this->baseTemplate = [
            'big' => [
                'width' => 500,
                'height' => 500
            ],
            'small' => [
                'width' => 250,
                'height' => 250
            ],
            'thumb' => [
                'width' => 100,
                'height' => 100
            ],
        ];
    }

    private function registerDependencies()
    {
        \Yii::$container->set('bl\imagable\name\BaseName',
            $this->nameClass);

        \Yii::$container->set('name', 'bl\imagable\name\BaseName');

        \Yii::$container->set('bl\imagable\interfaces\CreateImageInterface',
            $this->imageClass);
    }

    public function getTemplateSizeByCategory($template, $category)
    {
        if (key_exists($category, $this->categories)) {
            return $this->categories[$category][$template];
        } elseif (key_exists($category, $this->baseTemplate)) {
            return $this->baseTemplate[$category][$template];
        } else {
            throw new \Exception("Category with name '$category' does not exist");
        }
    }
}
