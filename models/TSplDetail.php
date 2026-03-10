<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spl_detail".
 *
 * @property integer $spld_id
 * @property integer $spl_id
 * @property integer $bhp_id
 * @property integer $spld_qty
 * @property double $spld_harga_estimasi
 * @property double $spld_harga_realisasi
 * @property string $spld_keterangan
 * @property integer $suplier_id
 * @property integer $sppd_id
 *
 * @property MBrgBhp $bhp
 * @property MSuplier $suplier
 * @property TSpl $spl
 * @property TSppDetail $sppd
 */ 
class TSplDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $subtotal,$bhp_nm,$bhp_satuan,$tgl_awal,$tgl_akhir,$bhp_group,$spl_kode,$spl_tanggal,$suplier_nm;
	public $count,$qty;
    public static function tableName()
    {
        return 't_spl_detail';
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
            [['spl_id', 'bhp_id', 'spld_qty'], 'required'],
            [['spl_id', 'bhp_id', 'suplier_id', 'sppd_id'], 'integer'],
            [['spld_harga_estimasi', 'spld_harga_realisasi'], 'number'],
            [['spld_keterangan'], 'string'],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['spl_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpl::className(), 'targetAttribute' => ['spl_id' => 'spl_id']],
            [['sppd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSppDetail::className(), 'targetAttribute' => ['sppd_id' => 'sppd_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'spld_id' => Yii::t('app', 'Spld'),
			'spl_id' => Yii::t('app', 'Spl'),
			'bhp_id' => Yii::t('app', 'Bhp'),
			'spld_qty' => Yii::t('app', 'Spld Qty'),
			'spld_harga_estimasi' => Yii::t('app', 'Spld Harga Estimasi'),
			'spld_harga_realisasi' => Yii::t('app', 'Spld Harga Realisasi'),
			'spld_keterangan' => Yii::t('app', 'Spld Keterangan'),
			'suplier_id' => Yii::t('app', 'Suplier'),
			'sppd_id' => Yii::t('app', 'Sppd'),
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
    public function getSpl()
    {
        return $this->hasOne(TSpl::className(), ['spl_id' => 'spl_id']);
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getSppd()
    {
        return $this->hasOne(TSppDetail::className(), ['sppd_id' => 'sppd_id']);
    } 
	
	
	public function searchLaporan() {
		$query = self::find();
		$query->select(self::tableName().'.spld_id,  spl_kode, spl_tanggal, t_spl_detail.bhp_id, bhp_nm, spld_qty, bhp_satuan, suplier_nm, spld_keterangan');
		$query->join('JOIN', 't_spl','t_spl.spl_id = '.self::tableName().'.spl_id');
		$query->join('JOIN', 'm_brg_bhp','m_brg_bhp.bhp_id = '.self::tableName().'.bhp_id');
		$query->join('LEFT JOIN', 'm_suplier','m_suplier.suplier_id = t_spl_detail.suplier_id');
		$query->andWhere(" t_spl.cancel_transaksi_id IS NULL ");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			't_spl.spl_tanggal DESC, suplier_nm' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_spl.spl_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
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
		$param['where'] = ['t_spl.cancel_transaksi_id IS NULL'];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_spl.spl_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_group)){
			array_push($param['where'],"bhp_group = '".$this->bhp_group."'");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		return $param;
	}
	
	public function searchTotalBeli() {
		$query = TSplDetail::find();
		$query->select('t_spl_detail.bhp_id, m_brg_bhp.bhp_nm, count(spld_id) as count, sum(spld_qty) as qty');
		$query->join('JOIN', 'm_brg_bhp','m_brg_bhp.bhp_id = t_spl_detail.bhp_id');
		$query->groupBy("t_spl_detail.bhp_id, m_brg_bhp.bhp_nm");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'count DESC' );
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm  ILIKE '%".$this->bhp_nm."%'");
		}
		return $query;
	}
	public function searchTotalBeliDt() {
		$searchLaporan = $this->searchTotalBeli();
		$param['table']= "t_spl_detail";
		$param['pk']= $param['table'].'.spld_id';
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
	
	public function searchAllSplByItem() {
		$query = self::find();
		$query->select('t_spl.spl_id, t_spl.spl_kode, m_brg_bhp.bhp_nm, t_spl_detail.spld_qty, m_brg_bhp.bhp_satuan, t_spl_detail.bhp_id');
		$query->join('JOIN', 't_spl','t_spl.spl_id = t_spl_detail.spl_id');
		$query->join('JOIN', 'm_brg_bhp','m_brg_bhp.bhp_id = t_spl_detail.bhp_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			't_spl.spl_id DESC' );
		if(!empty($this->bhp_id)){
			$query->andWhere("t_spl_detail.bhp_id  = ".$this->bhp_id);
		}
		return $query;
	}
	public function searchAllSplByItemDt() {
		$searchLaporan = $this->searchAllSplByItem();
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
			array_push($param['where'],"t_spl_detail.bhp_id = ".$this->bhp_id);
		}
		return $param;
	}
	
}
