<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_warna_kayu".
 *
 * @property integer $warna_kayu_id
 * @property string $jenis_produk
 * @property string $kode
 * @property string $nama
 * @property string $othername
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class MWarnaKayu extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_warna_kayu';
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
            [['jenis_produk', 'kode', 'nama', 'othername', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['jenis_produk', 'kode'], 'string', 'max' => 50],
            [['nama', 'othername'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'warna_kayu_id' => Yii::t('app', 'Warna Kayu'),
                'jenis_produk' => Yii::t('app', 'Jenis Produk'),
                'kode' => Yii::t('app', 'Kode'),
                'nama' => Yii::t('app', 'Nama'),
                'othername' => Yii::t('app', 'Othername'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
	
	public static function getOptionListNama($jenis_produk=null)
    {
		if(!empty($jenis_produk)){
			$res = self::find()->where(['active'=>true,'jenis_produk'=>$jenis_produk])->orderBy('warna_kayu_id ASC')->all();
		}else{
			$res = self::find()->where(['active'=>true])->orderBy('warna_kayu_id ASC')->all();
		}
        return \yii\helpers\ArrayHelper::map($res, 'nama', 'nama');
    }
}
