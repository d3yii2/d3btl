<?php

namespace d3yii2\d3btl\models;

use d3system\exceptions\D3ActiveRecordException;
use d3yii2\d3btl\models\base\BtlPart as BaseBtlPart;
use yii\helpers\Json;

/**
 * This is the model class for table "btl_part".
 */
class BtlPart extends BaseBtlPart
{

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

    public function getPartDataFieldValue(string $field)
    {
        $data = Json::decode($this->parsed_data);
        return $data[$field] ?? null;
    }

    public function getLength()
    {
        return $this->getPartDataFieldValue('LENGTH')/100;
    }

    public function getHeight()
    {
        return $this->getPartDataFieldValue('HEIGHT')/100;
    }

    public function getWidth()
    {
        return $this->getPartDataFieldValue('WIDTH')/100;
    }

    public function getSize(): string
    {
        return $this->getLength()
            . ' X ' . $this->getHeight()
            . ' X ' . $this->getWidth();
    }

    public function delete()
    {
        foreach ($this->btlProcesses as $process) {
            $process->delete();
        }
        return parent::delete();
    }
}
