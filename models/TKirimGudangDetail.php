<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_kirim_gudang_detail".
 *
 * @property integer $kirim_gudang_detail_id
 * @property integer $kirim_gudang_id
 * @property integer $produk_id
 * @property string $nomor_produksi
 * @property boolean $terkirim
 * @property string $status
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 */
class TKirimGudangDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $qty_kecil,$qty_m3;
    public static function tableName()
    {
        return 't_kirim_gudang_detail';
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
            [['produk_id', 'nomor_produksi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['kirim_gudang_id', 'produk_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['terkirim'], 'boolean'],
            [['keterangan'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['nomor_produksi', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'kirim_gudang_detail_id' => 'Kirim Gudang Detail',
                'kirim_gudang_id' => 'Kirim Gudang',
                'produk_id' => 'Produk',
                'nomor_produksi' => 'Nomor Produksi',
                'terkirim' => 'Terkirim',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
        ];
    }
}
