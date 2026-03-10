<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_gudang".
 *
 * @property integer $gudang_id
 * @property string $gudang_kode
 * @property string $gudang_nm
 * @property string $gudang_ket
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MGudang extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_gudang';
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
            [['gudang_kode', 'gudang_nm', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['gudang_ket'], 'string'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['gudang_kode', 'gudang_nm'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'gudang_id' => Yii::t('app', 'Gudang'),
                'gudang_kode' => Yii::t('app', 'Kode Gedung'),
                'gudang_nm' => Yii::t('app', 'Nama'),
                'gudang_ket' => Yii::t('app', 'Keterangan'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('gudang_nm ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'gudang_id', 'gudang_nm');
    }
}
