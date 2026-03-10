<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_penawaran_bhp".
 *
 * @property integer $penawaran_bhp_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $suplier_id
 * @property integer $bhp_id
 * @property double $qty
 * @property string $satuan_kecil
 * @property double $harga_satuan
 * @property double $harga_total
 * @property string $keterangan
 * @property string $attachment
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MBrgBhp $bhp
 * @property MSuplier $suplier
 * @property TCancelTransaksi $cancelTransaksi
 */
class TPenawaranBhp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $bhp_nm,$file1,$id;
    public static function tableName()
    {
        return 't_penawaran_bhp';
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
            [['kode', 'tanggal', 'suplier_id', 'bhp_id', 'qty', 'satuan_kecil', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['kode', 'tanggal', 'created_at', 'updated_at','qty','harga_satuan'], 'safe'],
            [['suplier_id', 'bhp_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            // [['keterangan','attachment'], 'string'],
            [['keterangan'], 'string'],
            [['attachment'], 'file', 'extensions' => 'pdf, jpeg, jpg, png', 'message' => 'Wajib upload file penawaran (PDF/JPG/PNG).'],  //, 'maxSize' => 1024 * 1024 * 5,'skipOnEmpty' => false, 
            [['attachment'], 'string', 'max' => 255],
            [['kode','satuan_kecil'], 'string', 'max' => 20],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'penawaran_bhp_id' => 'Penawaran Bhp',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'suplier_id' => 'Suplier',
                'bhp_id' => 'Bhp',
                'qty' => 'Qty',
                'satuan_kecil' => 'Satuan Kecil',
                'harga_satuan' => 'Harga Satuan',
                'keterangan' => 'Keterangan',
                'attachment' => 'Attachment',
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
    public function getBhp()
    {
        return $this->hasOne(MBrgBhp::className(), ['bhp_id' => 'bhp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuplier()
    {
        return $this->hasOne(MSuplier::className(), ['suplier_id' => 'suplier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
}
