<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_jabatan".
 *
 * @property integer $jabatan_id
 * @property string $jabatan_nama
 * @property string $jabatan_nama_lain
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MJabatan extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_jabatan';
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
            [['jabatan_nama', 'jabatan_nama_lain', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['jabatan_nama', 'jabatan_nama_lain'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'jabatan_id' => Yii::t('app', 'Jabatan'),
                'jabatan_nama' => Yii::t('app', 'Nama Jabatan'),
                'jabatan_nama_lain' => Yii::t('app', 'Nama Lain'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->all();
        return \yii\helpers\ArrayHelper::map($res, 'jabatan_id', 'jabatan_nama');
    }
}
