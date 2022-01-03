<?php

namespace d3yii2\d3btl\components;

use d3yii2\d3btl\models\BtlProcess;

class BTLFileParser
{
    public const GENERAL = 'GENERAL';
    public const RAWPART = 'RAWPART';
    public const PART = 'PART';

    public const REGEX = '#(\['.self::GENERAL.'])|(\['.self::RAWPART.'\])|(\['.self::PART.'])#';



    private $fileText;

    public function __construct($fileText)
    {
        $this->fileText = $fileText;
    }

    /**
     * get file parts GENERAL, RAWPART and PART
     * @return BTLFilePart[]
     */
    public function getParts(): array
    {
        $parts = [];
        $splitText = preg_split(self::REGEX, $this->fileText, 0,PREG_SPLIT_DELIM_CAPTURE );

        foreach ($splitText as $key => $chunk) {
            $chunk = trim($chunk,'[]');
            switch ($chunk) {
                case self::GENERAL:
                    $part = new BTLFilePart(
                        self::GENERAL,
                        $splitText[$key + 1]
                    );
                    $parts[] = $part->parseText();
                    break;
                case self::PART:
                case self::RAWPART:
                    $part = new BTLFilePart(
                        $chunk,
                        $splitText[$key + 1]
                    );
                $parts[] = $part->parseText()->parseProcessText();
                break;
            }

        }

        return $parts;
    }

}