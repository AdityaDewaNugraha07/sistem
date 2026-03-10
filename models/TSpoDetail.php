<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spo_detail".
 *
 * @property integer $spod_id
 * @property integer $spo_id
 * @property integer $bhp_id
 * @property integer $spod_qty
 * @property double $spod_harga
 * @property string $spod_keterangan
 * @property string $spod_garansi
 *
 * @property MBrgBhp $bhp
 * @property TSpo $spo
 */
class TSpoDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $subtotal,$qty_kebutuhan,$satuan,$spod_harga_bantu;
	public $harga_display,$subtotal_display;
	public $bhp_nm,$bhp_group,$tgl_awal,$tgl_akhir,$bhp_satuan,$suplier_nm,$spo_kode,$spo_tanggal;
	public $sppd_qty,$sppd_id,$terimabhp_kode,$count,$qty,$status,$suplier_id,$tanggal_kirim,$tglterima;
	public $bhp_id_display, $suplier_id_display;
	public $cekfilepenawaran;
    public static function tableName()
    {
        return 't_spo_detail';
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
            [['spo_id', 'bhp_id', 'spod_qty'], 'required'],
            [['spo_id', 'bhp_id'], 'integer'],
            [['spod_harga'], 'number'],
			[['spod_garansi'], 'boolean'],
            [['spod_keterangan'], 'string'],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['spo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpo::className(), 'targetAttribute' => ['spo_id' => 'spo_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spod_id' => Yii::t('app', 'Spod'),
                'spo_id' => Yii::t('app', 'Spo'),
                'bhp_id' => Yii::t('app', 'Bhp'),
                'spod_qty' => Yii::t('app', 'Spod Qty'),
                'spod_harga' => Yii::t('app', 'Spod Harga'),
                'spod_keterangan' => Yii::t('app', 'Spod Keterangan'),
				'spod_garansi' => Yii::t('app', 'Spod Garansi'),
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
    public function getSpo()
    {
        return $this->hasOne(TSpo::className(), ['spo_id' => 'spo_id']);
    }
	
	public function searchLaporan() {
		$bhp = MBrgBhp::tableName();
		$spo = TSpo::tableName();
		$suplier = MSuplier::tableName();
		$terimabhp = TTerimaBhp::tableName();
		$map_penawaran_bhp = MapPenawaranBhp::tableName();
		$penawaran_bhp = TPenawaranBhp::tableName();
		$query = self::find();

		//                                       0,         1,           2,                   3,      4,        5,          6,          7,          8,               9,                  10,             11,                  12,                     13
		/*$query->select(self::tableName().".spod_id,  spo_kode, spo_tanggal, t_spo_detail.bhp_id, bhp_nm, spod_qty, bhp_satuan, spod_harga, suplier_nm, spod_keterangan, t_spo.terima_bhp_id, terimabhp_kode, t_spo.tanggal_kirim, t_terima_bhp.tglterima
						, (select m_suplier.suplier_nm::text 
							   from map_penawaran_bhp
							   left join t_penawaran_bhp on t_penawaran_bhp.penawaran_bhp_id = map_penawaran_bhp.penawaran_bhp_id
							   left join m_suplier on m_suplier.suplier_id = t_penawaran_bhp.suplier_id
							   where map_penawaran_bhp.spod_id = t_spo_detail.spod_id limit 1) as suplier_nama_ 
						, (select t_penawaran_bhp.harga_satuan::text
							   from map_penawaran_bhp
							   left join t_penawaran_bhp on t_penawaran_bhp.penawaran_bhp_id = map_penawaran_bhp.penawaran_bhp_id
							   left join m_suplier on m_suplier.suplier_id = t_penawaran_bhp.suplier_id
							   where map_penawaran_bhp.spod_id = t_spo_detail.spod_id limit 1) as harga_satuan_
						");
		*/
		$query->select(self::tableName().".spod_id,  
										spo_kode, 
										spo_tanggal, 
										t_spo_detail.bhp_id, 
										bhp_nm, 
										spod_qty, 
										bhp_satuan, 
										spod_harga,
										(spod_qty*spod_harga) as subtotal, 
										suplier_nm, 
										spod_keterangan, 
										t_spo.terima_bhp_id, 
										terimabhp_kode, 
										t_spo.tanggal_kirim, 
										t_terima_bhp.tglterima, 
										t_terima_bhp.terima_bhp_id,
										spod_garansi");

		$query->join('JOIN', $spo,$spo.'.spo_id = '.self::tableName().'.spo_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.self::tableName().'.bhp_id');
		$query->join('JOIN', $suplier,$suplier.'.suplier_id = '.$spo.'.suplier_id');
		$query->join('LEFT JOIN', $terimabhp,$terimabhp.'.terima_bhp_id = '.$spo.'.terima_bhp_id');
		$query->andWhere(" t_spo.cancel_transaksi_id IS NULL ");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			$spo.'.spo_tanggal DESC, suplier_nm' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("spo_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm  ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->bhp_group)){
			$query->andWhere("bhp_group  ILIKE '%".$this->bhp_group."%'");
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
		$param['where'] = ['t_spo.cancel_transaksi_id IS NULL'];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"spo_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_group)){
			array_push($param['where'],"bhp_group = '".$this->bhp_group."'");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		return $param;
	}
	
	public static function tarikSppToSpo($supplier_id,$bhp_id){
//		$sqlspp = "SELECT SUM(sppd_qty) AS sppsum FROM t_spp_detail WHERE bhp_id = ".$bhp_id." AND suplier_id = ".$supplier_id;
//		$totalspp = \Yii::$app->db->createCommand($sqlspp)->queryOne()['sppsum'];
		
		$sqlspo = "SELECT sum(spod_qty) AS sposum FROM t_spo_detail JOIN t_spo ON t_spo.spo_id = t_spo_detail.spo_id WHERE bhp_id = ".$bhp_id." AND suplier_id = ".$supplier_id." AND cancel_transaksi_id IS NULL";
		$totalspo = \Yii::$app->db->createCommand($sqlspo)->queryOne()['sposum'];
		
		$sql = "SELECT * FROM t_spp_detail WHERE bhp_id = ".$bhp_id." AND suplier_id = ".$supplier_id;
		$mod = \Yii::$app->db->createCommand($sql)->queryAll();
		
		$return['ket'] = [];
		if(count($mod)>0){
			$totalmoving = 0;
			foreach($mod as $i => $spp){
				$totalmoving += $spp['sppd_qty'];
				if($totalmoving > $totalspo){
					$return['ket'][] = $spp['sppd_ket'];
					$return['sppd_id'][] = $spp['sppd_id'];
				}
			}
		}
		return $return;
	}
	
	public function searchTotalBeli() {
		$query = self::find();
		$query->select('t_spo_detail.bhp_id, m_brg_bhp.bhp_nm, count(spod_id) as count, sum(spod_qty) as qty');
		$query->join('JOIN', 'm_brg_bhp','m_brg_bhp.bhp_id = t_spo_detail.bhp_id');
		$query->groupBy("t_spo_detail.bhp_id, m_brg_bhp.bhp_nm");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'count DESC' );
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm  ILIKE '%".$this->bhp_nm."%'");
		}
		return $query;
	}
	public function searchTotalBeliDt() {
		$searchLaporan = $this->searchTotalBeli();
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
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		return $param;
	}
	
	public function searchAllSpoByItem() {
		$query = self::find();
		$query->select('t_spo.spo_id, t_spo.spo_kode, m_brg_bhp.bhp_nm, t_spo_detail.spod_qty, m_brg_bhp.bhp_satuan, t_spo_detail.bhp_id, t_spo_detail.spod_garansi');
		$query->join('JOIN', 't_spo','t_spo.spo_id = t_spo_detail.spo_id');
		$query->join('JOIN', 'm_brg_bhp','m_brg_bhp.bhp_id = t_spo_detail.bhp_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			't_spo.spo_id DESC' );
		if(!empty($this->bhp_id)){
			$query->andWhere("t_spo_detail.bhp_id  = ".$this->bhp_id);
		}
		return $query;
	}
	public function searchAllSpoByItemDt() {
		$searchLaporan = $this->searchAllSpoByItem();
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
		if(!empty($this->bhp_id)){
			array_push($param['where'],"t_spo_detail.bhp_id = ".$this->bhp_id);
		}
		return $param;
	}
}
