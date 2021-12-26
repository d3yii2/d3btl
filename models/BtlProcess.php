<?php

namespace d3yii2\d3btl\models;

use d3yii2\d3btl\models\base\BtlProcess as BaseBtlProcess;

/**
 * This is the model class for table "btl_process".
 */
class BtlProcess extends BaseBtlProcess
{

    public function isLap(): bool
    {
        return $this->comment === 'Lap';
    }

    public function getLap(): ?ProcessLap4_030
    {
        if (!$this->isLap()) {
            return null;
        }

        $processLap = new ProcessLap4_030();
        $processLap->loadParameters($this->parameters);
        return $processLap;

    }
}
