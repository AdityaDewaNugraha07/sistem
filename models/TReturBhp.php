<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_retur_bhp".
 *
 * @property integer $retur_bhp_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $terima_bhpd_id
 * @property double $qty
 * @property double $harga
 * @property double $potongan
 * @property double $total_kembali
 * @property string $deskripsi
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property integer $kas_kecil_id
 *
 * @property TCancelTransaksi $cancelTransaksi
 * @property TKasKecil $kasKecil
 * @property TTerimaBhpDetail $terimaBhpd
 */ 
class TReturBhp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	
	public $bhp_nm,$bhp_kode,$terimabhp_kode,$qty_in,$qty_out,$keterangan,$bhp_id,$tgl_awal,$tgl_akhir,$harga_terima;
    public static function tableName()
    {
        return 't_retur_bhp';
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
            [['kode', 'tanggal', 'terima_bhpd_id', 'deskripsi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at','potongan', 'harga',  'total_kembali'], 'safe'],
            [['terima_bhpd_id', 'created_by', 'updated_by', 'cancel_transaksi_id', 'kas_kecil_id'], 'integer'],
            [['qty'], 'number'],
            [['deskripsi'], 'string'],
            [['kode'], 'string', 'max' => 30],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
			[['kas_kecil_id'], 'exist', 'skipOnError' => true, 'targetClass' => TKasKecil::className(), 'targetAttribute' => ['kas_kecil_id' => 'kas_kecil_id']],
            [['terima_bhpd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaBhpDetail::className(), 'targetAttribute' => ['terima_bhpd_id' => 'terima_bhpd_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'retur_bhp_id' => Yii::t('app', 'Retur Bhp'),
			'kode' => Yii::t('app', 'Kode'),
			'tanggal' => Yii::t('app', 'Tanggal'),
			'terima_bhpd_id' => Yii::t('app', 'Terima Bhpd'),
			'qty' => Yii::t('app', 'Qty'),
			'harga' => Yii::t('app', 'Harga'),
			'potongan' => Yii::t('app', 'Potongan'),
			'total_kembali' => Yii::t('app', 'Total Kembali'),
			'deskripsi' => Yii::t('app', 'Deskripsi'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'kas_kecil_id' => Yii::t('app', 'Kas Kecil'),
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
    public function getKasKecil()
    {
        return $this->hasOne(TKasKecil::className(), ['kas_kecil_id' => 'kas_kecil_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaBhpd()
    {
        return $this->hasOne(TTerimaBhpDetail::className(), ['terima_bhpd_id' => 'terima_bhpd_id']);
    } 
	
	
	public function searchLaporan() {
		$bhp = MBrgBhp::tableName();
		$terimadetail = TTerimaBhpDetail::tableName();
		$terima = TTerimaBhp::tableName();
		$query = self::find();
		$query->select(self::tableName().'.retur_bhp_id, kode, '.$terimadetail.'.terima_bhp_id, terimabhp_kode, tanggal, bhp_nm, harga, potongan, qty, '.$bhp.'.bhp_satuan, total_kembali,  '.self::tableName().'.deskripsi, '.$terimadetail.'.bhp_id');
		$query->join('JOIN', $terimadetail,$terimadetail.'.terima_bhpd_id = '.self::tableName().'.terima_bhpd_id');
		$query->join('JOIN', $terima,$terima.'.terima_bhp_id = '.$terimadetail.'.terima_bhp_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.$terimadetail.'.bhp_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.tanggal DESC, kode DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->kode)){
			$query->andWhere("kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->terimabhp_kode)){
			$query->andWhere("terimabhp_kode ILIKE '%".$this->terimabhp_kode."%'");
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
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->kode)){
			array_push($param['where'],"kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->terimabhp_kode)){
			array_push($param['where'],"terimabhp_kode ILIKE '%".$this->terimabhp_kode."%'");
		}
		return $param;
	}
	
	
	public static function getOptionListKasKecil()
    {
        $res = self::find()->where("kas_kecil_id IS NULL AND cancel_transaksi_id IS NULL")->orderBy('created_at DESC')->all();
		$return = [];
		if(!empty($res)){
			foreach($res as $i => $det){
				$return[$det->retur_bhp_id] = $det->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($det->tanggal);
			}
		}
        return $return;
    }
	
}
