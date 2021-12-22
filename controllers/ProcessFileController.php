<?php

namespace d3yii2\d3btl\controllers;

use d3system\commands\D3CommandController;
use d3yii2\d3btl\Module;
use Exception;
use yii\console\ExitCode;
use d3yii2\d3btl\models\BtlFileData;
use Yii;

/**
* Class ProcessFileController* @property Module $module
*/
class ProcessFileController extends D3CommandController
{

    /**
     *
     * @param string $filename
     * @param string $notes
     * @return int
     */
    public function actionAdd($filename, $notes = null): int
    {

        $fileText = file_get_contents($filename);

        $model = new BtlFileData();
        $model->load(['file_data' => $fileText, 'file_name' => $filename], '');

        $transaction = Yii::$app->getDb()->beginTransaction();

        try {
            if ($model->saveWithParts()) {
                $this->stdout($filename . '  saved!');
                $transaction->commit();
            } else {
                $transaction->rollback();
                $this->stdout($filename . ' could not be saved!');
            }
        } catch (Exception $e) {
            $transaction->rollback();
            $this->stdout($filename . ' could not be saved!');
        }
        

        return ExitCode::OK;
    }

}

