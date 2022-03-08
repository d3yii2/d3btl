<?php

namespace d3yii2\d3btl\models;

use d3system\exceptions\D3ActiveRecordException;
use d3yii2\d3btl\components\BTLFilePart;
use d3yii2\d3btl\models\base\BtlPart as BaseBtlPart;

/**
 * This is the model class for table "btl_part".
 */
class BtlPart extends BaseBtlPart
{
    /**
     * @var array
     */
    private $_parsedData;

    /**
     * @param $processes
     * @return void
     * @throws \d3system\exceptions\D3ActiveRecordException
     */
    public function saveWithProcess($processes): void
    {
        foreach ($processes as $btlProcess) {
            $btlProcess->part_id = $this->id;
            if (!$btlProcess->save()){
                throw new D3ActiveRecordException($btlProcess);
            }
        }
    }

    public function getLength(): float
    {
        return round($this->length/100);
    }

    public function getHeight(): float
    {
        return round($this->height/100);
    }

    public function getWidth(): float
    {
        return round($this->width/100);
    }

    public function getSize(): string
    {
        return $this->getHeight()
            . ' X ' . $this->getLength()
            . ' X ' . $this->getWidth();
    }

    public function delete()
    {
        foreach ($this->btlProcesses as $process) {
            $process->delete();
        }
        return parent::delete();
    }

    public function getUserAttribute(string $name)
    {
        return $this->getUserAttributes()[$name]??null;
    }

    public function getUserAttributes(): array
    {
        $data = BTLFilePart::parseText($this->raw_data)['USERATTRIBUTE']??[];
        foreach ($data as $name => $value) {
            unset($data[$name]);
            $data[trim($name,'"')] = $value;
        }
        return $data;
    }

    public function getParsedData(): array
    {
        if ($this->_parsedData) {
            return $this->_parsedData;
        }
        return $this->_parsedData = BTLFilePart::parseText($this->raw_data);
    }

    public function getPartAttribute(string $name): ?string
    {
        return $this->getParsedData()[$name]??null;
    }

    public function getUserAttrMasters()
    {
        return $this->getUserAttribute('Masters');
    }

    /**
     * @return array [0=>'mastaer spec', 1=>height,2=> C/L, 4=>layersCnt, 5=> V/N, 5 => V/N]
     */
    public function getUserAttrMastersDetails(): array
    {
        if (!preg_match(
            '#^CLT_(\d+)_([CL])(\d+)_([VN])\/([VN])$#',
            $this->getUserAttribute('Masters'),
            $match
        )) {
            return [];
        }
        return $match;
    }

    public function getCL()
    {
        return $this->getUserAttrMastersDetails()[2]??null;
    }
}
