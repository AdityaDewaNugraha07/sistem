<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_afkir_sengon".
 *
 * @property integer $afkir_sengon_id
 * @property boolean $grader 
 * @property string $kode
 * @property string $tanggal
 * @property integer $terima_sengon_id
 * @property double $diameter
 * @property double $panjang
 * @property double $qty_pcs
 * @property double $qty_m3
 * @property double $selisih_pcs
 * @property double $selisih_m3
 * @property boolean $sudah_dikirim
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property TTerimaSengon $terimaSengon
 */
class TAfkirSengon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir,$suplier_id,$terima_kode,$qty_pcs_terima,$qty_m3_terima,$suplier_nm,$lokasi_muat,$asal_kayu,$nopol,$qty_pcs_nota,$qty_m3_nota;
    public $panjang_total,$diameter_total,$qty_pcs_total,$qty_m3_total;
    public static function tableName()
    {
        return 't_afkir_sengon';
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
            [['kode', 'tanggal', 'terima_sengon_id', 'diameter', 'panjang', 'qty_pcs', 'qty_m3', 'selisih_pcs', 'selisih_m3', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['terima_sengon_id', 'created_by', 'updated_by'], 'integer'],
            [['diameter', 'panjang', 'qty_pcs', 'qty_m3', 'selisih_pcs', 'selisih_m3'], 'number'],
            [['sudah_dikirim', 'active', 'grader'], 'boolean'],
            [['kode'], 'string', 'max' => 50],
            [['terima_sengon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaSengon::className(), 'targetAttribute' => ['terima_sengon_id' => 'terima_sengon_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'afkir_sengon_id' => 'Afkir Sengon',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'terima_sengon_id' => 'Terima Sengon',
                'diameter' => 'Diameter',
                'panjang' => 'Panjang',
                'qty_pcs' => 'Qty Pcs',
                'qty_m3' => 'Qty M3',
                'selisih_pcs' => 'Selisih Pcs',
                'selisih_m3' => 'Selisih M3',
                'sudah_dikirim' => 'Sudah Dikirim',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'grader' => 'Grader',
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
		$query->select('afkir_sengon_id, 
                        t_afkir_sengon.kode, 
                        m_suplier.suplier_nm, 
                        t_terima_sengon.tanggal, 
                        t_terima_sengon.lokasi_muat, 
                        t_terima_sengon.asal_kayu, 
                        t_terima_sengon.nopol, 
                        t_afkir_sengon.qty_pcs, 
                        t_afkir_sengon.qty_m3,
                        t_afkir_sengon.selisih_pcs, 
                        t_afkir_sengon.selisih_m3,
                        t_afkir_sengon.sudah_dikirim,
                        t_afkir_sengon.grader');
        $query->join('JOIN', "t_terima_sengon",'t_terima_sengon.terima_sengon_id = t_afkir_sengon.terima_sengon_id');
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
