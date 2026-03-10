<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_stockopname".
 *
 * @property integer $stockopname_id
 * @property integer $stockopname_agenda_id
 * @property string $waktu_scan
 * @property string $nomor_produksi
 * @property integer $gudang_id
 * @property integer $produk_id
 * @property integer $produksi_id
 * @property integer $pegawai_id
 * @property string $status
 * @property string $keterangan
 * @property string $keterangan2
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MBrgProduk $produk
 * @property MPegawai $pegawai
 * @property TProduksi $produksi
 * @property TStockopnameAgenda $stockopnameAgenda
 */
class TStockopname extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $kode_agenda,$nama_peserta,$lokasi_gudang;
    public static function tableName()
    {
        return 't_stockopname';
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
            [['stockopname_agenda_id', 'waktu_scan', 'nomor_produksi', 'pegawai_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['stockopname_agenda_id', 'gudang_id', 'produk_id', 'produksi_id', 'pegawai_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['waktu_scan', 'created_at', 'updated_at'], 'safe'],
            [['keterangan', 'keterangan2'], 'string'],
            [['nomor_produksi', 'status'], 'string', 'max' => 50],
            [['gudang_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGudang::className(), 'targetAttribute' => ['gudang_id' => 'gudang_id']],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['produksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TProduksi::className(), 'targetAttribute' => ['produksi_id' => 'produksi_id']],
            [['stockopname_agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => TStockopnameAgenda::className(), 'targetAttribute' => ['stockopname_agenda_id' => 'stockopname_agenda_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'stockopname_id' => 'Stockopname',
                'stockopname_agenda_id' => 'Stockopname Agenda',
                'waktu_scan' => 'Waktu Scan',
                'nomor_produksi' => 'Nomor Produksi',
                'gudang_id' => 'Lokasi Gudang',
                'produk_id' => 'Produk',
                'produksi_id' => 'Produksi',
                'pegawai_id' => 'Pegawai',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'keterangan2' => 'Keterangan2',
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
    public function getGudang()
    {
        return $this->hasOne(MGudang::className(), ['gudang_id' => 'gudang_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
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
    public function getProduksi()
    {
        return $this->hasOne(TProduksi::className(), ['produksi_id' => 'produksi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnameAgenda()
    {
        return $this->hasOne(TStockopnameAgenda::className(), ['stockopname_agenda_id' => 'stockopname_agenda_id']);
    }
}
