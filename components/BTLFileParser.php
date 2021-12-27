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
            $valuePair = explode(': ', $chunk, 2);

            if (count($valuePair) > 1) {

                list($key, $value)  = $valuePair;

                // going for 2d array
                if (str_contains($value, ': ')) {

                    list($key2, $value2) = explode(': ', $value, 2);
                    $parsedText[array_shift($valuePair)] = [$key2 => $value2];
                } else {
                    $parsedText[array_shift($valuePair)] = $valuePair[0];
                }
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
            $valuePair = explode(': ', $line, 2);

            if (count($valuePair) > 1) {

                list($key, $value)  = $valuePair;
                switch ($key) {
                    case 'PROCESSKEY':
                        $btlProcess = new BtlProcess();

                        if (str_contains($value, ': ')) {
                            list($key2, $value2) = explode(': ', $value, 2);
                            $btlProcess->key = substr($key2, 0,20);
                            $btlProcess->designation = $value2;
                        } else {
                            $btlProcess->key = substr($value, 0,20);
                        }

                        break;
                    case 'PROCESSPARAMETERS':
                        $btlProcess->parameters = $value;
                        break;
                    case 'PROCESSIDENT':
                        $btlProcess->ident = $value;
                        break;
                    case 'PROCESSINGQUALITY':
                        $btlProcess->quality = strtolower($value);
                        break;
                    case 'RECESS':
                        $btlProcess->recess = strtolower($value);
                        break;
                    case 'COMMENT':
                        $btlProcess->comment = trim($value, '"');
                        $processes[] = $btlProcess;
                        break;

                }

            }

        }


        return $processes;
    }
}