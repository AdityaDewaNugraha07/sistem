<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_surat_pengantar".
 *
 * @property integer $surat_pengantar_id
 * @property string $kode
 * @property string $tanggal
 * @property string $jenis_produk
 * @property integer $nota_penjualan_id
 * @property integer $spm_ko_id
 * @property string $kendaraan_nopol
 * @property string $kendaraan_supir
 * @property string $alamat_bongkar
 * @property integer $cust_id
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MCustomer $cust
 * @property TCancelTransaksi $cancelTransaksi
 * @property TNotaPenjualan $notaPenjualan
 * @property TSpmKo $spmKo
 * @property TSuratPengantarDetail[] $tSuratPengantarDetails
 * @property mixed|null $cust_alamat
 */
class TSuratPengantar extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_surat_pengantar';
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
            [['kode', 'tanggal', 'jenis_produk', 'nota_penjualan_id', 'spm_ko_id', 'kendaraan_nopol', 'kendaraan_supir', 'alamat_bongkar', 'cust_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['nota_penjualan_id', 'spm_ko_id', 'cust_id', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
            [['alamat_bongkar', 'cust_alamat'], 'string'],
            [['kode'], 'string', 'max' => 25],
            [['jenis_produk', 'kendaraan_supir', 'status'], 'string', 'max' => 50],
            [['kendaraan_nopol'], 'string', 'max' => 20],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['nota_penjualan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TNotaPenjualan::className(), 'targetAttribute' => ['nota_penjualan_id' => 'nota_penjualan_id']],
            [['spm_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpmKo::className(), 'targetAttribute' => ['spm_ko_id' => 'spm_ko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'surat_pengantar_id' => Yii::t('app', 'Surat Pengantar'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'jenis_produk' => Yii::t('app', 'Jenis Produk'),
                'nota_penjualan_id' => Yii::t('app', 'Nota Penjualan'),
                'spm_ko_id' => Yii::t('app', 'Spm Ko'),
                'kendaraan_nopol' => Yii::t('app', 'Kendaraan Nopol'),
                'kendaraan_supir' => Yii::t('app', 'Kendaraan Supir'),
                'alamat_bongkar' => Yii::t('app', 'Alamat Bongkar'),
                'cust_id' => Yii::t('app', 'Cust'),
                'status' => Yii::t('app', 'Status'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cust_alamat'=> Yii::t('app', 'Customer Address')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
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
    public function getNotaPenjualan()
    {
        return $this->hasOne(TNotaPenjualan::className(), ['nota_penjualan_id' => 'nota_penjualan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpmKo()
    {
        return $this->hasOne(TSpmKo::className(), ['spm_ko_id' => 'spm_ko_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSuratPengantarDetails()
    {
        return $this->hasMany(TSuratPengantarDetail::className(), ['surat_pengantar_id' => 'surat_pengantar_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(MUser::className(), ['user_id' => 'created_by']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdukJasa()
    {
        return $this->hasOne(MProdukJasa::className(), ['produk_jasa_id' => 'produk_id']);
    }
}
