<?php

namespace d3yii2\d3btl\models;

use d3system\exceptions\D3ActiveRecordException;
use d3yii2\d3btl\models\base\BtlFileData as BaseBtlFileData;
use d3yii2\d3btl\components\BTLFileParser;
use yii\helpers\Json;

/**
 * This is the model class for table "btl_file_data".
 */
class BtlFileData extends BaseBtlFileData
{

    /**
     * @var array
     */
    public $parts = [];

    public function load($data, $formName = null)
    {
        if (!parent::load($data, $formName)) {
            return false;
        }

        if ($this->file_data && !$this->parsed_data) {
            $generalInfo = $this->parseFile();

            $this->status = BtlFileData::STATUS_PROCESSED;
            $this->add_time = date('Y-m-d H:i:s');
            $this->project_name = $generalInfo['PROJECTNAME'];

            $dateTime = str_replace(
                ['\\', '"'],
                ' ',
                $generalInfo['EXPORTDATE'] . ' ' . $generalInfo['EXPORTTIME']);

            $this->export_datetime = $dateTime;
        }

        return true;
    }

    /**
     * @return array()
     */
    public function parseFile(): array
    {
        $parser = new BTLFileParser($this->file_data);

        $parsedData = $parser->getParts();
        $this->parsed_data =  Json::encode($parsedData);
        $generalInfo = [];
        foreach ($parsedData as $part) {

            if (BTLFileParser::GENERAL === $part->type) {
                $generalInfo = $part->getParsedText();
                continue;
            }

            $this->parts[] = $part;
        }

        return $generalInfo;
    }

    /**
     * @return bool
     * @throws \d3system\exceptions\D3ActiveRecordException
     */
    public function saveWithParts(): bool
    {

        if (!$this->save()) {
            return false;
        }

        /**
         * @var \d3yii2\d3btl\components\BTLFilePart $part
         */
        foreach ($this->parts as $part) {

            $partInfo = $part->getParsedText();

            $btlPart = new BtlPart();

            $btlPart->file_data_id = $this->id;
            $btlPart->type = strtolower($part->type);

            $btlPart->single_member_number = (int)$partInfo['SINGLEMEMBERNUMBER'];
            $btlPart->assembly_number = (int)$partInfo['ASSEMBLYNUMBER'];
            $btlPart->order_number = $partInfo['ORDERNUMBER'];
            $btlPart->designation = $partInfo['DESIGNATION'];
            $btlPart->annotation = $partInfo['ANNOTATION'];
            $btlPart->storey = $partInfo['STOREY'];
            $btlPart->material = $partInfo['MATERIAL'];
            $btlPart->group = $partInfo['GROUP'];
            $btlPart->package = $partInfo['PACKAGE'];
            $btlPart->timber_grade = $partInfo['TIMBERGRADE'];
            $btlPart->quality_grade = $partInfo['QUALITYGRADE'];
            $btlPart->count = $partInfo['COUNT'];
            $btlPart->length = $partInfo['LENGTH'];
            $btlPart->height = $partInfo['HEIGHT'];
            $btlPart->width = $partInfo['WIDTH'];
            $btlPart->colour = $partInfo['COLOUR'];
            $btlPart->uid = $partInfo['UID'];
            $btlPart->timbergrade = $partInfo['TIMBERGRADE']??null;
            $btlPart->raw_data = $part->rawData;
            if (!$btlPart->save()) {
                throw new D3ActiveRecordException($btlPart);
            }
            $btlPart->saveWithProcess($part->processes);
        }

        foreach($this->btlParts as $part) {
            if ($part->isTypePart()) {
                continue;
            }
            BtlPart::updateAll(
                ['master_part_id' => $part->id],
                [
                    'file_data_id' => $part->file_data_id,
                    'type' => BtlPart::TYPE_PART,
                    'timbergrade' => $part->single_member_number
                ]
            );
        }
        $this->refresh();

        return true;
    }

    public function delete()
    {
        foreach ($this->btlParts as $part) {
            $part->delete();
        }
        return parent::delete();
    }

}