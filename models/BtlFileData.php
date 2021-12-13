<?php

namespace d3yii2\d3btl\models;

use d3yii2\d3btl\models\base\BtlFileData as BaseBtlFileData;
use d3yii2\d3btl\components\BTLFileParser;

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

            $dateTime = str_replace('\\', ' ', $generalInfo['EXPORTDATE'] . $generalInfo['EXPORTTIME']);
            $dateTime = str_replace('"', ' ', $dateTime);

            $this->export_datetime = $dateTime;
        }

        return true;
    }

    /**
     * @return array()
     */
    public function parseFile()
    {
        $parser = new BTLFileParser($this->file_data);

        $parsedData = $parser->getParts();
        $this->parsed_data = json_encode($parsedData);

        foreach ($parsedData as $part) {

            if (BTLFileParser::GENERAL === $part->type) {
                $generalInfo = $part->parsedText;
                continue;
            }

            $this->parts[] = $part;
        }

        return $generalInfo;
    }

    /**
     * @return bool
     */
    public function saveWithParts()
    {

       if (!parent::save()) {
           return false;
       }

        foreach ($this->parts as $part) {

            $partInfo = $part->parsedText;

            $btlPart = new BtlPart();

            $btlPart->file_data_id = $this->id;
            $btlPart->type = $part->type;

            $btlPart->single_member_number = (int)$partInfo['SINGLEMEMBERNUMBER'];
            $btlPart->assembly_number = (int)$partInfo['ASSEMBLYNUMBER'] ;
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

            // need a save check
            $btlPart->save();
        }

        return true;
    }

}