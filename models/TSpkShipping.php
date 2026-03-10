<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spk_shipping".
 *
 * @property integer $spk_shipping_id
 * @property string $kode
 * @property string $tanggal
 * @property string $nama_tongkang
 * @property string $etd
 * @property string $eta_logpond
 * @property string $eta
 * @property string $lokasi_muat
 * @property double $estimasi_total_batang
 * @property double $estimasi_total_m3
 * @property boolean $asuransi
 * @property integer $pic_shipping
 * @property integer $by_kanit
 * @property integer $by_kadiv
 * @property string $approve_reason
 * @property string $reject_reason
 * @property string $status
 * @property string $keterangan
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $status_jenis
 *
 * @property TPengajuanPembelianlog[] $tPengajuanPembelianlogs
 * @property MPegawai $picShipping
 * @property MPegawai $byKanit
 * @property MPegawai $byKadiv
 */
class TSpkShipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tgl_awal,$tgl_akhir;
    public static function tableName()
    {
        return 't_spk_shipping';
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
            [['kode', 'tanggal', 'nama_tongkang', 'etd', 'eta_logpond', 'eta', 'lokasi_muat', 'asuransi', 'pic_shipping', 'by_kanit', 'by_kadiv', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'etd', 'eta_logpond', 'eta', 'created_at', 'updated_at'], 'safe'],
            [['estimasi_total_batang', 'estimasi_total_m3'], 'number'],
            [['asuransi'], 'boolean'],
            [['pic_shipping', 'by_kanit', 'by_kadiv', 'cancel_transaksi_id', 'created_by', 'updated_by', 'status_jenis'], 'integer'],
            [['approve_reason', 'reject_reason', 'keterangan'], 'string'],
            [['kode', 'status'], 'string', 'max' => 25],
            [['nama_tongkang'], 'string', 'max' => 200],
            [['lokasi_muat'], 'string', 'max' => 300],
            [['pic_shipping'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pic_shipping' => 'pegawai_id']],
            [['by_kanit'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['by_kanit' => 'pegawai_id']],
            [['by_kadiv'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['by_kadiv' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spk_shipping_id' => 'Spk Shipping',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'nama_tongkang' => 'Nama Tongkang',
                'etd' => 'ETD to Logpond',
                'eta_logpond' => 'ETA Logpond',
                'eta' => 'ETA Tanjung Mas',
                'lokasi_muat' => 'Lokasi Muat',
                'estimasi_total_batang' => 'Est. Total Pcs',
                'estimasi_total_m3' => 'Est. Total M3',
                'asuransi' => 'Asuransi',
                'pic_shipping' => 'Pic Shipping',
                'by_kanit' => 'By Kanit',
                'by_kadiv' => 'By Kadiv',
                'approve_reason' => 'Approve Reason',
                'reject_reason' => 'Reject Reason',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'status_jenis' => 'Status Jenis',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPengajuanPembelianlogs()
    {
        return $this->hasMany(TPengajuanPembelianlog::className(), ['spk_shipping_id' => 'spk_shipping_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicShipping()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pic_shipping']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByKanit()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'by_kanit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getByKadiv()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'by_kadiv']);
    }

    /**
     * @inheritdoc
     * @return TSpkShippingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TSpkShippingQuery(get_called_class());
    }

    public static function getOptionList()
    {
        $res = self::find()->where(['status'=>'APPROVED','status_jenis'=>1])->orderBy('tanggal ASC')->all();
		$ret = [];
		foreach($res as $i => $kolom){
			$ret[$kolom['spk_shipping_id']] = $kolom['kode'];
			
		}
        return $ret;
    }

    public function searchLaporan(){
		$query = self::find();
        
        $query->select('spk_shipping_id, kode, tanggal, nama_tongkang, etd, eta_logpond, eta, lokasi_muat, estimasi_total_batang, estimasi_total_m3, asuransi, pegawai_nama, keterangan, status, status_jenis');
        $query->join('JOIN', 'm_pegawai', 'm_pegawai.pegawai_id = '.self::tableName().'.pic_shipping');
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : 'tanggal DESC' );
		if(!empty($this->kode)){
			$query->andWhere("kode ILIKE '%".$this->kode."%'");
		}
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nama_tongkang)){
			$query->andWhere("nama_tongkang  = ".$this->nama_tongkang);
		}
		if(!empty($this->lokasi_muat)){
			$query->andWhere("lokasi_muat = '".$this->lokasi_muat."'");
		}
        if(!empty($this->pic_shipping)){
			$query->andWhere("pic_shipping = ".$this->pic_shipping." ");
        }
        if(!empty($this->status)){
			$query->andWhere("status = ".$this->status." ");
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
			array_push($param['where'],"kode ILIKE '%".$this->kode."%'");
		}
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nama_tongkang)){
			array_push($param['where'],"nama_tongkang ILIKE '%".$this->nama_tongkang."%'");
		}
		if(!empty($this->lokasi_muat)){
			array_push($param['where'],"lokasi_muat ILIKE '%".$this->lokasi_muat."%'");
		}
        if(!empty($this->pic_shipping)){
			array_push($param['where'],"pic_shipping = ".$this->pic_shipping." ");
		}
        if(!empty($this->status)){
			array_push($param['where'],"status = '".$this->status."' ");
		}
		return $param;
	}

    public static function getOptionListLoglist()
    {
		$map = [];
        $res = self::find()
                ->where("spk_shipping_id NOT IN (SELECT spk_shipping_id FROM t_spk_shipping WHERE cancel_transaksi_id IS NULL)")
                ->orderBy('created_at DESC')->all();
		foreach($res as $i => $resval){
            $approved = false;
            $cekapproval = TApproval::find()->where("reff_no = '{$resval->kode}' ")->one();
            if(!empty($cekapproval)){
                $approved = ($cekapproval->status == TApproval::STATUS_APPROVED)?true:false;
            }
			if($approved==true){
				$map[$resval->spk_shipping_id] = $resval->kode."-".$resval->revisi.' - '.$resval->nomor_kontrak;
			}
		}
        return $map;
    }

    public static function getOptionListSpkShipping()
    {
		$map = [];
        $res = self::find()
                ->where("spk_shipping_id IN (SELECT spk_shipping_id FROM t_spk_shipping WHERE cancel_transaksi_id IS NULL)")
                ->orderBy('created_at DESC')->all();
		foreach($res as $i => $resval){
            $map[$resval->spk_shipping_id] = $resval->kode;
		}
        return $map;
    }

    public static function getOptionListSpkShippingAdjustment($spk_shipping_id)
    {
        $res = self::find()->where("cancel_transaksi_id is NULL and spk_shipping_id = ".$spk_shipping_id."")->orderBy("created_at DESC")->all();
        $map = [];
        foreach($res as $i => $resval){
            $map[$resval->spk_shipping_id] = $resval->kode;
        }
        return $map;
    }
}
