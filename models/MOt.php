<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_ot".
 *
 * @property integer $ot_id
 * @property string $ot_tujuan
 * @property string $ot_tarif_slama
 * @property string $ot_tarif_sbaru
 * @property string $ot_satuan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MOt extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_ot';
    }
    
    public function behaviors(){
		return [\app\components\DeltaGeneralBehavior::className()];
	}
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ot_tujuan', 'ot_tarif_slama', 'ot_tarif_sbaru', 'ot_satuan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['created_by', 'updated_by'], 'integer'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['ot_tarif_slama', 'ot_tarif_sbaru','ot_tujuan'], 'string', 'max' => 50],
            [['ot_satuan'], 'string', 'max' => 10],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'ot_id' => Yii::t('app', 'Ot'),
                'ot_tujuan' => Yii::t('app', 'Tujuan'),
                'ot_tarif_slama' => Yii::t('app', 'Tarif Supir Lama (Rp)'),
                'ot_tarif_sbaru' => Yii::t('app', 'Tarif Supir Baru (Rp)'),
                'ot_satuan' => Yii::t('app', 'Satuan'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
}
