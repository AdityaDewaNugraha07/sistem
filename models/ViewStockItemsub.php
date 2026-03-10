<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_itemsub".
 *
 * @property string $itemnumber
 * @property integer $bhp_id
 * @property integer $reff_detail_id
 * @property double $jumlah
 */
class ViewStockItemsub extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $departement_id, $target_plan, $target_peruntukan;
	public $reff_detail_id, $kode, $bhp_nm, $dept_peruntukan, $harga_peritem, $departement;
    public static function tableName()
    {
        return 'view_stock_itemsub';
    }
    public static function primaryKey()
    {
        return ["reff_detail_id"];
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
            [['itemnumber'], 'string'],
            [['bhp_id', 'reff_detail_id'], 'integer'],
            [['jumlah'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemnumber' => 'Itemnumber',
            'bhp_id' => 'Bhp ID',
            'reff_detail_id' => 'Reff Detail ID',
            'jumlah' => 'Jumlah',
        ];
    }

	public function searchLaporan(){
        $terimabarang = TTerimaBhpSub::tableName();
        $detail = TPemakaianBhpsubDetail::tableName();
		$bhp = MBrgBhp::tableName();
		$dept = MDepartement::tableName();
		$query = self::find();
		$query->select(self::tableName().'.reff_detail_id,  
                        '.$terimabarang.'.kode, 
                        '.$bhp.'.bhp_nm, 
                        jumlah, 
                        '.$terimabarang.'.target_plan, 
                        '.$terimabarang.'.target_peruntukan,
                        '.$dept.'.departement_nama as dept_peruntukan,
						a.departement_nama as departement,
						'.$terimabarang.'.harga_peritem,
						(jumlah * '.$terimabarang.'.harga_peritem) as total,
						'.$detail.'.dept_peruntukan as dept_id');
        $query->join('JOIN', $terimabarang,$terimabarang.'.terima_bhp_sub_id = '.self::tableName().'.reff_detail_id');
		$query->join('JOIN', $bhp,$bhp.'.bhp_id = '.self::tableName().'.bhp_id');
        $query->join('LEFT JOIN', $detail,$detail.'.terima_bhp_sub_id = '.$terimabarang.'.terima_bhp_sub_id');
        $query->join('LEFT JOIN', $dept,$dept.'.departement_id = '.$detail.'.dept_peruntukan');
		$query->join('JOIN', $dept. ' a','a.departement_id = '.$terimabarang.'.departement_id');
		if(!empty($this->target_plan)){
			$query->andWhere("target_plan  ILIKE '%".$this->target_plan."%'");
		}
        if(!empty($this->target_peruntukan)){
			$query->andWhere("target_peruntukan  ILIKE '%".$this->target_peruntukan."%'");
		}
		if(!empty($this->departement_id)){
			$query->andWhere($terimabarang.".departement_id  = ".$this->departement_id);
		}
		return $query;
	}

    public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= 'reff_detail_id';
        $terimabarang = TTerimaBhpSub::tableName();
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
		if(!empty($this->target_plan)){
			array_push($param['where'],"target_plan = '".$this->target_plan."'");
		}
        if(!empty($this->target_peruntukan)){
			array_push($param['where'],"target_peruntukan = '".$this->target_peruntukan."'");
		}
		if(!empty($this->departement_id)){
			array_push($param['where'],$terimabarang.".departement_id = ".$this->departement_id);
		}
		
		return $param;
	}
}
