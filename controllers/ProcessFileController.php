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
     * @param string|null $notes
     * @return int
     */
    public function actionAdd(string $filename, string $notes = null): int
    {

        $fileText = file_get_contents($filename);

        $model = new BtlFileData();
        $model->load(['file_data' => $fileText, 'file_name' => $filename], '');
        $model->notes = $notes;
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

