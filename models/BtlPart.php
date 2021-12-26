<?php

namespace d3yii2\d3btl\models;

use d3system\exceptions\D3ActiveRecordException;
use d3yii2\d3btl\models\base\BtlPart as BaseBtlPart;

/**
 * This is the model class for table "btl_part".
 */
class BtlPart extends BaseBtlPart
{

    /**
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

}
