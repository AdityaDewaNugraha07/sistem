<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_tagihan_bahan_baku".
 *
 * @property string $penerima_reff_table
 * @property integer $penerima_reff_id
 * @property string $suplier_nm
 * @property string $type
 * @property string $status_bayar
 * @property integer $voucher_pengeluaran_id
 * @property string $tanggal
 * @property double $totaltagihan
 * @property double $dibayar
 */
class ViewTagihanBahanBaku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $per_tanggal ;
    public static function tableName()
    {
        return 'view_tagihan_bahan_baku';
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
            [['penerima_reff_id', 'voucher_pengeluaran_id'], 'integer'],
            [['tanggal'], 'safe'],
            [['totaltagihan', 'dibayar'], 'number'],
            [['penerima_reff_table', 'suplier_nm', 'status_bayar'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'penerima_reff_table' => 'Penerima Reff Table',
                'penerima_reff_id' => 'Penerima Reff',
                'suplier_nm' => 'Suplier Nm',
                'type' => 'Type',
                'status_bayar' => 'Status Bayar',
                'voucher_pengeluaran_id' => 'Voucher Pengeluaran',
                'tanggal' => 'Tanggal',
                'totaltagihan' => 'Totaltagihan',
                'dibayar' => 'Dibayar',
        ];
    }
    
    //
    public function searchLaporanTagihanSuplierBB() {
            $query = self::find();
            $select1 = "SUM(totaltagihan)";
            $select2 = "SUM(dibayar)";
            $select3 = "SUM(totaltagihan - dibayar)"; 
            $query->select(['penerima_reff_table',
                        'penerima_reff_id',
                        'suplier_nm',
                        'type',     
                        "{$select1} AS totaltagihan",
                        "{$select2} AS dibayar",
                        "{$select3} AS hutang" ]);
            
            $query->groupBy("penerima_reff_table,penerima_reff_id,suplier_nm,type");  
            $query->having("{$select1} - {$select2} >0");
            $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
                    'suplier_nm ASC' );
            $query->andWhere("tanggal <= '".$this->per_tanggal."'");
            if(!empty($this->penerima_reff_id)){
                    $query->andWhere("penerima_reff_id = ".$this->penerima_reff_id);
            }

//		echo "<pre>";
//		print_r($query->createCommand()->rawSql);
//		echo "</pre>";
//		exit;
            return $query;
    }

    public function searchLaporanTagihanSuplierBBDt() {
            $searchLaporan = $this->searchLaporanTagihanSuplierBB();
            $param['table']= self::tableName();
            $param['pk']= "penerima_reff_id";
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

            $param['where'] = ["tanggal <= '".$this->per_tanggal."'"];

            if(!empty($this->penerima_reff_id)){
                    array_push($param['where'],"penerima_reff_id = ".$this->penerima_reff_id); 
            }
            if(!empty($searchLaporan->having)){
                    $param['having'] = "HAVING ".$searchLaporan->having;
            }
//                echo "<pre>";
//		print_r($param);
//		echo "</pre>";
//		exit;
            return $param;
    }
}
