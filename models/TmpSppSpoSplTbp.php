<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tmp_spp_spo_spl_tbp".
 *
 * @property integer $tmp_id
 * @property integer $sppd_id
//  * @property integer $spp_id
//  * @property integer $bhp_id
//  * @property double $sppd_qty
 * @property double $terimabhpd_qty
//  * @property string $sppd_ket
//  * @property integer $suplier_id
//  * @property string $status_closed
 * @property string $spbd_id
 * @property string $reff_no
 * @property string $terima_bhpd_id
 * 
 * @property MapSpbDetailSppDetail[] $mapSpbDetailSppDetails
 * @property MBrgBhp $bhp
 * @property MSuplier $suplier
 * @property TSpp $spp
 */
class TmpSppSpoSplTbp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $qty_kebutuhan, $current_stock, $bhp_nama, $satuan, $qty_terpenuhi, $status_pembelian,$bhp_id,$sppd_ket,$status_pemenuhan;
	public $bhp_nm,$bhp_group,$tgl_awal,$tgl_akhir,$bhp_satuan,$departement_nama,$spp_kode,$spp_tanggal;
	public $keterangan,$status,$spld_qty,$status_closed,$sppd_qty,$suplier_nm,$suplier_id;

    public static function tableName()
    {
        return 'tmp_spp_spo_spl_tbp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
		//, 'spp_id', 'bhp_id', 'suplier_id'
		//'sppd_qty', 
		//'sppd_ket', 'status_closed', 
        return [
            [['sppd_id'], 'integer'],
            [['terimabhpd_qty'], 'number'],
            [['spbd_id'], 'string'],
            [['reff_no', 'terima_bhpd_id'], 'string', 'max' => 225],
            // [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            // [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            // [['spp_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpp::className(), 'targetAttribute' => ['spp_id' => 'spp_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tmp_id' => 'Tmp ID',
            'sppd_id' => 'Sppd',
            // 'spp_id' => 'Spp',
            // 'bhp_id' => 'Bhp',
            // 'sppd_qty' => 'Sppd Qty',
            'terimabhpd_qty' => 'Terimabhpd Qty',
            // 'sppd_ket' => 'Sppd Ket',
            // 'suplier_id' => 'Suplier',
            // 'status_closed' => 'Status Closed',
            'spbd_id' => 'Spbd',
            'reff_no' => 'Reff No',
            'terima_bhpd_id' => 'Terima Bhpd',
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
    public function getSpp()
    {
        return $this->hasOne(TSpp::className(), ['spp_id' => 'spp_id']);
    }
	
	public function searchLaporan() {
		$bhp = MBrgBhp::tableName();
		$spp = TSpp::tableName();
		$sppdetail = TSppDetail::tableName();
		$dept = MDepartement::tableName();
		// $terimaDetail = TTerimaBhpDetail::tableName();
		// $terima = TTerimaBhp::tableName();
		// $mapPenawaran = MapPenawaranBhp::tableName();
		// $penawaran = TPenawaranBhp::tableName();
		$suplier = MSuplier::tableName();

		$query = self::find();
		// $query->select(self::tableName().'.sppd_id, spp_kode, spp_tanggal, bhp_nm, sppd_qty, 
		// 									terimabhpd_qty, bhp_satuan, departement_nama,reff_no,
		// 									status_closed,'.self::tableName().'.bhp_id,sppd_ket,terima_bhpd_id,spbd_id,'.self::tableName().'.suplier_id,suplier_nm');								
		// $query->join('JOIN', $spp,$spp.'.spp_id = '.self::tableName().'.spp_id');
		// $query->join('JOIN', $bhp,$bhp.'.bhp_id = '.self::tableName().'.bhp_id');
		// $query->join('JOIN', $dept,$dept.'.departement_id = '.$spp.'.departement_id');
		// $query->join('LEFT JOIN', $suplier,$suplier.'.suplier_id = '.self::tableName().'.suplier_id');

		$query->select(self::tableName().'.sppd_id, 
											spp_kode, 
											spp_tanggal, 
											bhp_nm, 
											'.$sppdetail.'.sppd_qty, 
											terimabhpd_qty, 
											bhp_satuan, 
											departement_nama,
											reff_no,
											'.$sppdetail.'.status_closed,
											'.$sppdetail.'.bhp_id,
											'.$sppdetail.'.sppd_ket,
											terima_bhpd_id,
											spbd_id,
											'.$sppdetail.'.suplier_id,
											suplier_nm');
		$query->join('JOIN', $sppdetail,$sppdetail.'.sppd_id = '.self::tableName().'.sppd_id');									
		$query->join('JOIN', $spp,$spp.'.spp_id = '.$sppdetail.'.spp_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.$sppdetail.'.bhp_id');
		$query->join('JOIN', $dept,$dept.'.departement_id = '.$spp.'.departement_id');
		$query->join('LEFT JOIN', $suplier,$suplier.'.suplier_id = '.$sppdetail.'.suplier_id');

		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			$spp.'.spp_tanggal DESC, departement_nama' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("spp_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm  ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->spp_kode)){
			$query->andWhere("spp_kode  ILIKE '%".$this->spp_kode."%'");
		}
		if(!empty($this->bhp_group)){
			$query->andWhere("bhp_group = '".$this->bhp_group."'");
		}
		if(!empty($this->status_closed)){
			if($this->status_closed == "Open"){
				$query->andWhere("t_spp_detail.status_closed is null");
			}else if($this->status_closed == "Closed"){
				$query->andWhere("t_spp_detail.status_closed is not null");
			}
		}
		if(!empty($this->status_pembelian)){
			if($this->status_pembelian == "Telah Diproses"){
				$query->andWhere("reff_no is not null");
			}else if($this->status_pembelian == "Belum Diproses"){
				$query->andWhere("reff_no is null");
			}
		}
		if(!empty($this->status_pemenuhan)){
			if($this->status_pemenuhan == "Complete"){
				$query->andWhere("sppd_qty = terimabhpd_qty");
			}else if($this->status_pemenuhan == "Partial"){
				$query->andWhere("sppd_qty > terimabhpd_qty and terimabhpd_qty > 0");
			}else if($this->status_pemenuhan == "-"){
				$query->andWhere("terimabhpd_qty = 0");
			}
		}
		if(!empty($this->suplier_id)){
			$query->andWhere("t_spp_detail.suplier_id = '".$this->suplier_id."'");
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
			array_push($param['where'],"spp_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_group)){
			array_push($param['where'],"bhp_group = '".$this->bhp_group."'");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->spp_kode)){
			array_push($param['where'],"spp_kode ILIKE '%".$this->spp_kode."%'");
		}
		if(!empty($this->status_closed)){
			if($this->status_closed == "Open"){
				array_push($param['where'],"t_spp_detail.status_closed is null");
			}else if($this->status_closed == "Closed"){
				array_push($param['where'],"t_spp_detail.status_closed is not null");
			}
		}
		if(!empty($this->status_pembelian)){
			if($this->status_pembelian == "Telah Diproses"){
				array_push($param['where']," reff_no is not null");
			}else if($this->status_pembelian == "Belum Diproses"){
				array_push($param['where']," reff_no is null");
			}
		}
		if(!empty($this->status_pemenuhan)){
			if($this->status_pemenuhan == "Complete"){
				array_push($param['where']," sppd_qty = terimabhpd_qty ");
			}else if($this->status_pemenuhan == "Partial"){
				array_push($param['where']," sppd_qty > terimabhpd_qty and terimabhpd_qty > 0");
			}else if($this->status_pemenuhan == "-"){
				array_push($param['where']," terimabhpd_qty = 0");
			}
		}
		if(!empty($this->suplier_id)){
			array_push($param['where'],"t_spp_detail.suplier_id = '".$this->suplier_id."'");
		}
		return $param;
	}
}
