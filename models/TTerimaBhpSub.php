<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_bhp_sub".
 *
 * @property integer $terima_bhp_sub_id
 * @property string $kode
 * @property string $tanggal
 * @property integer $bpbd_id
 * @property integer $bhp_id
 * @property string $target_plan
 * @property string $target_peruntukan
 * @property integer $departement_id
 * @property double $qty
 * @property double $harga_peritem
 * @property string $keterangan
 * @property string $status_approval
 * @property string $reason_approve
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MBrgBhp $bhp
 * @property MDepartement $departement
 * @property TBpbDetail $bpbd
 */
class TTerimaBhpSub extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $bhp_nm;
    public $tgl_awal, $tgl_akhir, $departement_nama, $total;
    public static function tableName()
    {
        return 't_terima_bhp_sub';
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
            [['kode', 'target_plan', 'target_peruntukan', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['bpbd_id', 'bhp_id', 'departement_id', 'created_by', 'updated_by'], 'integer'],
            [['qty', 'harga_peritem'], 'number'],
            [['keterangan', 'reason_approve'], 'string'],
            [['kode', 'status_approval'], 'string', 'max' => 25],
            [['target_plan', 'target_peruntukan'], 'string', 'max' => 100],
            [['bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgBhp::className(), 'targetAttribute' => ['bhp_id' => 'bhp_id']],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['bpbd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TBpbDetail::className(), 'targetAttribute' => ['bpbd_id' => 'bpbd_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'terima_bhp_sub_id' => 'Terima Bhp Sub ID',
            'kode' => 'Kode',
            'tanggal' => 'Tanggal',
            'bpbd_id' => 'Bpbd ID',
            'bhp_id' => 'Bhp ID',
            'target_plan' => 'Target Plan',
            'target_peruntukan' => 'Target Peruntukan',
            'departement_id' => 'Departement ID',
            'qty' => 'Qty',
            'harga_peritem' => 'Harga Peritem',
            'keterangan' => 'Keterangan',
            'status_approval' => 'Status Approval',
            'reason_approve' => 'Reason Approve',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBpbd()
    {
        return $this->hasOne(TBpbDetail::className(), ['bpbd_id' => 'bpbd_id']);
    }

    public function searchLaporan(){
		$bhp = MBrgBhp::tableName();
		$dept = MDepartement::tableName();
		$query = self::find();
		$query->select(self::tableName().'.terima_bhp_sub_id,  
                        kode, 
                        tanggal, 
                        '.$bhp.'.bhp_nm, 
                        qty, 
                        target_plan, 
                        target_peruntukan,
                        '.$dept.'.departement_nama, 
                        keterangan,
                        harga_peritem,
                        (qty * harga_peritem) as total,
                        '.self::tableName().'.departement_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.self::tableName().'.bhp_id');
		$query->join('JOIN', $dept,$dept.'.departement_id = '.self::tableName().'.departement_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'tanggal DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
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
		return $query;
	}

    public function searchLaporanDt(){
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
		if(!empty($this->target_plan)){
			array_push($param['where'],"target_plan = '".$this->target_plan."'");
		}
        if(!empty($this->target_peruntukan)){
			array_push($param['where'],"target_peruntukan = '".$this->target_peruntukan."'");
		}
		if(!empty($this->departement_id)){
			array_push($param['where'],$param['table'].".departement_id = ".$this->departement_id);
		}
		
		return $param;
	}
}
