<?php

namespace d3yii2\d3btl\components;

class BTLFileParser
{
    public const REGEX = '#(\[RAWPART])|(\[PART\])|(\[GENERAL])#';

    public const GENERAL = 'general';
    public const RAWPART = 'rawpart';
    public const PART = 'part';

    private $fileText;

    public function __construct($fileText)
    {
        $this->fileText = $fileText;
    }

    /**
     * @return array[]
     */
    public function getParts()
    {
        $parts = [];
        $splitText = preg_split(self::REGEX, $this->fileText, 0,PREG_SPLIT_DELIM_CAPTURE );

        foreach ($splitText as $key => $chunk) {
            if ('[' . strtoupper(self::GENERAL) . ']' === $chunk) {
                $parts[] = new BTLFilePart(self::GENERAL, $this->parseText($splitText[$key + 1]));
            }

            if ('[' . strtoupper(self::PART) . ']' === $chunk) {
                $parts[] = new BTLFilePart(self::PART, $this->parseText($splitText[$key + 1]));
            }

            if ('[' . strtoupper(self::RAWPART) . ']' === $chunk) {
                $parts[] = new BTLFilePart(self::RAWPART, $this->parseText($splitText[$key + 1]));
            }
        }

        return $parts;
    }

    /**
     * @param string $text
     * @return array
     */
    public function parseText($text)
    {
        $parsedText = [];

        $textChunks = explode(PHP_EOL, $text);

        foreach ($textChunks as $chunk) {
            $valuePair = explode(': ', $chunk);

            if (count($valuePair) === 2) {
                $parsedText[$valuePair[0]] = $valuePair[1];
            }
        }

        return $parsedText;
    }

}