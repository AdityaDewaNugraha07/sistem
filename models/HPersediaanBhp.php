<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_persediaan_bhp".
 *
 * @property integer $persediaan_id
 * @property integer $bhp_id
 * @property string $waktu_transaksi
 * @property double $qty_in
 * @property double $qty_out
 * @property string $keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $reff_no
 * @property integer $reff_detail_id
 * @property string $tgl_transaksi
 */ 
class HPersediaanBhp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $bhp_group,$per_tanggal,$bhp_nm,$current,$available;
	public $tgl_awal,$tgl_akhir,$total_qty;
    public static function tableName()
    {
        return 'h_persediaan_bhp';
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
			[['bhp_id', 'waktu_transaksi', 'qty_in', 'qty_out', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['bhp_id', 'created_by', 'updated_by', 'reff_detail_id'], 'integer'],
            [['waktu_transaksi', 'created_at', 'updated_at', 'tgl_transaksi'], 'safe'],
            [['qty_in', 'qty_out'], 'number'],
            [['keterangan'], 'string'],
            [['active'], 'boolean'],
            [['reff_no'], 'string', 'max' => 50],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'persediaan_id' => Yii::t('app', 'Persediaan'),
            'bhp_id' => Yii::t('app', 'Bhp'),
            'waktu_transaksi' => Yii::t('app', 'Waktu Transaksi'),
            'qty_in' => Yii::t('app', 'Qty In'),
            'qty_out' => Yii::t('app', 'Qty Out'),
            'keterangan' => Yii::t('app', 'Keterangan'),
            'active' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Create Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Last Update Time'),
            'updated_by' => Yii::t('app', 'Last Updated By'),
			'reff_no' => Yii::t('app', 'Reff No'),
			'reff_detail_id' => Yii::t('app', 'Reff Detail'),
			'tgl_transaksi' => Yii::t('app', 'Tgl Transaksi'),
        ];
    }
    
    public static function updateStokPersediaan($modParams,$reff_no,$reff_detail_id,$tgl_transaksi){
        $model = new HPersediaanBhp();
        $model->bhp_id = $modParams->bhp_id;
        $model->waktu_transaksi = date('Y-m-d H:i:s');
        $model->qty_in = $modParams->qty_in;
        $model->qty_out = $modParams->qty_out;
        $model->keterangan = isset($modParams->keterangan)?$modParams->keterangan:"";
        $model->reff_no = !empty($reff_no)?$reff_no:"";
        $model->reff_detail_id = !empty($reff_detail_id)?$reff_detail_id:"";
        $model->tgl_transaksi = $tgl_transaksi;
        if($model->validate()){
            if($model->save()){
                return true;
            }else{
                return false;
            }
        }
    }
	
    public static function getCurrentStock($bhp_id){
        $sql = "SELECT sum(qty_in)-sum(qty_out) AS current FROM h_persediaan_bhp WHERE bhp_id = ".$bhp_id." GROUP BY bhp_id";
		$mod = \Yii::$app->db->createCommand($sql)->queryOne();
        return $mod['current'];
    }
	
	public function searchLaporan() {
		$bhp = MBrgBhp::tableName();
		$query = self::find();
		$query->groupBy(self::tableName().".bhp_id, ".$bhp.".bhp_nm");
		$query->select(self::tableName().'.bhp_id, '.$bhp.'.bhp_nm, SUM(qty_in) AS qty_in, SUM(qty_out) AS qty_out, SUM(qty_in - qty_out) AS current');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.self::tableName().'.bhp_id');
		$query->andWhere([self::tableName().'.active'=>TRUE]);
		if(!empty($this->tgl_transaksi)){
			$query->andWhere("tgl_transaksi  <= '".$this->tgl_transaksi." 23:59:59'");
		}
		if(!empty($this->waktu_transaksi)){
			$query->andWhere("waktu_transaksi  <= '".$this->waktu_transaksi." 23:59:59'");
		}
		if(!empty($this->bhp_nm)){
			$query->andWhere("bhp_nm  ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->bhp_group)){
			$query->andWhere("bhp_group  = '".$this->bhp_group."'");
		}
		if(!empty($this->waktu_transaksi)){
			$query->andWhere("waktu_transaksi  ILIKE '%".$this->waktu_transaksi."%'");
		}
		if($this->available){
			$query->having("SUM(qty_in - qty_out) > 0");
		}
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
		return $query;
	}
	
	public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= self::primaryKey()[0];
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}		
		if(!empty($searchLaporan->groupBy)){
			$param['group'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		$param['where'] = [];
		if(!empty($this->tgl_transaksi)){
			array_push($param['where'],"tgl_transaksi <= '".$this->tgl_transaksi." 23:59:59'");
		}
		if(!empty($this->waktu_transaksi)){
			array_push($param['where'],"waktu_transaksi <= '".$this->waktu_transaksi." 23:59:59'");
		}
		if(!empty($this->bhp_nm)){
			array_push($param['where'],"bhp_nm ILIKE '%".$this->bhp_nm."%'");
		}
		if(!empty($this->bhp_group)){
			array_push($param['where'],"bhp_group = '".$this->bhp_group."'");
		}
		if($this->available){
			$param['having'] = "HAVING SUM(qty_in - qty_out) > 0";
		}
		
		return $param;
	}
}
