<?php

namespace app\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "t_spl".
 *
 * @property integer $spl_id
 * @property string $spl_kode
 * @property string $spl_tanggal
 * @property integer $spl_disetujui
 * @property string $spl_tanggal_disetujui
 * @property string $spl_status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $terima_bhp_id
 * @property integer $cancel_transaksi_id
 *
 * @property MPegawai $splDisetujui
 * @property TCancelTransaksi $cancelTransaksi
 * @property TSplDetail[] $tSplDetails
 * @property TTerimaBhp[] $tTerimaBhps
 */ 
class TSpl extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_spl';
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
            [['spl_tanggal', 'spl_disetujui', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['spl_tanggal', 'spl_tanggal_disetujui', 'created_at', 'updated_at'], 'safe'],
            [['spl_disetujui', 'created_by', 'updated_by', 'terima_bhp_id', 'cancel_transaksi_id'], 'integer'],
            [['spl_kode', 'spl_status'], 'string', 'max' => 30],
            [['spl_disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['spl_disetujui' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'spl_id' => Yii::t('app', 'Spl'),
			'spl_kode' => Yii::t('app', 'Kode'),
			'spl_tanggal' => Yii::t('app', 'Tanggal'),
			'spl_disetujui' => Yii::t('app', 'Di Setujui'),
			'spl_tanggal_disetujui' => Yii::t('app', 'Tanggal Di Setujui'),
			'spl_status' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'terima_bhp_id' => Yii::t('app', 'Terima Bhp'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSplDisetujui()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'spl_disetujui']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSplCreatedBy()
    {
        return $this->hasOne(MUser::className(), ['user_id' => 'created_by']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSplDetails()
    {
        return $this->hasMany(TSplDetail::className(), ['spl_id' => 'spl_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhps()
    {
        return $this->hasMany(TTerimaBhp::className(), ['terima_bhp_id' => 'terima_bhp_id'])->all();
    }
	
	public static function getOptionListPenerimaan()
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL")->orderBy('created_at DESC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'spl_id', 'spl_kode');
    }
}
