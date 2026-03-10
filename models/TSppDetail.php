<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spp_detail".
 *
 * @property integer $sppd_id
 * @property integer $spp_id
 * @property integer $bhp_id
 * @property integer $sppd_qty
 * @property string $sppd_ket
 * @property integer $suplier_id
 *
 * @property MapSpbDetailSppDetail[] $mapSpbDetailSppDetails
 * @property MBrgBhp $bhp
 * @property MSuplier $suplier
 * @property TSpp $spp
 */ 
class TSppDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $qty_kebutuhan, $current_stock, $bhp_nama, $satuan, $qty_terpenuhi;
	public $bhp_nm,$bhp_group,$tgl_awal,$tgl_akhir,$bhp_satuan,$departement_nama,$spp_kode,$spp_tanggal;
	public $keterangan,$status,$spld_qty;
    public static function tableName()
    {
        return 't_spp_detail';
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
            [['spp_id', 'bhp_id', 'sppd_qty'], 'required'],
            [['spp_id', 'bhp_id', 'suplier_id'], 'integer'],
            [['sppd_ket','status_closed'], 'string'],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['spp_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpp::className(), 'targetAttribute' => ['spp_id' => 'spp_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sppd_id' => Yii::t('app', 'Sppd'),
            'spp_id' => Yii::t('app', 'Spp'),
            'bhp_id' => Yii::t('app', 'Bhp'),
            'sppd_qty' => Yii::t('app', 'Sppd Qty'),
            'sppd_ket' => Yii::t('app', 'Sppd Ket'),
            'suplier_id' => Yii::t('app', 'Suplier'),
            'status_closed' => Yii::t('app', 'Closed'),
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
		$dept = MDepartement::tableName();
		$query = self::find();
		$query->select(self::tableName().'.sppd_id,  spp_kode, spp_tanggal, bhp_nm, sppd_qty, bhp_satuan, departement_nama');
		$query->join('JOIN', $spp,$spp.'.spp_id = '.self::tableName().'.spp_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.self::tableName().'.bhp_id');
		$query->join('JOIN', $dept,$dept.'.departement_id = '.$spp.'.departement_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			$spp.'.spp_tanggal DESC, departement_nama' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("spp_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
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
		return $param;
	}
	
	public function getQtyTerbeli(){
		$return['qty'] = 0;
		$return['info_terima'] = "";
		// 2020-06-30 beri variable qty_retur
		$return['qty_retur'] = 0;		
//		$sql = "SELECT * FROM map_spp_detail_reff 
//				JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhpd_id = map_spp_detail_reff.terima_bhpd_id
//				JOIN t_terima_bhp ON t_terima_bhp.terima_bhp_id = t_terima_bhp_detail.terima_bhp_id
//				WHERE sppd_id = ".$this->sppd_id." AND t_terima_bhp.cancel_transaksi_id IS NULL";
//		$modMap = Yii::$app->db->createCommand($sql)->queryAll();
//		if(count($modMap)>0){
//			foreach($modMap as $i => $map){
//				$modTerimaDetail = TTerimaBhpDetail::findOne($map['terima_bhpd_id']);
//				$qty += (!empty($modTerimaDetail->terima_bhpd_id)?$modTerimaDetail->terimabhpd_qty:0);
//			}
//		}
		
		$sql0 = "SELECT * FROM map_spp_detail_reff
				 LEFT JOIN t_spl ON map_spp_detail_reff.reff_no = t_spl.spl_kode
				 LEFT JOIN t_spo ON map_spp_detail_reff.reff_no = t_spo.spo_kode
				 WHERE sppd_id = ".$this->sppd_id;
		$modsql0 = Yii::$app->db->createCommand($sql0)->queryAll();
		$where = "";
		if(count($modsql0)>0){
			foreach($modsql0 as $i => $res){
				if(!empty($res['spl_id'])){
					$where = "AND spl_id = ".$res['spl_id'];
				}
				if(!empty($res['spo_id'])){
					$where = "AND spo_id = ".$res['spo_id'];
				}
				if(!empty($where)){
					if($res['terima_bhpd_id']){
						$sql = "SELECT * from t_terima_bhp 
								JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhp_id = t_terima_bhp.terima_bhp_id
								WHERE bhp_id = ".$this->bhp_id." AND t_terima_bhp.cancel_transaksi_id IS NULL ".$where;
//                      $sql = "SELECT * from t_terima_bhp 
//								JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhp_id = t_terima_bhp.terima_bhp_id
//                              join map_spp_detail_reff on map_spp_detail_reff.terima_bhpd_id=t_terima_bhp_detail.terima_bhpd_id
//								WHERE map_spp_detail_reff.sppd_id = ".$res['sppd_id']." AND t_terima_bhp.cancel_transaksi_id IS NULL ";
						$modMap = Yii::$app->db->createCommand($sql)->queryAll();
						if(count($modMap)>0){
							foreach($modMap as $i => $map){
								$return['info_terima'][] = "<a onclick='infoTBP(\"".$map['terima_bhp_id']."\",\"".$map['bhp_id']."\")'>".$map['terimabhp_kode']."</a>";
								$return['qty'] += (!empty($map['terimabhpd_qty'])?$map['terimabhpd_qty']:0);
								$sqlRetur = "SELECT * FROM t_retur_bhp WHERE terima_bhpd_id = ".$map['terima_bhpd_id'];
								$modRetur = Yii::$app->db->createCommand($sqlRetur)->queryOne();
								if(!empty($modRetur)){
									// 2020-06-30 jumlahkan qty_retur
									$return['qty_retur'] += (!empty($modRetur['qty']) ? $modRetur['qty'] : 0);
									$return['info_terima'][] .= '<a onclick="infoReturBHP('.$modRetur['retur_bhp_id'].');" class="blue-steel" style="font-size: 1rem">'.$modRetur['kode'].'</a>';
								}
							}
						}
					}
				}
			}
		}
//		if(!empty($where)){
//			$sql = "SELECT * from t_terima_bhp 
//				JOIN t_terima_bhp_detail ON t_terima_bhp_detail.terima_bhp_id = t_terima_bhp.terima_bhp_id
//				WHERE bhp_id = ".$this->bhp_id." AND t_terima_bhp.cancel_transaksi_id IS NULL ".$where;
//			$modMap = Yii::$app->db->createCommand($sql)->queryAll();
//			if(count($modMap)>0){
//				foreach($modMap as $i => $map){
//					$return['info_terima'][] = "<a onclick='infoTerima(\"".$map['terimabhp_kode']."\",\"".$map['bhp_id']."\")'>".$map['terimabhp_kode']."</a>";
//					$return['qty'] += (!empty($map['terimabhpd_qty'])?$map['terimabhpd_qty']:0);
//				}
//			}
//		}
		if(!empty($return['info_terima'])){
			$return['info_terima'] = implode("<br>", $return['info_terima']);
		}
		return $return;
	}
	public function getStatusSppDetail(){
		if(empty($this->status_closed)){
			if(!empty($this->spp->cancel_transaksi_id)){
				$status = '<span class="label label-danger" style="font-size: 1.0rem;">'.TCancelTransaksi::STATUS_ABORTED.'</span>';
			}else{
				// 2020-06-30 masukkan variable qty_retur
				if (($this->QtyTerbeli['qty'] - $this->QtyTerbeli['qty_retur']) == 0) {
					$status = '<span onclick="closeSPP('.$this->sppd_id.')" class="label label-info" style="font-size: 1.0rem; cursor: pointer;">TO-DO</span>';
				} else {
					if (($this->QtyTerbeli['qty'] - $this->QtyTerbeli['qty_retur']) < $this->sppd_qty){
						$status = '<span onclick="closeSPP('.$this->sppd_id.')" class="label label-warning" style="font-size: 1.0rem; cursor: pointer;">PARTIALLY</span>';
					} else {
						$status = '<span class="label label-success" style="font-size: 1.0rem;">COMPLETE</span>';
					}
				}
			}
		}else{
			$status = '<span onclick="closeSPP('.$this->sppd_id.')" class="label label-default" style="font-size: 1.0rem; cursor: pointer;">CLOSED</span>';
		}
		return $status;
	}
}
