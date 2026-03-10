<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_sengon_detail".
 *
 * @property integer $terima_sengon_detail_id
 * @property integer $terima_sengon_id
 * @property integer $nourut_log
 * @property string $kode_terima
 * @property integer $nourut_datang
 * @property string $kode_jenis
 * @property double $diameter
 * @property double $panjang
 * @property double $qty_pcs
 * @property double $qty_m3
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TTerimaSengon $terimaSengon
 */
class TTerimaSengonDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir,$suplier_id,$jenis_periode,$kode,$suplier_nm,$tanggal,$nopol;
    public static function tableName()
    {
        return 't_terima_sengon_detail';
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
            [['terima_sengon_id', 'nourut_log', 'kode_terima', 'nourut_datang', 'kode_jenis', 'diameter', 'panjang', 'qty_pcs', 'qty_m3', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['terima_sengon_id', 'nourut_log', 'nourut_datang', 'created_by', 'updated_by'], 'integer'],
            [['diameter', 'panjang', 'qty_pcs', 'qty_m3'], 'number'],
            [['active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode_jenis', 'kode_terima'], 'string', 'max' => 50],
            [['terima_sengon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaSengon::className(), 'targetAttribute' => ['terima_sengon_id' => 'terima_sengon_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'terima_sengon_detail_id' => 'Terima Sengon Detail',
                'terima_sengon_id' => 'Terima Sengon',
                'nourut_log' => 'Nourut Log',
                'kode_terima' => 'Kode Terima',
                'nourut_datang' => 'Nourut Datang',
                'kode_jenis' => 'Kode Jenis',
                'diameter' => 'Diameter',
                'panjang' => 'Panjang',
                'qty_pcs' => 'Qty Pcs',
                'qty_m3' => 'Qty M3',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaSengon()
    {
        return $this->hasOne(TTerimaSengon::className(), ['terima_sengon_id' => 'terima_sengon_id']);
    }
    
    
    
    public function searchLaporan() {
		$query = self::find();
		$query->select('terima_sengon_detail_id, 
                        t_terima_sengon.kode, 
                        m_suplier.suplier_nm, 
                        t_terima_sengon.tanggal, 
                        t_terima_sengon.nopol, 
                        nourut_log, 
                        kode_terima, 
                        nourut_datang, 
                        kode_jenis, 
                        diameter, 
                        panjang, 
                        qty_pcs, 
                        qty_m3,
                        t_terima_sengon_detail.terima_sengon_id');
        $query->join('JOIN', "t_terima_sengon",'t_terima_sengon.terima_sengon_id = t_terima_sengon_detail.terima_sengon_id');
        $query->join('JOIN', "m_suplier",'m_suplier.suplier_id = t_terima_sengon.suplier_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.terima_sengon_id DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_terima_sengon.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->suplier_id)){
			$query->andWhere("t_terima_sengon.suplier_id = %".$this->suplier_id."%");
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
			array_push($param['where'],"t_terima_sengon.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
        if(!empty($this->suplier_id)){
            array_push($param['where'],"t_terima_sengon.suplier_id = ".$this->suplier_id );
		}
		return $param;
	}
}
