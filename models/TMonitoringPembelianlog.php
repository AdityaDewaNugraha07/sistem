<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_monitoring_pembelianlog".
 *
 * @property integer $monitoring_pembelianlog_id
 * @property integer $pengajuan_pembelianlog_id
 * @property string $tanggal
 * @property string $lokasi_logpond
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MKayu $kayu
 * @property TPengajuanPembelianlog $pengajuanPembelianlog
 */
class TMonitoringPembelianlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $attachment,$suplier_id,$tgl_awal,$tgl_akhir,$kode_pengajuan,$nomor_kontrak,$suplier_nm,$waktu_penyerahan_awal,$waktu_penyerahan_akhir;
    public static function tableName()
    {
        return 't_monitoring_pembelianlog';
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
            [['pengajuan_pembelianlog_id', 'tanggal', 'lokasi_logpond', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['pengajuan_pembelianlog_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal'], 'safe'],
            [['keterangan'], 'string'],
            [['lokasi_logpond'], 'string', 'max' => 200],
            [['pengajuan_pembelianlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPengajuanPembelianlog::className(), 'targetAttribute' => ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'monitoring_pembelianlog_id' => 'Monitoring Pembelianlog',
                'pengajuan_pembelianlog_id' => 'Pengajuan Pembelianlog',
                'tanggal' => 'Tanggal',
                'lokasi_logpond' => 'Lokasi Logpond',
                'keterangan' => 'Keterangan',
				'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanPembelianlog()
    {
        return $this->hasOne(TPengajuanPembelianlog::className(), ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']);
    }
	
	
	public function searchLaporan(){
		$query = self::find();
		$query->select('monitoring_pembelianlog_id, t_monitoring_pembelianlog.kode, t_pengajuan_pembelianlog.kode AS kode_pengajuan, t_monitoring_pembelianlog.tanggal, lokasi_logpond, nomor_kontrak, suplier_nm, waktu_penyerahan_awal, waktu_penyerahan_akhir');
		$query->join('JOIN', "t_pengajuan_pembelianlog",'t_pengajuan_pembelianlog.pengajuan_pembelianlog_id = t_monitoring_pembelianlog.pengajuan_pembelianlog_id');
		$query->join('JOIN', "m_suplier",'m_suplier.suplier_id = t_pengajuan_pembelianlog.suplier_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			't_monitoring_pembelianlog.kode DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_monitoring_pembelianlog.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->lokasi_logpond)){
			$query->andWhere("lokasi_logpond ILIKE '%".$this->lokasi_logpond."%'");
		}
		if(!empty($this->suplier_id)){
			$query->andWhere("t_pengajuan_pembelianlog.suplier_id  = ".$this->suplier_id);
		}
		return $query;
	}
	
	public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
		$wildinas = MWilayahDinas::tableName();
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
			array_push($param['where'],"t_monitoring_pembelianlog.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->lokasi_logpond)){
			array_push($param['where'],"lokasi_logpond ILIKE '%".$this->lokasi_logpond."%'");
		}
		if(!empty($this->suplier_id)){
			array_push($param['where'],"t_pengajuan_pembelianlog.suplier_id = '".$this->suplier_id."'");
		}
		
		return $param;
	}
}
