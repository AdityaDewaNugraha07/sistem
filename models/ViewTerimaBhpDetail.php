<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_terima_bhp_detail".
 *
 * @property integer $terima_bhpd_id
 * @property string $terimabhp_kode
 * @property string $tglterima
 * @property string $suplier_nm
 * @property string $bhp_kode
 * @property string $bhp_nm
 * @property string $bhp_satuan
 * @property double $terimabhpd_qty
 * @property double $terimabhpd_harga
 * @property double $ppn
 * @property integer $spo_id
 * @property double $total
 * @property string $terimabhpd_keterangan
 * @property double $ppn_nominal
 * @property double $pph_peritem
 * @property string $mata_uang
 * @property double $total_pbbkb
 */
class ViewTerimaBhpDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public $subtotal,$diskon_rp,$harga_estimasi,$qty_in,$qty_out,$totalbykelompokbarang;
    public $terimabhpd_harga_display,$subtotal_display;
    
    public $tgl_awal,$tgl_akhir;
    public $spod_id,$spld_id,$is_ppn_peritem,$is_pph_peritem;
    public $keterangan,$npwp,$per_tanggal,$terimabhpd_qty_old;

    public static function tableName()
    {
        return 'view_terima_bhp_detail';
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
            [['terima_bhpd_id', 'spo_id', 'suplier_id'], 'integer'],
            [['tglterima'], 'safe'],
            [['terimabhpd_qty', 'terimabhpd_harga', 'ppn', 'total', 'ppn_nominal', 'pph_peritem', 'total_pbbkb'], 'number'],
            [['terimabhpd_keterangan'], 'string'],
            [['terimabhp_kode'], 'string', 'max' => 30],
            [['suplier_nm', 'bhp_satuan'], 'string', 'max' => 50],
            [['bhp_kode'], 'string', 'max' => 100],
            [['bhp_nm', 'mata_uang'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
                'terima_bhpd_id' => 'Terima Bhpd',
                'terimabhp_kode' => 'Terimabhp Kode',
                'tglterima' => 'Tglterima',
                'suplier_nm' => 'Suplier Nm',
                'bhp_kode' => 'Bhp Kode',
                'bhp_nm' => 'Bhp Nm',
                'bhp_satuan' => 'Bhp Satuan',
                'terimabhpd_qty' => 'Terimabhpd Qty',
                'terimabhpd_harga' => 'Terimabhpd Harga',
                'ppn' => 'Ppn',
                'spo_id' => 'Spo',
                'total' => 'Total',
                'terimabhpd_keterangan' => 'Terimabhpd Keterangan',
                'ppn_nominal' => 'Ppn Nominal',
                'pph_peritem' => 'Pph Peritem',
                'mata_uang' => 'Mata Uang',
                'total_pbbkb' => 'Total Pbbkb',
        ];
    }
    
    public function searchLaporan() {

        $query = self::find();
        $query->select(
                self::tableName().'.terima_bhpd_id,  
                terimabhp_kode, 
                tglterima, 
                suplier_nm, 
                bhp_kode, 
                bhp_nm, 
                bhp_satuan, 
                terimabhpd_qty, 
                terimabhpd_harga, 
                ppn,
                spo_id,
                ( terimabhpd_qty * terimabhpd_harga ) + ppn + CASE WHEN pph_peritem IS NULL THEN 0 ELSE pph_peritem END + total_pbbkb AS total,
                terimabhpd_keterangan,
                ppn_nominal,
                pph_peritem,
                mata_uang,
                total_pbbkb'
        );
        $query->andWhere(" cancel_transaksi_id IS NULL ");
        $query->andWhere(" totalbayar > 0");
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
                'tglterima DESC' );
        if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
                $query->andWhere("tglterima BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
        }
        if(!empty($this->terimabhp_kode)){
                $query->andWhere("terimabhp_kode  ilike '%".$this->terimabhp_kode."%'");
        }
        if(!empty($this->bhp_nm)){
                $query->andWhere("bhp_nm  ilike '%".$this->bhp_nm."%'");
        }
        if(!empty($this->bhp_group)){
                $query->andWhere("bhp_group  = '".$this->bhp_group."'");
        }
        if(!empty($this->suplier_id)){
                $query->andWhere(self::tableName().".suplier_id  = ".$this->suplier_id."");
        }
        if(!empty($this->terimabhpd_keterangan)){
                // $query->andWhere("terimabhpd_keterangan  ILIKE '%".$this->terimabhpd_keterangan."%'");
                $query->andWhere(" exists(
                        select terimabhpd_keterangan from t_terima_bhp_detail as a  
                        where a.terima_bhpd_id=view_terima_bhp_detail.terima_bhpd_id 
                                and terimabhpd_keterangan ILIKE '%".$this->terimabhpd_keterangan."%' group by 1
                )");
        }
        return $query;
    }
	
    public function searchLaporanDt() {
        $searchLaporan = $this->searchLaporan();
        $param['table']= self::tableName();
        $param['pk']= "terima_bhpd_id";
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
        array_push($param['where'],"cancel_transaksi_id IS NULL and totalbayar > 0");
        if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
                array_push($param['where'],"tglterima BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
        }
        if(!empty($this->terimabhp_kode)){
                array_push($param['where'],"terimabhp_kode ilike '%".$this->terimabhp_kode."%'");
        }
        if(!empty($this->bhp_group)){
                array_push($param['where'],"bhp_group = '".$this->bhp_group."'");
        }
        if(!empty($this->bhp_nm)){
                array_push($param['where'],"bhp_nm ilike '%".$this->bhp_nm."%'");
        }
        if(!empty($this->suplier_id)){
                array_push($param['where'],"suplier_id = ".$this->suplier_id."");
        }
        if(!empty($this->terimabhpd_keterangan)){
                // array_push($param['where'],"terimabhpd_keterangan ILIKE '%".$this->terimabhpd_keterangan."%'");
                array_push($param['where']," exists(
                        select terimabhpd_keterangan from t_terima_bhp_detail as a  
                        where a.terima_bhpd_id=view_terima_bhp_detail.terima_bhpd_id 
                                and terimabhpd_keterangan ILIKE '%".$this->terimabhpd_keterangan."%' group by 1
                )");
        }
        return $param;
    }
}
