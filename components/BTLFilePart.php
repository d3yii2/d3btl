<?php

namespace d3yii2\d3btl\components;

class BTLFilePart
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $parsedText;

    public function __construct($type, $parsedText)
    {
        $this->type = $type;
        $this->parsedText = $parsedText;
    }
}