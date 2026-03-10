<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_bhp_detail".
 *
 * @property integer $terima_bhpd_id
 * @property integer $terima_bhp_id
 * @property integer $bhp_id
 * @property double $terimabhpd_qty
 * @property double $terimabhpd_harga
 * @property double $terimabhpd_diskon
 * @property string $terimabhpd_keterangan
 * @property string $terimabhpd_status
 * @property double $ppn_peritem
 * @property integer $suplier_id
 * @property double $pph_peritem
 *
 * @property MapSppDetailReff[] $mapSppDetailReffs
 * @property MBrgBhp $bhp
 * @property MSuplier $suplier
 * @property TTerimaBhp $terimaBhp
 */ 
class TTerimaBhpDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $subtotal,$diskon_rp,$harga_estimasi,$qty_in,$qty_out,$totalbykelompokbarang;
	public $terimabhpd_harga_display,$subtotal_display;
	public $bhp_nm,$bhp_group,$tgl_awal,$tgl_akhir,$bhp_satuan,$suplier_nm,$terimabhp_kode,$tglterima,$total_pbbkb;
	public $spod_id,$spld_id,$bhp_kode,$is_ppn_peritem,$is_pph_peritem,$spo_id,$ppn_nominal;
	public $keterangan,$npwp,$per_tanggal,$terimabhpd_qty_old;
    public static function tableName()
    {
        return 't_terima_bhp_detail';
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
            [['terima_bhp_id', 'bhp_id', 'terimabhpd_qty', 'terimabhpd_harga'], 'required'],
            [['terima_bhp_id', 'bhp_id', 'suplier_id'], 'integer'],
			[['terimabhpd_harga', 'pph_peritem'], 'safe'],
            [['terimabhpd_qty', 'terimabhpd_diskon', 'ppn_peritem'], 'number'],
            [['terimabhpd_keterangan'], 'string'],
            [['terimabhpd_status'], 'string', 'max' => 30],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['terima_bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaBhp::className(), 'targetAttribute' => ['terima_bhp_id' => 'terima_bhp_id']],
        ];
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'terima_bhpd_id' => Yii::t('app', 'Terima Bhpd'),
			'terima_bhp_id' => Yii::t('app', 'Terima Bhp'),
			'bhp_id' => Yii::t('app', 'Bhp'),
			'terimabhpd_qty' => Yii::t('app', 'Terimabhpd Qty'),
			'terimabhpd_harga' => Yii::t('app', 'Terimabhpd Harga'),
			'terimabhpd_diskon' => Yii::t('app', 'Terimabhpd Diskon'),
			'terimabhpd_keterangan' => Yii::t('app', 'Terimabhpd Keterangan'),
			'terimabhpd_status' => Yii::t('app', 'Terimabhpd Status'),
			'ppn_peritem' => Yii::t('app', 'Ppn Peritem'),
			'suplier_id' => Yii::t('app', 'Suplier'),
			'pph_peritem' => Yii::t('app', 'Pph Peritem'),
        ];
    }

    public function getMapSppDetailReffs()
    {
        return $this->hasMany(MapSppDetailReff::className(), ['terima_bhpd_id' => 'terima_bhpd_id']);
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
    public function getTerimaBhp()
    {
        return $this->hasOne(TTerimaBhp::className(), ['terima_bhp_id' => 'terima_bhp_id']);
    } 
	
	public function searchLaporan() {
		$bhp = MBrgBhp::tableName();
		$terima = TTerimaBhp::tableName();
		$suplier = MSuplier::tableName();
		$query = self::find();
		$query->select(self::tableName().'.terima_bhpd_id,  
						terimabhp_kode, 
						tglterima, 
						suplier_nm, 
						bhp_kode, 
						bhp_nm, 
						bhp_satuan, 
						terimabhpd_qty, 
						terimabhpd_harga, 
						(terimabhpd_qty*terimabhpd_harga)*0.1 AS ppn, 
						t_terima_bhp.spo_id, 
						(terimabhpd_qty * terimabhpd_harga) as total,
						terimabhpd_keterangan, 
						ppn_nominal, 
						pph_peritem, 
						m_default_value.name_en as mata_uang,
						t_terima_bhp.total_pbbkb as total_pbbkb,bhp_id');
		$query->join('JOIN', $terima,$terima.'.terima_bhp_id = '.self::tableName().'.terima_bhp_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.self::tableName().'.bhp_id');
		$query->join('LEFT JOIN', "t_spo",'t_spo.spo_id = '.$terima.'.spo_id');
		$query->join('LEFT JOIN', "m_default_value",'m_default_value.value = t_spo.mata_uang');
		$query->join('LEFT JOIN', $suplier,$suplier.'.suplier_id = '.self::tableName().'.suplier_id');
		$query->andWhere(" $terima.cancel_transaksi_id IS NULL ");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			$terima.'.tglterima DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tglterima BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->terimabhp_kode)){
			$query->andWhere("terimabhp_kode  ILIKE '%".$this->terimabhp_kode."%'");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm  ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->bhp_group)){
			$query->andWhere("bhp_group  ILIKE '%".$this->bhp_group."%'");
		}
		if(!empty($this->suplier_id)){
			$query->andWhere(self::tableName().".suplier_id  = ".$this->suplier_id."");
		}
        if(!empty($this->terimabhpd_keterangan)){
			$query->andWhere("terimabhpd_keterangan  ILIKE '%".$this->terimabhpd_keterangan."%'");
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
		array_push($param['where'],"t_terima_bhp.cancel_transaksi_id IS NULL");
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tglterima BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->terimabhp_kode)){
			array_push($param['where'],"terimabhp_kode = '".$this->terimabhp_kode."'");
		}
		if(!empty($this->bhp_group)){
			array_push($param['where'],"bhp_group = '".$this->bhp_group."'");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->suplier_id)){
			array_push($param['where'],self::tableName().".suplier_id = ".$this->suplier_id."");
		}
        if(!empty($this->terimabhpd_keterangan)){
			array_push($param['where'],"terimabhpd_keterangan ILIKE '%".$this->terimabhpd_keterangan."%'");
		}
		return $param;
	}
	
//	SUM(total_pembayaran)
	public function searchLaporanTagihanSuplier() {
		$query = self::find();
		$select1 = "SUM( CASE 
						WHEN (ppn_nominal > 0 AND COALESCE(pph_peritem,0) = 0) 
							THEN terimabhpd_qty * ((terimabhpd_harga * 0.1) + terimabhpd_harga) + COALESCE(total_pbbkb,0)
						WHEN (pph_peritem > 0 AND ppn_nominal = 0) 
							THEN (terimabhpd_qty * terimabhpd_harga) - COALESCE(pph_peritem,0) + COALESCE(total_pbbkb,0)
						WHEN (ppn_nominal > 0 AND COALESCE(pph_peritem,0) > 0) 
							THEN terimabhpd_qty * ((terimabhpd_harga * 0.1) + terimabhpd_harga) - COALESCE(pph_peritem,0) + COALESCE(total_pbbkb,0)
						ELSE terimabhpd_qty * terimabhpd_harga END )";
		$select2 = "COALESCE( (SELECT COALESCE(SUM(total_pembayaran + total_potongan)) FROM t_voucher_pengeluaran WHERE cancel_transaksi_id IS NULL AND status_bayar = 'PAID' AND (tipe = 'Pembelian BHP' OR tipe = 'Pembayaran DP BHP') AND t_voucher_pengeluaran.suplier_id = t_terima_bhp.suplier_id ".((!empty($this->per_tanggal))?"AND tanggal_bayar <= '".$this->per_tanggal."'":"")."),0 )";
		$query->select(['t_terima_bhp.suplier_id', 'm_suplier.suplier_nm',
						"{$select1} AS totaltagihan" ,
						"{$select2} AS paid",
						"(	{$select1} - {$select2} ) AS hutang"]);
		$query->join('JOIN', "t_terima_bhp",'t_terima_bhp.terima_bhp_id = t_terima_bhp_detail.terima_bhp_id');
		$query->join('LEFT JOIN', "m_suplier",'m_suplier.suplier_id = t_terima_bhp.suplier_id');
		$query->groupBy("t_terima_bhp.suplier_id, m_suplier.suplier_nm");  
                $query->having("{$select1} - {$select2} > 0");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'm_suplier.suplier_nm ASC' );
		$query->andWhere("cancel_transaksi_id IS NULL AND spo_id IS NOT NULL");
		if(!empty($this->per_tanggal)){
			$query->andWhere("tglterima <= '".$this->per_tanggal."'");
		}
		if(!empty($this->suplier_id)){
			$query->andWhere("t_terima_bhp.suplier_id = ".$this->suplier_id);
		}
                
//		echo "<pre>";
//		print_r($query->createCommand()->rawSql);
//		exit;
		return $query;
	}
	
	public function searchLaporanTagihanSuplierDt() {
		$searchLaporan = $this->searchLaporanTagihanSuplier();
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
		$param['where'] = ["cancel_transaksi_id IS NULL AND spo_id IS NOT NULL"];
		if(!empty($this->per_tanggal)){
			array_push($param['where'],"tglterima <= '".$this->per_tanggal."'");
		}
		if(!empty($this->suplier_id)){
			array_push($param['where'],"t_terima_bhp.suplier_id = ".$this->suplier_id);
		}
                if(!empty($searchLaporan->having)){
			$param['having'] = "HAVING ".$searchLaporan->having;
		}
		return $param;
	}
}
