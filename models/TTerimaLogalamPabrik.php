<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_logalam_pabrik".
 *
 * @property integer $terima_logalam_pabrik_id
 * @property integer $terima_logalam_detail_id
 * @property string $tanggal
 * @property string $kode
 * @property integer $kayu_id
 * @property integer $pic_terima
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MPegawai $picTerima
 * @property TTerimaLogalamDetail $terimaLogalamDetail
 */
class TTerimaLogalamPabrik extends \yii\db\ActiveRecord
{
    public $tgl_awal, $tgl_akhir, $fsc;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_terima_logalam_pabrik';
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
            [['terima_logalam_detail_id', 'kayu_id', 'pic_terima', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'kode', 'kayu_id', 'pic_terima', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 25],
            [['pic_terima'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pic_terima' => 'pegawai_id']],
            [['terima_logalam_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaLogalamDetail::className(), 'targetAttribute' => ['terima_logalam_detail_id' => 'terima_logalam_detail_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'terima_logalam_pabrik_id' => 'Terima Logalam Pabrik',
                'terima_logalam_detail_id' => 'Terima Logalam Detail',
                'tanggal' => 'Tanggal',
                'kode' => 'Kode',
                'kayu_id' => 'Kayu',
                'pic_terima' => 'Pic Terima',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicTerima()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pic_terima']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaLogalamDetail()
    {
        return $this->hasOne(TTerimaLogalamDetail::className(), ['terima_logalam_detail_id' => 'terima_logalam_detail_id']);
    }
}
