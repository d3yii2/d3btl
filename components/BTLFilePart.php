<?php

namespace d3yii2\d3btl\components;

use d3yii2\d3btl\models\BtlProcess;

class BTLFilePart
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $processes;

    /**
     * @var array
     */
    public $parsedText;

    /**
     * @var string
     */
    public $rawData;

    public function __construct($type, $rawData)
    {
        $this->type = $type;
        $this->rawData = $rawData;
    }


    /**
     * process part data
     * @return $this
     */
    public function parseText(): BTLFilePart
    {
        $parsedText = [];

        $textChunks = explode(PHP_EOL, $this->rawData);

        foreach ($textChunks as $chunk) {
            $valuePair = explode(': ', $chunk, 2);

            if (count($valuePair) > 1) {

                [, $value] = $valuePair;

                // going for 2d array
                if (str_contains($value, ': ')) {

                    [$key2, $value2] = explode(': ', $value, 2);
                    $parsedText[array_shift($valuePair)] = [$key2 => trim(trim($value2,"\" \n\r\t\v\0"))];
                } else {
                    $parsedText[array_shift($valuePair)] = trim(trim($valuePair[0], "\" \n\r\t\v\0"));
                }
            }
        }

        $this->parsedText = $parsedText;

        return $this;
    }

    /**
     * read process data
     * @return $this
     */
    public function parseProcessText(): BTLFilePart
    {

        $textChunks = explode(PHP_EOL, $this->rawData);

        $processes = [];
        $btlProcess = null;
        foreach ($textChunks as $line) {
            if (!$btlProcess) {
                $btlProcess = new BtlProcess();
            }
            $valuePair = explode(': ', $line, 2);

            if (count($valuePair) > 1) {

                [$key, $value] = $valuePair;
                $value = trim($value);
                switch ($key) {
                    case 'PROCESSKEY':
                        if (str_contains($value, ': ')) {
                            [$key2, $value2] = explode(': ', $value, 2);
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
                        $btlProcess->comment = trim($value, "\" \n\r\t\v\0");
                        $processes[] = $btlProcess;
                        $btlProcess = null;
                        break;

                }

            }

        }

        $this->processes = $processes;

        return $this;
    }


}