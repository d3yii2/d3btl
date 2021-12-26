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
                    $parts[] = new BTLFilePart(
                        self::GENERAL,
                        $this->parseText($splitText[$key + 1]),
                        []
                    );

                    break;
                case self::PART:
                case self::RAWPART:
                    $parts[] = new BTLFilePart(
                        $chunk,
                        $this->parseText($splitText[$key + 1]),
                        $this->parseProcessText($splitText[$key + 1])
                    );
                    break;
            }

        }

        return $parts;
    }

    /**
     * @param string $text
     * @return array
     */
    public function parseText(string $text): array
    {
        $parsedText = [];

        $textChunks = explode(PHP_EOL, $text);

        foreach ($textChunks as $chunk) {
            $valuePair = explode(': ', $chunk);

            if (count($valuePair) === 2) {
                $parsedText[$valuePair[0]] = trim($valuePair[1],'"');
            }
        }

        return $parsedText;
    }

    /**
     * @param $text
     * @return array
     */
    public function parseProcessText($text): array
    {

        $textChunks = explode(PHP_EOL, $text);

        $processes = [];

        foreach ($textChunks as $line) {
            $valuePair = explode(': ', $line);

            if (count($valuePair) > 1) {

                switch ($valuePair[0]) {
                    case 'PROCESSKEY':
                        $btlProcess = new BtlProcess();
                        // need to be more careful with exploding, so no need to glue back together
                        $btlProcess->key = $valuePair[1];
                        break;
                    case 'PROCESSPARAMETERS':
                        $btlProcess->parameters = $valuePair[1];
                        break;
                    case 'PROCESSIDENT':
                        $btlProcess->ident = $valuePair[1];
                        break;
                    case 'PROCESSINGQUALITY':
                        $btlProcess->quality = strtolower($valuePair[1]);
                        break;
                    case 'RECESS':
                        $btlProcess->recess = strtolower($valuePair[1]);
                        break;
                    case 'COMMENT':
                        $btlProcess->comment = trim($valuePair[1], '"');
                        $processes[] = $btlProcess;
                        break;

                }

            }

        }


        return $processes;
    }
}