<?php

namespace d3yii2\d3btl\components;

class BTLFileParser
{
    // too specific for example file, need to handle PART loop with varied RAWPART
    public const REGEX = '#GENERAL](?s)(.*)\[RAWPART](?s)(.*)\[PART](.*)\[PART](.*)#';

    public const GENERAL = 'general';
    public const RAWPART = 'rawpart';
    public const PART = 'part';

    private $fileText;

    public function __construct($fileText)
    {
        $this->fileText = $fileText;
    }

    /**
     * @return array[]|null
     */
    public function getParts()
    {
        $matches = [];

        $result = preg_match(self::REGEX, $this->fileText, $matches);

        if (!$result) {
                return null;
        }

        return [ new BTLFilePart(self::GENERAL, $this->parseText($matches[1])),
             new BTLFilePart(self::RAWPART, $this->parseText($matches[2])),
             new BTLFilePart(self::PART, $this->parseText($matches[3])),
             new BTLFilePart(self::PART, $this->parseText($matches[4])),
        ];

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