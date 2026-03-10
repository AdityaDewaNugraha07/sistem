<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spk_shipping_tracking".
 *
 * @property integer $spk_shipping_tracking_id
 * @property integer $spk_shipping_id
 * @property string $tanggal
 * @property string $jenis
 * @property string $lokasi
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 */
class TSpkShippingTracking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir, $kode, $nama_tongkang;
    public static function tableName()
    {
        return 't_spk_shipping_tracking';
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
            [['spk_shipping_id', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['tanggal', 'jenis', 'lokasi', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['lokasi'], 'string'],
            [['jenis'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spk_shipping_tracking_id' => 'Spk Shipping Tracking',
                'spk_shipping_id' => 'Spk Shipping',
                'tanggal' => 'Tanggal',
                'jenis' => 'Jenis',
                'lokasi' => 'Lokasi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
        ];
    }

    /**
     * @inheritdoc
     * @return TSpkShippingTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TSpkShippingTrackingQuery(get_called_class());
    }

    public function searchLaporan(){
		$query = self::find();
        $query->select('t_spk_shipping_tracking.spk_shipping_tracking_id, t_spk_shipping.kode, t_spk_shipping_tracking.tanggal, 
                            t_spk_shipping.nama_tongkang, t_spk_shipping_tracking.jenis, t_spk_shipping_tracking.lokasi, t_spk_shipping.status');
        $query->join('JOIN', 't_spk_shipping', 't_spk_shipping.spk_shipping_id = '.self::tableName().'.spk_shipping_id');
        $query->andWhere("t_spk_shipping.status = 'APPROVED' ");
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : 'tanggal DESC' );
		if(!empty($this->kode)){
			$query->andWhere("t_spk_shipping.kode ILIKE '%".$this->kode."%'");
		}
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("date(t_spk_shipping_tracking.tanggal) BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nama_tongkang)){
			$query->andWhere("t_spk_shipping.t_spk_shipping.nama_tongkang  = ".$this->nama_tongkang);
		}
		if(!empty($this->lokasi_muat)){
			$query->andWhere("t_spk_shipping_tracking.jenis = '".$this->jenis."'");
		}
        if(!empty($this->pic_shipping)){
			$query->andWhere("t_spk_shipping_tracking.lokasi = ".$this->lokasi." ");
        }
		return $query;
	}
	
	public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];

		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
		}
		if(!empty($searchLaporan->join)){
			foreach($searchLaporan->join as $join){
				$param['join'][] = $join[0].' '.$join[1]." ON ".$join[2];
			}
		}
		if(!empty($searchLaporan->groupBy)){
			$param['column'] = ['GROUP BY '.implode(", ", $searchLaporan->groupBy)];
		}
        if(!empty($searchLaporan->orderBy)){
			foreach($searchLaporan->orderBy as $i_order => $order){
				$param['order'][] = $i_order." ".(($order == 3)?"DESC":"ASC");
			}
		}
		$param['where'] = [];
		if(!empty($this->kode)){
			array_push($param['where'],"t_spk_shipping.kode ILIKE '%".$this->kode."%'");
		}
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"date(t_spk_shipping_tracking.tanggal) BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nama_tongkang)){
			array_push($param['where'],"t_spk_shipping.nama_tongkang ILIKE '%".$this->nama_tongkang."%'");
		}
		if(!empty($this->jenis)){
			array_push($param['where'],"t_spk_shipping_tracking.jenis ILIKE '%".$this->jenis."%'");
		}
		if(!empty($this->lokasi)){
			array_push($param['where'],"t_spk_shipping_tracking.lokasi ILIKE '%".$this->lokasi."%'");
		}
		return $param;
	}
}
