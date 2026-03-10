<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_penyerapan_budget".
 *
 * @property integer $departement_id
 * @property string $departement_nama
 * @property string $periode_bulan
 * @property integer $bhp_id
 * @property string $bhp_nm
 * @property string $target_plan
 * @property string $target_peruntukan
 * @property double $penerimaan
 * @property double $pemakaian
 * @property string $penyerapan
 */
class ViewPenyerapanBudget extends \app\models\DeltaBaseActiveRecord //extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal, $tgl_akhir, $bhp_nm, $departement_nama, $total;

    public static function tableName()
    {
        return 'view_penyerapan_budget';
    }
    public static function primaryKey()
    {
        // return ['departement_nama', 'periode_bulan']; // Kombinasi kolom yang dianggap unik
		return ['id'];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['departement_id', 'bhp_id'], 'integer'],
            [['periode_bulan'], 'string'],
            [['penerimaan', 'pemakaian', 'penyerapan'], 'number'],
            [['departement_nama'], 'string', 'max' => 50],
            [['bhp_nm'], 'string', 'max' => 200],
            [['target_plan', 'target_peruntukan'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'departement_id' => 'Departement ID',
            'departement_nama' => 'Departement Nama',
            'periode_bulan' => 'Periode Bulan',
            'bhp_id' => 'Bhp ID',
            'bhp_nm' => 'Bhp Nm',
            'target_plan' => 'Target Plan',
            'target_peruntukan' => 'Target Peruntukan',
            'penerimaan' => 'Penerimaan',
            'pemakaian' => 'Pemakaian',
            'penyerapan' => 'Penyerapan',
        ];
    }
	// Method untuk mendapatkan nama bulan
    public function getMonthName($month)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        return $months[$month];
    }
    public static function getOptionListPeriodeBln()
    {
        $res = Yii::$app->db->createCommand("select periode_bulan from view_penyerapan_budget group by 1 order by to_date(periode_bulan, 'Month YYYY') asc")->queryAll();
		$ret = [];
		foreach($res as $i => $asd){
			$ret[$asd['periode_bulan']] = $asd['periode_bulan'];
		}
        return $ret;
    }
    public function searchLaporan(){
		$query = self::find();
		$dept = MDepartement::tableName();
		$query->select([
			self::tableName() . '.departement_id',
								'departement_nama',
								'target_peruntukan',
								'target_plan',
								'periode_bulan',							
								'total_penerimaan',
								'total_pemakaian',
								'penyerapan',
								'bulan',
								'tahun',
                                new \yii\db\Expression("to_date(periode_bulan, 'Month YYYY') AS periode_bulan_tanggal")								
		]);
		$query->join('JOIN', $dept,$dept.'.departement_id = '.self::tableName().'.departement_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 'departement_id,target_peruntukan,target_plan,periode_bulan_tanggal asc' );
        // Ubah bagian orderBy untuk menggunakan kolom spesifik jika tidak ada sorting dari parameter GET
        // $query->orderBy(
        //     !empty($_GET['sort']['col']) 
        //         ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) . " " . strtoupper($_GET['sort']['dir']) 
        //         : [
        //             'departement_id' => SORT_ASC,
        //             'target_peruntukan' => SORT_ASC,
        //             'target_plan' => SORT_ASC,
        //             'periode_bulan_tanggal' => SORT_ASC
        //         ]
        // );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere(" to_date(periode_bulan, 'Month YYYY') BETWEEN to_date('".$this->tgl_awal."', 'Month YYYY') AND to_date('".$this->tgl_akhir."', 'Month YYYY')");
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
			array_push($param['where'],"to_date(periode_bulan, 'Month YYYY') BETWEEN to_date('".$this->tgl_awal."', 'Month YYYY') AND to_date('".$this->tgl_akhir."', 'Month YYYY')");
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
