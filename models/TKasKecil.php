<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_kas_kecil".
 *
 * @property integer $kas_kecil_id
 * @property string $kode
 * @property string $tipe
 * @property string $jenis
 * @property string $tanggal
 * @property string $penerima
 * @property string $deskripsi
 * @property double $nominal
 * @property integer $voucher_pengeluaran_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property boolean $closing
 * @property string $tbp_reff
 * @property integer $seq
 * @property integer $bkk_id
 *
 * @property TKasBon[] $tKasBons
 * @property TCancelTransaksi $cancelTransaksi
 * @property TVoucherPengeluaran $voucherPengeluaran
 * @property TReturBhp[] $tReturBhps
 * @property TTerimaBhp[] $tTerimaBhps
 */ 
class TKasKecil extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $tgl_awal,$tgl_akhir,$kas_bon_id,$debit;
    public static function tableName()
    {
        return 't_kas_kecil';
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
            [['tipe', 'jenis', 'tanggal', 'penerima', 'deskripsi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['kode', 'tanggal', 'created_at', 'updated_at'], 'safe'],
            [['deskripsi', 'tbp_reff'], 'string'],
            [['nominal'], 'number'],
            [['voucher_pengeluaran_id', 'created_by', 'updated_by', 'cancel_transaksi_id', 'seq', 'bkk_id'], 'integer'],
            [['closing'], 'boolean'],
            [['kode', 'tipe', 'jenis', 'status'], 'string', 'max' => 50],
            [['penerima'], 'string', 'max' => 200],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['voucher_pengeluaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'kas_kecil_id' => Yii::t('app', 'Kas Kecil'),
			'kode' => Yii::t('app', 'Kode'),
			'tipe' => Yii::t('app', 'Tipe'),
			'jenis' => Yii::t('app', 'Jenis'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'penerima' => Yii::t('app', 'Penerima'),
			'deskripsi' => Yii::t('app', 'Deskripsi'),
			'nominal' => Yii::t('app', 'Nominal'),
			'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'status' => Yii::t('app', 'Status'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'closing' => Yii::t('app', 'Closing'),
			'tbp_reff' => Yii::t('app', 'Tbp Reff'),
			'seq' => Yii::t('app', 'Seq'),
			'bkk_id' => Yii::t('app', 'Bkk'),
        ];
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getTKasBons()
    {
        return $this->hasMany(TKasBon::className(), ['kas_kecil_id' => 'kas_kecil_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getBkk()
    {
        return $this->hasOne(TBkk::className(), ['bkk_id' => 'bkk_id']);
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
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTReturBhps()
    {
        return $this->hasMany(TReturBhp::className(), ['kas_kecil_id' => 'kas_kecil_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhps()
    {
        return $this->hasMany(TTerimaBhp::className(), ['kas_kecil_id' => 'kas_kecil_id']);
    } 
	
	public function searchLaporan() {
		$query = self::find();
		$query->select(self::tableName().'.pengeluaran_kaskecil_id, tanggal, kode, deskripsi, nominal');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.tanggal DESC, kode ASC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			$query->andWhere("kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->closing)){
			$query->andWhere("closing IS ".$this->closing);
		}
		return $query;
	}
}
