<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_kas_besar_nontunai".
 *
 * @property integer $kas_besar_nontunai_id
 * @property string $kode
 * @property string $tanggal
 * @property string $nama_customer
 * @property string $no_bukti
 * @property string $cust_bank
 * @property string $cust_acct
 * @property string $reff_number
 * @property string $tanggal_jatuhtempo
 * @property double $nominal
 * @property string $keterangan
 * @property boolean $closing
 * @property integer $seq
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property TCancelTransaksi $cancelTransaksi
 */
class TKasBesarNontunai extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $tgl_awal,$tgl_akhir;
    public static function tableName()
    {
        return 't_kas_besar_nontunai';
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
            [['kode', 'tanggal', 'nama_customer', 'reff_number', 'tanggal_jatuhtempo', 'nominal', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_jatuhtempo', 'created_at', 'updated_at','nominal'], 'safe'],
            [[], 'number'],
            [['keterangan'], 'string'],
            [['closing'], 'boolean'],
            [['seq', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['kode', 'nama_customer', 'no_bukti', 'cust_bank', 'cust_acct', 'reff_number'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 30],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'kas_besar_nontunai_id' => Yii::t('app', 'Kas Besar Nontunai'),
                'kode' => Yii::t('app', 'Kode'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'nama_customer' => Yii::t('app', 'Nama Customer'),
                'no_bukti' => Yii::t('app', 'No Bukti'),
                'cust_bank' => Yii::t('app', 'Cust Bank'),
                'cust_acct' => Yii::t('app', 'Cust Acct'),
                'reff_number' => Yii::t('app', 'Reff Number'),
                'tanggal_jatuhtempo' => Yii::t('app', 'Tanggal Jatuhtempo'),
                'nominal' => Yii::t('app', 'Nominal'),
                'keterangan' => Yii::t('app', 'Keterangan'),
                'closing' => Yii::t('app', 'Closing'),
                'seq' => Yii::t('app', 'Seq'),
                'status' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
	
	public function searchLaporan() {
		$query = self::find();
		$query->select(self::tableName().'.kas_besar_nontunai_id, kode, tanggal, nama_customer, cust_bank, cust_acct, reff_number, tanggal_jatuhtempo, nominal, keterangan');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			$query->andWhere("kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->keterangan)){
			$query->andWhere("keterangan ILIKE '%".$this->keterangan."%'");
		}
		if(!empty($this->nama_customer)){
			$query->andWhere("nama_customer ILIKE '%".$this->nama_customer."%'");
		}
		return $query;
	}
	
	public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
		if(!empty($searchLaporan->groupBy)){
			$param['column'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}	
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->orderBy)){
			foreach($searchLaporan->orderBy as $i_order => $order){
				$param['order'][] = $i_order." ".(($order == 3)?"DESC":"ASC");
			}
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		$param['where'] = [];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			array_push($param['where'],"kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->keterangan)){
			array_push($param['where'],"keterangan ILIKE '%".$this->keterangan."%'");
		}
		if(!empty($this->nama_customer)){
			array_push($param['where'],"nama_customer ILIKE '%".$this->nama_customer."%'");
		}
		return $param;
	}
}
