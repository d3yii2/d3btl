<?php

namespace d3yii2\d3btl\components;

use d3yii2\d3btl\models\BtlProcess;

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
                $parts[] = new BTLFilePart(
                    self::GENERAL,
                    $this->parseText($splitText[$key + 1]),
                    []
                );
            }

            if ('[' . strtoupper(self::PART) . ']' === $chunk) {
                $parts[] = new BTLFilePart(
                    self::PART,
                    $this->parseText($splitText[$key + 1]),
                    $this->parseProcessText($splitText[$key + 1])
                );
            }

            if ('[' . strtoupper(self::RAWPART) . ']' === $chunk) {
                $parts[] = new BTLFilePart(
                    self::RAWPART,
                    $this->parseText($splitText[$key + 1]),
                    $this->parseProcessText($splitText[$key + 1])
                );
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

    /**
     * @param $text
     * @return array
     */
    public function parseProcessText($text)
    {

        $textChunks = explode(PHP_EOL, $text);

        $processes = [];

        foreach ($textChunks as $key => $line) {
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
                        $btlProcess->comment = $valuePair[1];
                        $processes[] = $btlProcess;
                        break;

                }

            }

        }


        return $processes;
    }
}