<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_log_kontrak".
 *
 * @property integer $log_kontrak_id
 * @property string $kode
 * @property string $nomor
 * @property string $tanggal
 * @property string $tanggal_po
 * @property string $pihak1_nama
 * @property string $pihak1_perusahaan
 * @property string $pihak1_alamat
 * @property integer $pihak2_pegawai
 * @property integer $pihak2_pegawai2
 * @property string $pihak2_perusahaan
 * @property string $pihak2_alamat
 * @property string $jenis_log
 * @property string $asal_log
 * @property string $kuantitas
 * @property string $kualitas
 * @property string $komposisi
 * @property string $hargafob
 * @property string $lokasi_muat
 * @property string $uploadfile
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $suplier_id
 * @property integer $hasil_orientasi_id
 *
 * @property MPegawai $pihak2Pegawai
 * @property MPegawai $pihak2Pegawai2
 * @property MSuplier $suplier
 */
class TLogKontrak extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $file1,$tgl_awal,$tgl_akhir,$nama_iuphhk,$nama_ipk;
    public $keputusan_terkait;
    public static function tableName()
    {
        return 't_log_kontrak';
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
            [['kode', 'nomor', 'tanggal', 'tanggal_po', 'pihak1_nama', 'pihak1_perusahaan', 'pihak2_pegawai', 'pihak2_perusahaan', 'jenis_log', 'kuantitas', 'kualitas', 'hargafob', 'created_at', 'created_by', 'updated_at', 'updated_by', 'suplier_id','hasil_orientasi_id'], 'required'],
            [['kode', 'nomor', 'pihak1_nama', 'pihak1_perusahaan', 'pihak1_alamat', 'pihak2_perusahaan', 'pihak2_alamat', 'jenis_log', 'asal_log', 'kuantitas', 'kualitas', 'komposisi', 'hargafob', 'lokasi_muat', 'uploadfile', 'term_of_price'], 'string'],
            [['tanggal', 'tanggal_po', 'created_at', 'updated_at'], 'safe'],
            [['is_ppn10'], 'boolean'],
            [['pihak2_pegawai', 'pihak2_pegawai2', 'created_by', 'updated_by', 'suplier_id','hasil_orientasi_id'], 'integer'],
            [['pihak2_pegawai'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pihak2_pegawai' => 'pegawai_id']],
            [['pihak2_pegawai2'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pihak2_pegawai2' => 'pegawai_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['hasil_orientasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => THasilOrientasi::className(), 'targetAttribute' => ['hasil_orientasi_id' => 'hasil_orientasi_id']],
        ];
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'log_kontrak_id' => Yii::t('app', 'Log Kontrak'),
			'kode' => Yii::t('app', 'Kode PO'),
			'nomor' => Yii::t('app', 'Nomor Kontrak'),
			'tanggal' => Yii::t('app', 'Tanggal Kontrak'),
			'tanggal_po' => Yii::t('app', 'Tanggal PO'),
			'pihak1_nama' => Yii::t('app', 'Nama'),
			'pihak1_perusahaan' => Yii::t('app', 'Perusahaan'),
			'pihak1_alamat' => Yii::t('app', 'Alamat'),
			'pihak2_pegawai' => Yii::t('app', 'Direktur Utama'),
			'pihak2_pegawai2' => Yii::t('app', 'Kadiv Purchasing Log'),
			'pihak2_perusahaan' => Yii::t('app', 'Perusahaan'),
			'pihak2_alamat' => Yii::t('app', 'Alamat'),
			'jenis_log' => Yii::t('app', 'Jenis Log'),
			'asal_log' => Yii::t('app', 'Asal Kayu'),
			'kuantitas' => Yii::t('app', 'Kuantitas'),
			'kualitas' => Yii::t('app', 'Kualitas'),
			'komposisi' => Yii::t('app', 'Diameter / Komposisi'),
			'hargafob' => Yii::t('app', 'Harga FOB'),
			'lokasi_muat' => Yii::t('app', 'Lokasi Pemuatan'),
			'uploadfile' => Yii::t('app', 'File Kontrak'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'term_of_price' => Yii::t('app', 'Term Of Price'),
            'is_ppn10' => Yii::t('app', 'PPn 10%'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTLogBayarDps()
    {
        return $this->hasMany(TLogBayarDp::className(), ['log_kontrak_id' => 'log_kontrak_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTLogBayarMuats()
    {
        return $this->hasMany(TLogBayarMuat::className(), ['log_kontrak_id' => 'log_kontrak_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPihak2Pegawai()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pihak2_pegawai']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPihak2Pegawai2()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pihak2_pegawai2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuplier()
    {
        return $this->hasOne(MSuplier::className(), ['suplier_id' => 'suplier_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHasilOrientasi()
    {
        return $this->hasOne(THasilOrientasi::className(), ['hasil_orientasi_id' => 'hasil_orientasi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTLoglists()
    {
        return $this->hasMany(TLoglist::className(), ['log_kontrak_id' => 'log_kontrak_id']);
    } 
	
	
	public static function getOptionList(){
		$res = self::find()->orderBy('nomor ASC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'log_kontrak_id', 'nomor');
	}
	
	public static function getOptionListPO(){
		$ret = [];
		$res = self::find()->orderBy('created_at DESC')->all();
		if(count($res)>0){
			foreach($res as $i => $asd){
				$ret[$asd->log_kontrak_id] = $asd->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($asd->tanggal_po);
			}
		}
        return $ret;
	}
	
	public function searchLaporan(){
		$query = self::find();
		$query->select('log_kontrak_id, nomor, tanggal, pihak1_nama, pihak1_perusahaan, jenis_log, kualitas, hargafob');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nomor)){
			$query->andWhere("biaya_grader_kode ILIKE '%".$this->nomor."%'");
		}
		if(!empty($this->pihak1_nama)){
			$query->andWhere("pihak1_nama ILIKE '%".$this->pihak1_nama."%'");
		}
		if(!empty($this->pihak1_perusahaan)){
			$query->andWhere("pihak1_perusahaan ILIKE '%".$this->pihak1_perusahaan."%'");
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
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->nomor)){
			array_push($param['where'],"nomor ILIKE '%".$this->nomor."%'");
		}
		if(!empty($this->pihak1_nama)){
			array_push($param['where'],"pihak1_nama ILIKE '%".$this->pihak1_nama."%'");
		}
		if(!empty($this->pihak1_perusahaan)){
			array_push($param['where'],"pihak1_perusahaan ILIKE '%".$this->pihak1_perusahaan."%'");
		}
		
		return $param;
	}

	public static function getOptionListPerusahaan(){
		$res = Yii::$app->db->createCommand("SELECT pihak1_perusahaan FROM t_log_kontrak GROUP BY pihak1_perusahaan ORDER BY 1")->queryAll();
        return \yii\helpers\ArrayHelper::map($res, 'pihak1_perusahaan', 'pihak1_perusahaan');
	}
}
