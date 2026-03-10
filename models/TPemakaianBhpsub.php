<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pemakaian_bhpsub".
 *
 * @property integer $pemakaian_bhpsub_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $departement_id
 * @property string $status_approval
 * @property string $reason_approve
 * @property string $reason_reject
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MDepartement $departement
 * @property TPemakaianBhpsubDetail[] $tPemakaianBhpsubDetails
 */
class TPemakaianBhpsub extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal, $tgl_akhir, $departement_nama, $bhp_nm, $target_plan, $target_peruntukan, $bhp_group;
    public $qty, $harga_peritem, $dept_peruntukan, $asset_peruntukan, $keterangan, $total, $departement, $kode_asset;
    public $inventaris_nama, $bulan, $tahun;
    public static function tableName()
    {
        return 't_pemakaian_bhpsub';
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
            [['kode', 'departement_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['departement_id', 'created_by', 'updated_by'], 'integer'],
            [['reason_approve', 'reason_reject'], 'string'],
            [['kode', 'status_approval'], 'string', 'max' => 25],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pemakaian_bhpsub_id' => 'Pemakaian Bhpsub ID',
            'kode' => 'Kode',
            'tanggal' => 'Tanggal',
            'departement_id' => 'Departement ID',
            'status_approval' => 'Status Approval',
            'reason_approve' => 'Reason Approve',
            'reason_reject' => 'Reason Reject',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }
    public function getTPemakaianBhpsubDetails()
    {
        return $this->hasMany(TPemakaianBhpsubDetail::className(), ['pemakaian_bhpsub_id' => 'pemakaian_bhpsub_id']);
    }

    public function searchLaporan(){
		$bhp = MBrgBhp::tableName();
		$dept = MDepartement::tableName();
        $detail = TPemakaianBhpsubDetail::tableName();
        $terimabhp = TTerimaBhpSub::tableName();
        $inventaris = MInventaris::tableName();
		$query = self::find();
		$query->select( $detail.'.pemakaian_bhpsub_detail_id,
                        '.self::tableName().'.kode,
                        '.self::tableName().'.tanggal, 
                        '.$bhp.'.bhp_nm, 
                        '.$detail.'.qty, 
                        '.$detail.'.harga_peritem,
                        '.$terimabhp.'.target_plan, 
                        '.$terimabhp.'.target_peruntukan,
                        '.$dept.'.departement_nama as dept_peruntukan, 
                        '.$inventaris.'.inventaris_nama as asset_peruntukan,
                        '.$detail.'.keterangan,
                        ('.$detail.'.qty * '.$detail.'.harga_peritem) as total,
                        a.departement_nama as departement,
                        '.$inventaris.'.kode as kode_asset,
                        '.$inventaris.'.inventaris_id,
                        '.$bhp.'.bhp_group');
        $query->join('JOIN', $detail,$detail.'.pemakaian_bhpsub_id = '.self::tableName().'.pemakaian_bhpsub_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.$detail.'.bhp_id');
        $query->join('JOIN', $dept,$dept.'.departement_id = '.$detail.'.dept_peruntukan');
        $query->join('JOIN', $terimabhp,$terimabhp.'.terima_bhp_sub_id = '.$detail.'.terima_bhp_sub_id');
        $query->join('JOIN', $dept. ' a','a.departement_id = '.self::tableName().'.departement_id');
        $query->join('LEFT JOIN', $inventaris,$inventaris.'.inventaris_id = '.$detail.'.asset_peruntukan');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'tanggal DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere(self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm  ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->target_plan)){
			$query->andWhere("target_plan  ILIKE '%".$this->target_plan."%'");
		}
        if(!empty($this->target_peruntukan)){
			$query->andWhere("target_peruntukan  ILIKE '%".$this->target_peruntukan."%'");
		}
		if(!empty($this->departement_id)){
			$query->andWhere(self::tableName().".departement_id  = ".$this->departement_id);
		}
        if(!empty($this->asset_peruntukan)){
            if (is_array($this->asset_peruntukan)) {
                if (isset($this->asset_peruntukan)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->asset_peruntukan as $k) {
                        $subq.="inventaris_id = '".$k."' ";
                        if ($cn < count($this->asset_peruntukan)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("inventaris_id = '".$this->asset_peruntukan."'");
            }            
        }
		return $query;
	}

    public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		// $param['pk']= $param['table'].'.'.self::primaryKey()[0];
        $param['pk']='pemakaian_bhpsub_detail_id';
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
			array_push($param['where'],$param['table'].".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->target_plan)){
			array_push($param['where'],"target_plan = '".$this->target_plan."'");
		}
        if(!empty($this->target_peruntukan)){
			array_push($param['where'],"target_peruntukan = '".$this->target_peruntukan."'");
		}
		if(!empty($this->departement_id)){
			array_push($param['where'],$param['table'].".departement_id = ".$this->departement_id);
		}
        if(!empty($this->asset_peruntukan)){
            if (is_array($this->asset_peruntukan)) {
                if (isset($this->asset_peruntukan)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->asset_peruntukan as $k) {
                        $subq.="inventaris_id = '".$k."'";
                        if ($cn < count($this->asset_peruntukan)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"inventaris_id = '".$this->asset_peruntukan."'");
            }     
        }
		
		return $param;
	}

    public function searchLaporanRekapAsset(){
		$query = self::find();
		$query->select( " m_inventaris.inventaris_id, 
                          m_inventaris.kode, 
                          m_inventaris.inventaris_nama,
                          EXTRACT(MONTH  FROM t_pemakaian_bhpsub.tanggal) as bulan,
                          EXTRACT(YEAR FROM t_pemakaian_bhpsub.tanggal) as tahun,
                          target_plan, 
                          SUM(t_pemakaian_bhpsub_detail.qty * t_pemakaian_bhpsub_detail.harga_peritem) AS total" );
        $query->join('JOIN', 't_pemakaian_bhpsub_detail','t_pemakaian_bhpsub_detail.pemakaian_bhpsub_id = '.self::tableName().'.pemakaian_bhpsub_id');
        $query->join('JOIN', 't_terima_bhp_sub', 't_terima_bhp_sub.terima_bhp_sub_id = t_pemakaian_bhpsub_detail.terima_bhp_sub_id');
        $query->join('LEFT JOIN', 'm_inventaris', 'm_inventaris.inventaris_id = t_pemakaian_bhpsub_detail.asset_peruntukan');
        $query->groupBy("m_inventaris.inventaris_id, EXTRACT(MONTH  FROM t_pemakaian_bhpsub.tanggal), EXTRACT(YEAR FROM t_pemakaian_bhpsub.tanggal), target_plan");
        $query->orderBy(!empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
                    "m_inventaris.inventaris_id, 
                     EXTRACT(MONTH  FROM t_pemakaian_bhpsub.tanggal), 
                     EXTRACT(YEAR FROM t_pemakaian_bhpsub.tanggal),
                     (CASE 
                        WHEN target_plan = 'Preventive' THEN 1
                        WHEN target_plan = 'Corrective' THEN 2
                        WHEN target_plan = 'Project' THEN 3
                        ELSE 4 
                     END)");
        $query->where('t_pemakaian_bhpsub_detail.asset_peruntukan IS NOT NULL');
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere(self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->target_plan)){
			$query->andWhere("target_plan  ILIKE '%".$this->target_plan."%'");
		}
		if(!empty($this->departement_id)){
			$query->andWhere(self::tableName().".departement_id  = ".$this->departement_id);
		}
        if(!empty($this->asset_peruntukan)){
            if (is_array($this->asset_peruntukan)) {
                if (isset($this->asset_peruntukan)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->asset_peruntukan as $k) {
                        $subq.="inventaris_id = '".$k."' ";
                        if ($cn < count($this->asset_peruntukan)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        $query->andWhere($subq);
                    }
                }
            }else{
                $query->andWhere("inventaris_id = '".$this->asset_peruntukan."'");
            }            
        }
		return $query;
	}

    public function searchLaporanRekapAssetDt(){
		$searchLaporan = $this->searchLaporanRekapAsset();
		$param['table']= self::tableName();
		// $param['pk']= $param['table'].'.'.self::primaryKey()[0];
        $param['pk']='inventaris_id';
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
		$param['where'] = ['t_pemakaian_bhpsub_detail.asset_peruntukan IS NOT NULL'];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],$param['table'].".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->target_plan)){
			array_push($param['where'],"target_plan = '".$this->target_plan."'");
		}
		if(!empty($this->departement_id)){
			array_push($param['where'],$param['table'].".departement_id = ".$this->departement_id);
		}
        if(!empty($this->asset_peruntukan)){
            if (is_array($this->asset_peruntukan)) {
                if (isset($this->asset_peruntukan)) {
                    $subq=null;
                    $cn=1;
                    $subq.='(';
                    foreach ($this->asset_peruntukan as $k) {
                        $subq.="inventaris_id = '".$k."'";
                        if ($cn < count($this->asset_peruntukan)) {
                            $subq.=' OR ';
                        }
                        $cn++;
                    }
                    $subq.=')';
                    if (!empty($subq)) {
                        array_push($param['where'],$subq);
                    }
                }
            }else{
                array_push($param['where'],"inventaris_id = '".$this->asset_peruntukan."'");
            }     
        }
		
		return $param;
	}
}
