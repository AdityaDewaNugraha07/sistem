<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_kurs_rupiah".
 *
 * @property integer $kurs_rupiah_id
 * @property string $tanggal
 * @property double $usd
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $tanggal_akhir
 * @property string $keterangan
 * @property string $keperluan
 * @property string $sumber
 */
class HKursRupiah extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_kurs_rupiah';
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
            [['tanggal', 'usd', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['tanggal','tanggal_akhir','keterangan','keperluan','sumber'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'kurs_rupiah_id' => Yii::t('app', 'Kurs Rupiah'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'usd' => Yii::t('app', 'Rupiah per USD'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'tanggal_akhir' => Yii::t('app', 'Tanggal Akhir'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'keperluan' => Yii::t('app', 'Keperluan'),
                'sumber' => Yii::t('app', 'Sumber'),
        ];
    }
    
    public static function getKursOrtax($tgl)
    {
        $ret = 0;
        $mod = self::find()->where("keperluan = 'KURS PERIODIK' AND tanggal <= '{$tgl}' AND tanggal_akhir >= '{$tgl}'")->one();
        if($mod){
            $ret = $mod->usd;
        }
        return $ret;
    }
}
