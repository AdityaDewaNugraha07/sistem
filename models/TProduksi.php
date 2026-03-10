<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_produksi".
 *
 * @property integer $produksi_id
 * @property string $nomor_produksi
 * @property string $tanggal_produksi
 * @property string $nomor_urut_produksi
 * @property integer $produk_id
 * @property string $plymill_shift
 * @property string $sawmill_line
 * @property string $keterangan
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property HPersediaanProduk[] $hPersediaanProduks
 * @property TMutasiGudang[] $tMutasiGudangs
 * @property MBrgProduk $produk
 */ 
class TProduksi extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_produksi';
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
            [['nomor_produksi', 'tanggal_produksi', 'nomor_urut_produksi', 'produk_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'plymill_shift', 'sawmill_line'], 'required'],
            [['tanggal_produksi', 'created_at', 'updated_at'], 'safe'],
            [['produk_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['keterangan'], 'string'],
			[['nomor_urut_produksi'], 'string', 'min' => 6],
            [['nomor_urut_produksi'], 'string', 'max' => 6],
            [['nomor_produksi', 'plymill_shift', 'sawmill_line', 'status'], 'string', 'max' => 50],
            [['nomor_produksi'], 'unique'],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'produksi_id' => Yii::t('app', 'Produksi'),
			'nomor_produksi' => Yii::t('app', 'Kode Barang Jadi'),
			'tanggal_produksi' => Yii::t('app', 'Tanggal Produksi'),
			'nomor_urut_produksi' => Yii::t('app', 'Nomor Produksi'),
			'produk_id' => Yii::t('app', 'Produk'),
			'plymill_shift' => Yii::t('app', 'Plymill Shift'),
			'sawmill_line' => Yii::t('app', 'Sawmill Line'),
			'keterangan' => Yii::t('app', 'Keterangan'),
			'status' => Yii::t('app', 'Status'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }
	
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getHPersediaanProduks()
    {
        return $this->hasMany(HPersediaanProduk::className(), ['nomor_produksi' => 'nomor_produksi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTMutasiGudangs()
    {
        return $this->hasMany(TMutasiGudang::className(), ['nomor_produksi' => 'nomor_produksi']);
    } 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    }
}
