<?php

namespace d3yii2\d3btl\components;

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
            if (!$rawData = $splitText[$key + 1]??false) {
                continue;
            }
            switch ($chunk) {
                case self::GENERAL:
                    $part = new BTLFilePart(
                        self::GENERAL,
                        $rawData
                    );
                    $parts[] = $part->parseProcessText();
                    break;
                case self::PART:
                case self::RAWPART:
                    $part = new BTLFilePart(
                        $chunk,
                        $rawData
                    );
                    $parts[] = $part->parseProcessText();
                    break;
            }
        }

        return $parts;
    }

}