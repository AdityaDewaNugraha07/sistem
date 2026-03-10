<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_piutang_penjualan".
 *
 * @property integer $piutang_penjualan_id
 * @property string $kode
 * @property string $tipe
 * @property string $tanggal
 * @property integer $cust_id
 * @property string $bill_reff
 * @property string $tanggal_bill
 * @property string $cara_bayar
 * @property string $payment_reff
 * @property string $mata_uang
 * @property string $status_bayar
 * @property string $tanggal_bayar
 * @property double $tagihan
 * @property double $bayar
 * @property double $sisa
 * @property integer $custtop_top
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $keterangan
 *
 * @property MCustomer $cust
 * @property TCancelTransaksi $cancelTransaksi
 */ 
class TPiutangPenjualan extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $cust_an_nama,$kode_nota,$jumlah_tagihan,$saldo_awal_piutang;
	public $nominal_bill,$nominal_terima,$nominal_terpakai,$nominal_terbayar;
    public static function tableName()
    {
        return 't_piutang_penjualan';
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
            [['kode', 'tipe', 'tanggal', 'cust_id', 'bill_reff', 'tanggal_bill','cara_bayar', 'mata_uang', 'custtop_top', 'created_at', 'created_by', 'updated_at', 'updated_by','payment_reff'], 'required'],
            [['cust_id', 'custtop_top', 'cancel_transaksi_id', 'created_by', 'updated_by'], 'integer'],
			[['keterangan'], 'string'],
            [['tanggal', 'tanggal_bill', 'tanggal_bayar', 'created_at', 'updated_at','tagihan','bayar','sisa'], 'safe'],
            [['kode', 'cara_bayar', 'payment_reff', 'mata_uang'], 'string', 'max' => 50],
            [['tipe', 'status_bayar', 'status'], 'string', 'max' => 20],
            [['kode'], 'unique'],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'piutang_penjualan_id' => Yii::t('app', 'Piutang Penjualan'),
                'kode' => Yii::t('app', 'Kode'),
                'tipe' => Yii::t('app', 'Tipe Penjualan'),
				'tanggal' => Yii::t('app', 'Tanggal'),
                'cust_id' => Yii::t('app', 'Customer'),
                'bill_reff' => Yii::t('app', 'Bill Reff'),
				'tanggal_bill' => Yii::t('app', 'Tanggal Bill'),
				'cara_bayar' => Yii::t('app', 'Cara Bayar'),
                'payment_reff' => Yii::t('app', 'Payment Reff'),
                'mata_uang' => Yii::t('app', 'Mata Uang'),
                'status_bayar' => Yii::t('app', 'Status Bayar'),
                'tanggal_bayar' => Yii::t('app', 'Tanggal Bayar'),
                'tagihan' => Yii::t('app', 'Tagihan'),
                'bayar' => Yii::t('app', 'Bayar'),
                'sisa' => Yii::t('app', 'Sisa'),
                'custtop_top' => Yii::t('app', 'Term of Payment'),
                'status' => Yii::t('app', 'Status'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
				'keterangan' => Yii::t('app', 'Keterangan'),
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
        return $this->hasOne(TNotaPenjualan::className(), ['bill_reff' => 'kode']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(TInvoice::className(), ['bill_reff' => 'kode']);
    }
	
	public static function getTotalPiutangByNota($bill_reff){
		$total = 0;
        $models = self::find()->where("cancel_transaksi_id IS NULL AND bill_reff = '".$bill_reff."'")->all();
		foreach($models as $i => $mod){
			$total += $mod->total_bayar;
		}
        return $total;
    }
	
	public static function getOptionListCustPiutang($isexport)
    {
		if($isexport==true){
			$query = "SELECT m_customer.cust_id, m_customer.cust_an_nama FROM m_customer
					JOIN t_invoice ON t_invoice.cust_id = m_customer.cust_id
					WHERE cancel_transaksi_id IS NULL AND cust_tipe_penjualan = 'export' AND t_invoice.piutang_active IS TRUE
					GROUP BY m_customer.cust_id, m_customer.cust_an_nama 
					ORDER BY m_customer.cust_an_nama";
		}else{
			$query = "SELECT m_customer.cust_id, m_customer.cust_an_nama FROM m_customer
					JOIN t_nota_penjualan ON t_nota_penjualan.cust_id = m_customer.cust_id
					WHERE cancel_transaksi_id IS NULL AND cust_tipe_penjualan = 'lokal'
					GROUP BY m_customer.cust_id, m_customer.cust_an_nama 
					ORDER BY m_customer.cust_an_nama";
		}
        $res = \Yii::$app->db->createCommand($query)->queryAll();
		$return = [];
		if(count($res)>0){
			foreach($res as $i => $val){
				$return[$val['cust_id']] = $val['cust_an_nama'];
			}
		}
        return $return;
    }
}
