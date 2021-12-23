<?php

namespace d3yii2\d3btl\models;

use d3yii2\d3btl\models\base\BtlPart as BaseBtlPart;
use d3yii2\d3btl\models\BtlProcess;

/**
 * This is the model class for table "btl_part".
 */
class BtlPart extends BaseBtlPart
{


    /**
     * @return bool
     */
    public function saveWithProcess($processes)
    {

        if (!parent::save()) {
            return false;
        }


        foreach ($processes as $btlProcess) {
            $btlProcess->part_id = $this->id;
            $btlProcess->save();
        }

        return true;
    }

}
