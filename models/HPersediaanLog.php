<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "h_persediaan_log".
 *
 * @property integer $persediaan_log_id
 * @property string $tgl_transaksi
 * @property integer $kayu_id
 * @property string $no_grade
 * @property string $no_barcode
 * @property string $no_btg
 * @property string $no_lap
 * @property string $status
 * @property string $reff_no
 * @property string $lokasi
 * @property double $fisik_diameter
 * @property double $fisik_panjang
 * @property double $fisik_volume
 * @property string $fisik_reduksi
 * @property integer $pot
 * @property string $keterangan
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $fisik_pcs
 * @property integer $diameter_ujung1
 * @property integer $diameter_ujung2
 * @property integer $diameter_pangkal1
 * @property integer $diameter_pangkal
 * @property integer $cacat_panjang
 * @property integer $cacat_gb
 * @property integer $cacat_gr
 * @property boolean $fsc
 *
 * @property MKayu $kayu
 */
class HPersediaanLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir,$tanggal,$kayu;
    public static function tableName()
    {
        return 'h_persediaan_log';
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
            [['kayu_id', 'tgl_transaksi', 'no_grade', 'no_barcode', 'no_btg', 'status', 'reff_no', 'lokasi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['kayu_id', 'created_by', 'updated_by'], 'integer'],
            [['fisik_diameter', 'fisik_panjang', 'fisik_volume','fisik_pcs','diameter_ujung1','diameter_ujung2','diameter_pangkal1','diameter_pangkal2','cacat_panjang','cacat_gb','cacat_gr'], 'number'],
            [['pot', 'keterangan'], 'string'],
            [['active', 'fsc'], 'boolean'],
            [['created_at', 'updated_at','tgl_transaksi'], 'safe'],
            [['no_grade', 'no_barcode', 'no_btg', 'no_lap', 'status', 'reff_no', 'lokasi', 'fisik_reduksi'], 'string', 'max' => 50],
            [['kayu_id'], 'exist', 'skipOnError' => true, 'targetClass' => MKayu::className(), 'targetAttribute' => ['kayu_id' => 'kayu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'persediaan_log_id' => 'Persediaan Log',
                'kayu_id' => 'Kayu',
                'tgl_transaksi' => 'Tanggal',
                'no_grade' => 'No Grade',
                'no_barcode' => 'No Barcode',
                'no_btg' => 'No Btg',
                'no_lap' => 'No. Lap',
                'status' => 'Status',
                'reff_no' => 'Reff No',
                'lokasi' => 'Lokasi',
                'fisik_diameter' => 'Fisik Diameter',
                'fisik_panjang' => 'Fisik Panjang',
                'fisik_volume' => 'Fisik Volume',
                'fisik_reduksi' => 'Fisik Reduksi',
                'pot' => 'Pot',
                'keterangan' => 'Keterangan',
                'active' => 'Status',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'fisik_pcs' => 'Fisik Pcs',
                'diameter_ujung1' => 'Diameter Ujung 1',
                'diameter_ujung2' => 'Diameter Ujung 1',
                'diameter_pangkal1' => 'Diameter Pangkal 1',
                'diameter_pangkal2' => 'Diameter Pangkal 2',
                'cacat_panjang' => 'Cacat Panjang',
                'cacat_gb' => 'Cacat Gubal',
                'cacat_gr' => 'Cacat Growong',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKayu()
    {
        return $this->hasOne(MKayu::className(), ['kayu_id' => 'kayu_id']);
    }
	
	public static function checkPersediaanOut($barcode){
		$check = \app\models\HPersediaanLog::find()->where("no_barcode='{$barcode}' AND status != 'IN'")->all();
		if(count($check)>0){
			return true;
		}else{
			return false;
		}
	}
	
	public static function updateStokPersediaan($model){
        if($model->validate()){
            if($model->save()){
                return true;
            }else{
                return false;
            }
		}
    }
	
	public static function getCurrentStockPerBatang($no_barcode){
		$sql = "SELECT * FROM h_persediaan_log WHERE no_barcode = '{$no_barcode}' AND status = 'IN' ORDER BY persediaan_log_id DESC LIMIT 1";
		$mod = self::find()->where(['no_barcode'=>$no_barcode,'status'=>'IN'])->limit("1")->orderBy("persediaan_log_id DESC")->one();
		return $mod;
	}
    
	public static function getStockSengonRekap($lokasi,$kayu_id,$panjang,$tgl_transaksi,$dia_a,$dia_b){
		$sql = "SELECT fisik_panjang, 
                    ( (SELECT COALESCE( SUM(fisik_pcs), 0) FROM h_persediaan_log AS a 
                            WHERE lokasi = '{$lokasi}' 
                            AND kayu_id = {$kayu_id} AND status = 'IN' 
                            AND a.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND a.tgl_transaksi <= '{$tgl_transaksi}' 
                            AND a.fisik_diameter BETWEEN {$dia_a} AND {$dia_b}
                       ) - 
                      (SELECT COALESCE( SUM(fisik_pcs), 0) FROM h_persediaan_log AS b 
                            WHERE lokasi = '{$lokasi}' 
                            AND kayu_id = {$kayu_id} AND status = 'OUT' 
                            AND b.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND b.tgl_transaksi <= '{$tgl_transaksi}' 
                            AND b.fisik_diameter BETWEEN {$dia_a} AND {$dia_b}
                       ) 
                    ) AS pcs,
                    ( (SELECT COALESCE( SUM(fisik_volume), 0) FROM h_persediaan_log AS a 
                            WHERE lokasi = '{$lokasi}' 
                            AND kayu_id = {$kayu_id} AND status = 'IN' 
                            AND a.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND a.tgl_transaksi <= '{$tgl_transaksi}' 
                            AND a.fisik_diameter BETWEEN {$dia_a} AND {$dia_b}
                       ) - 
                      (SELECT COALESCE( SUM(fisik_volume), 0) FROM h_persediaan_log AS b 
                            WHERE lokasi = '{$lokasi}' 
                            AND kayu_id = {$kayu_id} AND status = 'OUT' 
                            AND b.fisik_panjang = h_persediaan_log.fisik_panjang
                            AND b.tgl_transaksi <= '{$tgl_transaksi}' 
                            AND b.fisik_diameter BETWEEN {$dia_a} AND {$dia_b}
                       ) 
                    ) AS m3
                FROM h_persediaan_log WHERE fisik_panjang = '{$panjang}' GROUP BY 1";
//        echo "<pre>";
//        print_r($sql);
//        exit;
        $mod = Yii::$app->db->createCommand($sql)->queryOne();
		return $mod;
	}

    public function searchLaporanPengeluaran($jenis_log) {
		$query = self::find();
		$query->select('h_persediaan_log.persediaan_log_id,
                            h_persediaan_log.tgl_transaksi, 
                            b.kayu_nama, 
                            h_persediaan_log.no_grade, 
                            h_persediaan_log.no_barcode, 
                            h_persediaan_log.no_btg, 
                            h_persediaan_log.no_lap, 
                            h_persediaan_log.status, 
                            h_persediaan_log.reff_no, 
                            h_persediaan_log.lokasi,
                            h_persediaan_log.fisik_diameter, 
                            h_persediaan_log.fisik_panjang, 
                            h_persediaan_log.fisik_reduksi, 
                            h_persediaan_log.fisik_volume, 
                            h_persediaan_log.pot, 
                            h_persediaan_log.keterangan,
                            h_persediaan_log.fisik_pcs, 
                            h_persediaan_log.diameter_ujung1, 
                            h_persediaan_log.diameter_ujung2, 
                            h_persediaan_log.diameter_pangkal1, 
                            h_persediaan_log.diameter_pangkal2, 
                            h_persediaan_log.cacat_panjang, 
                            h_persediaan_log.cacat_gb, 
                            h_persediaan_log.cacat_gr
                        ');
		$query->join('JOIN', 'm_kayu b', 'b.kayu_id = h_persediaan_log.kayu_id');
		$query->andWhere([self::tableName().'.active'=>TRUE]);
        $query->orWhere("h_persediaan_log.lokasi = 'GUDANG LOG ALAM");
        $query->orWhere("h_persediaan_log.lokasi = 'PRODUKSI LOG ALAM");
        $query->orWhere("h_persediaan_log.lokasi = 'PENJUALAN LOG ALAM");

        if ($jenis_log == "Sengon") {
            $reff_no = "MLS"; 
        } else if ($jenis_log == "Jabon") {
            $reff_no = "MLJ";
        }

        $query->andWhere("h_persediaan_log.reff_no  ilike '".$reff_no."%' and status = 'OUT' ");
        
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']):"" );
		return $query;
	}
	
	public function searchLaporanDtPengeluaran($jenis_log) {
		$searchLaporan = $this->searchLaporanPengeluaran($jenis_log);
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
        if ($jenis_log == "Sengon") {
            $reff_no = "MLS"; 
        } else if ($jenis_log == "Jabon") {
            $reff_no = "MLJ";
        }
		array_push($param['where'],"reff_no ilike '".$reff_no."%' and status = 'OUT' ");
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tgl_transaksi BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		return $param;
	}

    // public static function getCurrentStockPerLog($kayu_id, $log_id){
    //     $sql = "SELECT m_brg_log.log_id, m_brg_log.kayu_id, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS stock
    //             FROM m_brg_log
    //             JOIN h_persediaan_log on h_persediaan_log.kayu_id = m_brg_log.kayu_id
    //             JOIN m_kayu ON m_kayu.kayu_id = m_brg_log.kayu_id
    //             join (SELECT no_barcode, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS total_stock FROM h_persediaan_log
	// 						GROUP BY no_barcode HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0) s ON h_persediaan_log.no_barcode = s.no_barcode
    //             WHERE fisik_diameter BETWEEN range_awal AND range_akhir AND m_brg_log.active = TRUE 
    //             AND m_brg_log.kayu_id = {$kayu_id} AND m_brg_log.log_id = {$log_id}
    //             group by m_brg_log.log_id, m_brg_log.kayu_id
    //             having SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0";
    //     $mod = \Yii::$app->db->createCommand($sql)->queryOne();
    //     return $mod;
	// }


    // TAMBAH FSC - penambahan parameter dan kondisi where $fsc
    public static function getCurrentStockPerLog($po_ko_id, $log_id, $fsc){
        $sql = "SELECT m_brg_log.log_id, m_brg_log.kayu_id, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS stock
                FROM t_po_ko_detail 
                JOIN m_brg_log on (
                                    (t_po_ko_detail.produk_id IS NULL AND m_brg_log.log_id = ANY(string_to_array(t_po_ko_detail.produk_id_alias, ',')::int[])) OR
                                    (t_po_ko_detail.produk_id IS NOT NULL AND m_brg_log.log_id = t_po_ko_detail.produk_id)
                                   )
                JOIN m_kayu on m_kayu.kayu_id = m_brg_log.kayu_id
                LEFT JOIN h_persediaan_log on h_persediaan_log.kayu_id = m_brg_log.kayu_id AND h_persediaan_log.fisik_diameter BETWEEN m_brg_log.range_awal AND m_brg_log.range_akhir
                WHERE t_po_ko_detail.po_ko_id = {$po_ko_id} and log_id = {$log_id} and no_grade <> '-' and h_persediaan_log.fsc = '{$fsc}'
                GROUP BY m_brg_log.log_id";
        $mod = \Yii::$app->db->createCommand($sql)->queryOne();
        return $mod;
	}

    public static function getDataByNoBarcode($no_barcode){
        $sql = "SELECT *, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS stock FROM h_persediaan_log 
                WHERE no_barcode = '$no_barcode' and no_grade <> '-'
                GROUP BY persediaan_log_id";
		$mod = \Yii::$app->db->createCommand($sql)->queryOne();
		return $mod;
	}

    public static function getDataScanned($no_barcode, $fsc){
        $sql = "SELECT m_brg_log.log_id, h_persediaan_log.kayu_id, no_barcode, SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) AS stock, h_persediaan_log.fsc
                FROM h_persediaan_log 
                JOIN m_brg_log ON h_persediaan_log.kayu_id = m_brg_log.kayu_id 
                WHERE no_barcode = '{$no_barcode}' AND fisik_diameter BETWEEN range_awal AND range_akhir AND m_brg_log.fsc = '$fsc'
                GROUP BY m_brg_log.log_id, h_persediaan_log.kayu_id, no_barcode, h_persediaan_log.fsc
                HAVING SUM(CASE WHEN status = 'IN' THEN 1 ELSE -1 END) > 0";
        $mod = \Yii::$app->db->createCommand($sql)->queryOne();
		return $mod;
    }
}
