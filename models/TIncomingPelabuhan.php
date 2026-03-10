<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_incoming_pelabuhan".
 *
 * @property integer $incoming_pelabuhan_id
 * @property integer $keberangkatan_tongkang_id
 * @property string $kode
 * @property string $tanggal
 * @property string $status
 * @property string $keterangan
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TIncomingDkb $tIncomingDkb
 * @property TLoglist $loglist
 */
class TIncomingPelabuhan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $loglist_kode,$nomor_kontrak,$tongkang,$lokasi_muat,$kode_keberangkatan,$total_loglist,$total_batang,$total_m3;
    public static function tableName()
    {
        return 't_incoming_pelabuhan';
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
            [['keberangkatan_tongkang_id', 'kode', 'tanggal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['keberangkatan_tongkang_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['keterangan'], 'string'],
            [['kode'], 'string', 'max' => 25],
            [['status'], 'string', 'max' => 50],
            [['kode'], 'unique'],
            [['keberangkatan_tongkang_id'], 'exist', 'skipOnError' => true, 'targetClass' => TKeberangkatanTongkang::className(), 'targetAttribute' => ['keberangkatan_tongkang_id' => 'keberangkatan_tongkang_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'incoming_pelabuhan_id' => 'Incoming Pelabuhan',
                'keberangkatan_tongkang_id' => 'Kode Keberangkatan',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'status' => 'Status',
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
    public function getTIncomingDkb()
    {
        return $this->hasOne(TIncomingDkb::className(), ['incoming_pelabuhan_id' => 'incoming_pelabuhan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeberangkatanTongkang()
    {
        return $this->hasOne(TKeberangkatanTongkang::className(), ['keberangkatan_tongkang_id' => 'keberangkatan_tongkang_id']);
    }
}
