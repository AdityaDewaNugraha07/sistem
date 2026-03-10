<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_wilayah_dinas".
 *
 * @property integer $wilayah_dinas_id
 * @property string $wilayah_dinas_nama
 * @property double $wilayah_dinas_plafon
 * @property double $wilayah_dinas_makan
 * @property double $wilayah_dinas_pulsa
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MWilayahDinas extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_wilayah_dinas';
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
            [['wilayah_dinas_nama'], 'string'],
            [['wilayah_dinas_plafon', 'wilayah_dinas_makan', 'wilayah_dinas_pulsa'], 'number'],
            [['active'], 'boolean'],
            [['wilayah_dinas_nama','created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'wilayah_dinas_id' => Yii::t('app', 'Wilayah Dinas'),
                'wilayah_dinas_nama' => Yii::t('app', 'Nama Wilayah'),
                'wilayah_dinas_plafon' => Yii::t('app', 'Maksimal Plafon (Rp)'),
                'wilayah_dinas_makan' => Yii::t('app', 'Biaya Makan per Hari (Rp)'),
                'wilayah_dinas_pulsa' => Yii::t('app', 'Biaya Pulsa (Rp)'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('created_at ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'wilayah_dinas_id', 'wilayah_dinas_nama');
    }
}
