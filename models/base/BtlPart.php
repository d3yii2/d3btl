<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace d3yii2\d3btl\models\base;

use Yii;

/**
 * This is the base-model class for table "btl_part".
 *
 * @property integer $id
 * @property integer $file_data_id
 * @property string $type
 * @property integer $single_member_number
 * @property integer $assembly_number
 * @property integer $order_number
 * @property string $designation
 * @property string $annotation
 * @property string $storey
 * @property string $material
 * @property string $group
 * @property string $package
 * @property string $timber_grade
 * @property string $quality_grade
 * @property integer $count
 * @property integer $length
 * @property integer $height
 * @property integer $width
 * @property string $colour
 * @property integer $uid
 *
 * @property \d3yii2\d3btl\models\BtlFileData $fileData
 * @property string $aliasModel
 */
abstract class BtlPart extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
    */
    public const TYPE_RAWPART = 'rawpart';
    public const TYPE_PART = 'part';
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'btl_part';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'required' => [['file_data_id'], 'required'],
            'enum-type' => ['type', 'in', 'range' => [
                    self::TYPE_RAWPART,
                    self::TYPE_PART,
                ]
            ],
            'integer Unsigned' => [['id','file_data_id','single_member_number','assembly_number','order_number','count','length','height','width','uid'],'integer' ,'min' => 0 ,'max' => 4294967295],
            [['type'], 'string'],
            [['designation', 'annotation', 'storey', 'material', 'group', 'package', 'timber_grade', 'quality_grade', 'colour'], 'string', 'max' => 200],
            [['file_data_id'], 'exist', 'skipOnError' => true, 'targetClass' => \d3yii2\d3btl\models\BtlFileData::className(), 'targetAttribute' => ['file_data_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('btl', 'ID'),
            'file_data_id' => Yii::t('btl', 'File Data ID'),
            'type' => Yii::t('btl', 'Type'),
            'single_member_number' => Yii::t('btl', 'Single Member Number'),
            'assembly_number' => Yii::t('btl', 'Assembly Number'),
            'order_number' => Yii::t('btl', 'Order Number'),
            'designation' => Yii::t('btl', 'Designation'),
            'annotation' => Yii::t('btl', 'Annotation'),
            'storey' => Yii::t('btl', 'Storey'),
            'material' => Yii::t('btl', 'Material'),
            'group' => Yii::t('btl', 'Group'),
            'package' => Yii::t('btl', 'Package'),
            'timber_grade' => Yii::t('btl', 'Timber Grade'),
            'quality_grade' => Yii::t('btl', 'Quality Grade'),
            'count' => Yii::t('btl', 'Count'),
            'length' => Yii::t('btl', 'Length'),
            'height' => Yii::t('btl', 'Height'),
            'width' => Yii::t('btl', 'Width'),
            'colour' => Yii::t('btl', 'Colour'),
            'uid' => Yii::t('btl', 'Uid'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFileData()
    {
        return $this->hasOne(\poker\sharkability\models\BtlFileData::className(), ['id' => 'file_data_id']);
    }




    /**
     * get column type enum value label
     * @param string $value
     * @return string
     */
    public static function getTypeValueLabel(string $value): string
    {
        if (!$value) {
            return '';
        }
        $labels = self::optsType();
        return $labels[$value] ?? $value;
    }

    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType(): array
    {
        return [
            self::TYPE_RAWPART => Yii::t('btl', 'rawpart'),
            self::TYPE_PART => Yii::t('btl', 'part'),
        ];
    }
    /**
    * ENUM field values
    */
    /**
     * @return bool
     */
    public function isTypeRawpart(): bool
    {
        return $this->type === self::TYPE_RAWPART;
    }

     /**
     * @return void
     */
    public function setTypeRawpart(): void
    {
        $this->type = self::TYPE_RAWPART;
    }
    /**
     * @return bool
     */
    public function isTypePart(): bool
    {
        return $this->type === self::TYPE_PART;
    }

     /**
     * @return void
     */
    public function setTypePart(): void
    {
        $this->type = self::TYPE_PART;
    }
}
