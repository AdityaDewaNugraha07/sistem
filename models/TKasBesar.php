<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_kas_besar".
 *
 * @property integer $kas_besar_id
 * @property string $kode
 * @property string $tipe
 * @property string $tanggal
 * @property string $penerima
 * @property string $deskripsi
 * @property double $nominal
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property boolean $closing
 * @property string $reff
 * @property string $no_tandaterima
 * @property string $cara_transaksi
 * @property string $reff_cara_transaksi
 * @property string $jenis_penerimaan
 * @property integer $nota_penjualan_id
 *
 * @property TCancelTransaksi $cancelTransaksi
 */ 
class TKasBesar extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $kredit,$tgl_awal,$tgl_akhir,$debit;
    public static function tableName()
    {
        return 't_kas_besar';
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
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['deskripsi', 'reff', 'reff_cara_transaksi'], 'string'],
            [['nominal'], 'number'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['created_by', 'updated_by', 'cancel_transaksi_id','nota_penjualan_id'], 'integer'],
            [['closing'], 'boolean'],
            [['kode', 'tipe', 'status', 'no_tandaterima', 'cara_transaksi', 'jenis_penerimaan'], 'string', 'max' => 50],
            [['penerima'], 'string', 'max' => 200],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'kas_besar_id' => Yii::t('app', 'Kas Besar'),
			'kode' => Yii::t('app', 'Kode'),
			'tipe' => Yii::t('app', 'Tipe'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'penerima' => Yii::t('app', 'Penerima'),
			'deskripsi' => Yii::t('app', 'Deskripsi'),
			'nominal' => Yii::t('app', 'Nominal'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'status' => Yii::t('app', 'Status'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'closing' => Yii::t('app', 'Closing'),
			'reff' => Yii::t('app', 'Reff'),
			'nota_penjualan_id' => Yii::t('app', 'Nota Penjualan'),
			'no_tandaterima' => Yii::t('app', 'No Tandaterima'),
			'cara_transaksi' => Yii::t('app', 'Cara Transaksi'),
			'reff_cara_transaksi' => Yii::t('app', 'Reff Cara Transaksi'),
			'jenis_penerimaan' => Yii::t('app', 'Jenis Penerimaan'),
        ];
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
	
	public function searchLaporan() {
		$query = self::find();
		$query->select(self::tableName().'.kas_besar_id, tanggal, no_tandaterima, deskripsi, nominal');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->no_tandaterima)){
			$query->andWhere("no_tandaterima ILIKE '%".$this->no_tandaterima."%'");
		}
		if(!empty($this->deskripsi)){
			$query->andWhere("deskripsi ILIKE '%".$this->deskripsi."%'");
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
		if(!empty($this->no_tandaterima)){
			array_push($param['where'],"no_tandaterima ILIKE '%".$this->no_tandaterima."%'");
		}
		if(!empty($this->deskripsi)){
			array_push($param['where'],"deskripsi ILIKE '%".$this->deskripsi."%'");
		}
		return $param;
	}
}
