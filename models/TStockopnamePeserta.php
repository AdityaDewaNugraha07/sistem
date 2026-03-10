<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_stockopname_peserta".
 *
 * @property integer $stockopname_peserta_id
 * @property integer $stockopname_agenda_id
 * @property integer $pegawai_id
 * @property string $status
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MPegawai $pegawai
 * @property TStockopnameAgenda $stockopnameAgenda
 */
class TStockopnamePeserta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $pegawai_nama,$jabatan_nama,$departement_nama;
    public static function tableName()
    {
        return 't_stockopname_peserta';
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
            [['stockopname_agenda_id', 'pegawai_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['stockopname_agenda_id', 'pegawai_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 50],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['stockopname_agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => TStockopnameAgenda::className(), 'targetAttribute' => ['stockopname_agenda_id' => 'stockopname_agenda_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'stockopname_peserta_id' => 'Stockopname Peserta',
                'stockopname_agenda_id' => 'Stockopname Agenda',
                'pegawai_id' => 'Pegawai',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnameAgenda()
    {
        return $this->hasOne(TStockopnameAgenda::className(), ['stockopname_agenda_id' => 'stockopname_agenda_id']);
    }
}
