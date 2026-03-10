<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_sales".
 *
 * @property integer $sales_id
 * @property string $sales_kode
 * @property string $sales_jns
 * @property string $sales_level
 * @property string $sales_tgl_join
 * @property string $sales_nm
 * @property string $sales_almt
 * @property string $sales_phone
 * @property string $sales_email
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MSales extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_sales';
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
            [['sales_kode', 'sales_jns', 'sales_level', 'sales_tgl_join', 'sales_nm', 'sales_almt', 'sales_phone', 'sales_email', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['sales_tgl_join', 'created_at', 'updated_at'], 'safe'],
            [['active'], 'boolean'],
            [['created_by', 'updated_by'], 'integer'],
            [['sales_kode', 'sales_jns', 'sales_level'], 'string', 'max' => 30],
            [['sales_nm'], 'string', 'max' => 50],
            [['sales_almt'], 'string', 'max' => 100],
            [['sales_phone', 'sales_email'], 'string', 'max' => 60],
            [['sales_email'], 'email'],
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sales_id' => Yii::t('app', 'Sales'),
            'sales_kode' => Yii::t('app', 'Kode Sales'),
            'sales_jns' => Yii::t('app', 'Jenis Sales'),
            'sales_level' => Yii::t('app', 'Level Sales'),
            'sales_tgl_join' => Yii::t('app', 'Tanggal Join'),
            'sales_nm' => Yii::t('app', 'Nama'),
            'sales_almt' => Yii::t('app', 'Alamat'),
            'sales_phone' => Yii::t('app', 'Telp / HP'),
            'sales_email' => Yii::t('app', 'Email'),
            'active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
	
	public static function getOptionList()
    {
        $res = self::find()->where(['active'=>true])->orderBy('sales_nm ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'sales_id', 'sales_nm');
    }
	public static function getOptionListAll()
    {
        $res = self::find()->where("")->orderBy('sales_nm ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'sales_id', 'sales_nm');
    }
}
