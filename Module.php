<?php

namespace d3yii2\d3btl;

use Yii;
use d3system\yii2\base\D3Module;

class Module extends D3Module
{
    public $controllerNamespace = 'd3yii2/d3btl\controllers';

    public function getLabel(): string
    {
        return Yii::t('d3btl','d3btl');
    }

}
