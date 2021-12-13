<?php

namespace d3yii2\d3btl\commands;

use d3yii2\d3btl\Module;
use yii\console\ExitCode;
use yii\console\Controller;
use d3yii2\d3btl\models\BtlFileData;
use d3yii2\d3btl\models\BtlPart;
use d3yii2\d3files\components\BTLFilePart;

/**
* Class ProcessFileController* @property Module $module
*/
class ProcessFileController extends Controller
{

    /**
     * @param string $filename
     * @param string $notes
     * @return int
     */
    public function actionAdd($filename, $notes = null): int
    {

        $fileText = file_get_contents($filename);

        $model = new BtlFileData();
        $model->load(['file_data' => $fileText], '');

        if ($model->saveWithParts()) {
            $this->stdout($filename . '  saved!');
        }


        return ExitCode::OK;
    }

}

