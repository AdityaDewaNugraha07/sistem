<?php

namespace app\models;

use app\components\DeltaGeneralBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "t_mtrg_in_out_detail".
 *
 * @property integer $mtrg_in_out_detail_id
 * @property integer $mtrg_in_out_id
 * @property string $unit
 * @property double $tebal
 * @property string $size
 * @property string $grade
 * @property double $pcs
 * @property double $volume
 * @property integer $mtrg_setup_id
 * @property integer $patching
 *
 * @property TMtrgInOut $mtrgInOut
 */
class TMtrgInOutDetail extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_mtrg_in_out_detail';
    }
    
    public function behaviors(){
		return [DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mtrg_in_out_id', 'mtrg_setup_id'], 'integer'],
            [['unit', 'tebal', 'size', 'grade', 'pcs', 'volume'], 'required'],
            [['tebal', 'pcs', 'volume', 'patching'], 'number'],
            [['unit', 'size'], 'string', 'max' => 30],
            [['mtrg_in_out_id'], 'exist', 'skipOnError' => true, 'targetClass' => TMtrgInOut::className(), 'targetAttribute' => ['mtrg_in_out_id' => 'mtrg_in_out_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'mtrg_in_out_detail_id' => Yii::t('app', 'Mrtg In Out Detail'),
                'mtrg_in_out_id' => Yii::t('app', 'Mtrg In Out'),
                'unit' => Yii::t('app', 'Unit'),
                'tebal' => Yii::t('app', 'Tebal'),
                'size' => Yii::t('app', 'Size'),
                'grade' => Yii::t('app', 'Grade'),
                'pcs' => Yii::t('app', 'Pcs'),
                'volume' => Yii::t('app', 'Volume'),
                'mtrg_setup_id' => Yii::t('app', 'ID Setup Monitoring'),
                'patching' => Yii::t('app', 'Patch'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getMtrgInOut()
    {
        return $this->hasOne(TMtrgInOut::className(), ['mtrg_in_out_id' => 'mtrg_in_out_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSetup()
    {
        return $this->hasOne(MMtrgSetup::className(), ['mtrg_setup_id' => 'mtrg_setup_id']);
    }
}
