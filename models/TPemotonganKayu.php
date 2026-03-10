<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pemotongan_kayu".
 *
 * @property integer $pemotongan_kayu_id
 * @property string $kode
 * @property string $nomor
 * @property string $tanggal
 * @property integer $petugas
 * @property string $keterangan
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MPegawai $petugas0
 */
class TPemotonganKayu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_pemotongan_kayu';
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
            [['kode', 'nomor', 'tanggal', 'petugas', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['petugas', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['keterangan'], 'string'],
            [['kode'], 'string', 'max' => 25],
            [['nomor'], 'string', 'max' => 50],
            [['nomor'], 'unique'],
            [['petugas'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['petugas' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pemotongan_kayu_id' => 'Pemotongan Kayu',
                'kode' => 'Kode',
                'nomor' => 'Nomor',
                'tanggal' => 'Tanggal',
                'petugas' => 'Petugas',
                'keterangan' => 'Keterangan',
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
    public function getPetugas0()
    {
        return $this->hasOne(MPetugasLegalkayu::className(), ['petugas_legalkayu_id' => 'petugas']);
    }
}
