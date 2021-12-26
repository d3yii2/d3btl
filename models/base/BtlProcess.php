<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace d3yii2\d3btl\models\base;

use Yii;

/**
 * This is the base-model class for table "btl_process".
 *
 * @property integer $id
 * @property integer $part_id
 * @property string $key
 * @property string $parameters
 * @property integer $ident
 * @property string $quality
 * @property string $recess
 * @property string $comment
 *
 * @property \d3yii2\d3btl\models\BtlPart $part
 * @property string $aliasModel
 */
abstract class BtlProcess extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
    */
    public const QUALITY_AUTOMATIC = 'automatic';
    public const QUALITY_VISIBLE = 'visible';
    public const QUALITY_FAST = 'fast';
    public const RECESS_AUTOMATIC = 'automatic';
    public const RECESS_MANUAL = 'manual';
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'btl_process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'required' => [['part_id'], 'required'],
            'enum-quality' => ['quality', 'in', 'range' => [
                    self::QUALITY_AUTOMATIC,
                    self::QUALITY_VISIBLE,
                    self::QUALITY_FAST,
                ]
            ],
            'enum-recess' => ['recess', 'in', 'range' => [
                    self::RECESS_AUTOMATIC,
                    self::RECESS_MANUAL,
                ]
            ],
            'smallint Signed' => [['ident'],'integer' ,'min' => -32768 ,'max' => 32767],
            'integer Unsigned' => [['id','part_id'],'integer' ,'min' => 0 ,'max' => 4294967295],
            [['parameters', 'quality', 'recess', 'comment'], 'string'],
            [['key'], 'string', 'max' => 20],
            [['part_id'], 'exist', 'skipOnError' => true, 'targetClass' => \d3yii2\d3btl\models\BtlPart::className(), 'targetAttribute' => ['part_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('d3btl', 'ID'),
            'part_id' => Yii::t('d3btl', 'Part ID'),
            'key' => Yii::t('d3btl', 'Key'),
            'parameters' => Yii::t('d3btl', 'Parameters'),
            'ident' => Yii::t('d3btl', 'Ident'),
            'quality' => Yii::t('d3btl', 'Quality'),
            'recess' => Yii::t('d3btl', 'Recess'),
            'comment' => Yii::t('d3btl', 'Comment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPart()
    {
        return $this->hasOne(\d3yii2\d3btl\models\BtlPart::className(), ['id' => 'part_id']);
    }




    /**
     * get column quality enum value label
     * @param string $value
     * @return string
     */
    public static function getQualityValueLabel(string $value): string
    {
        if (!$value) {
            return '';
        }
        $labels = self::optsQuality();
        return $labels[$value] ?? $value;
    }

    /**
     * column quality ENUM value labels
     * @return string[]
     */
    public static function optsQuality(): array
    {
        return [
            self::QUALITY_AUTOMATIC => Yii::t('d3btl', 'automatic'),
            self::QUALITY_VISIBLE => Yii::t('d3btl', 'visible'),
            self::QUALITY_FAST => Yii::t('d3btl', 'fast'),
        ];
    }

    /**
     * get column recess enum value label
     * @param string $value
     * @return string
     */
    public static function getRecessValueLabel(string $value): string
    {
        if (!$value) {
            return '';
        }
        $labels = self::optsRecess();
        return $labels[$value] ?? $value;
    }

    /**
     * column recess ENUM value labels
     * @return string[]
     */
    public static function optsRecess(): array
    {
        return [
            self::RECESS_AUTOMATIC => Yii::t('d3btl', 'automatic'),
            self::RECESS_MANUAL => Yii::t('d3btl', 'manual'),
        ];
    }
    /**
    * ENUM field values
    */
    /**
     * @return bool
     */
    public function isQualityAutomatic(): bool
    {
        return $this->quality === self::QUALITY_AUTOMATIC;
    }

     /**
     * @return void
     */
    public function setQualityAutomatic(): void
    {
        $this->quality = self::QUALITY_AUTOMATIC;
    }
    /**
     * @return bool
     */
    public function isQualityVisible(): bool
    {
        return $this->quality === self::QUALITY_VISIBLE;
    }

     /**
     * @return void
     */
    public function setQualityVisible(): void
    {
        $this->quality = self::QUALITY_VISIBLE;
    }
    /**
     * @return bool
     */
    public function isQualityFast(): bool
    {
        return $this->quality === self::QUALITY_FAST;
    }

     /**
     * @return void
     */
    public function setQualityFast(): void
    {
        $this->quality = self::QUALITY_FAST;
    }
    /**
     * @return bool
     */
    public function isRecessAutomatic(): bool
    {
        return $this->recess === self::RECESS_AUTOMATIC;
    }

     /**
     * @return void
     */
    public function setRecessAutomatic(): void
    {
        $this->recess = self::RECESS_AUTOMATIC;
    }
    /**
     * @return bool
     */
    public function isRecessManual(): bool
    {
        return $this->recess === self::RECESS_MANUAL;
    }

     /**
     * @return void
     */
    public function setRecessManual(): void
    {
        $this->recess = self::RECESS_MANUAL;
    }

}
