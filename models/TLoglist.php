<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_loglist".
 *
 * @property integer $loglist_id
 * @property integer $log_kontrak_id
 * @property string $grader_id
 * @property string $loglist_kode
 * @property string $tanggal
 * @property string $tongkang
 * @property string $lokasi_muat
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $model_ukuran_loglist
 *
 * @property TLogKontrak $logKontrak
 */
class TLoglist extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $kode_po,$nomor_kontrak;
	public $tgl_awal, $tgl_akhir, $kayu_id, $volume_range, $pihak1_perusahaan;
	public $nomor_grd, $nomor_produksi, $nomor_batang, $nomor, $kayu_nama, $pcs, $volume_value;
    public static function tableName()
    {
        return 't_loglist';
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
            [['pengajuan_pembelianlog_id','log_kontrak_id', 'grader_id', 'loglist_kode', 'kode_bajg', 'tongkang', 'lokasi_muat', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['pengajuan_pembelianlog_id','log_kontrak_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['grader_id', 'lokasi_muat','model_ukuran_loglist'], 'string', 'max' => 150],
            [['loglist_kode', 'kode_bajg'], 'string', 'max' => 30],
            [['tongkang'], 'string', 'max' => 100],
            [['pengajuan_pembelianlog_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPengajuanPembelianlog::className(), 'targetAttribute' => ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']],
            [['log_kontrak_id'], 'exist', 'skipOnError' => true, 'targetClass' => TLogKontrak::className(), 'targetAttribute' => ['log_kontrak_id' => 'log_kontrak_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'loglist_id' => Yii::t('app', 'Log List'),
                'pengajuan_pembelianlog_id' => Yii::t('app', 'Kode Keputusan'),
                'log_kontrak_id' => Yii::t('app', 'Nomor Kontrak'),
                'grader_id' => Yii::t('app', 'Grader'),
                'loglist_kode' => Yii::t('app', 'Kode Log List'),
                'kode_bajg' => Yii::t('app', 'Kode BAJG'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'tongkang' => Yii::t('app', 'Tongkang'),
                'lokasi_muat' => Yii::t('app', 'Lokasi Muat'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'model_ukuran_loglist' => Yii::t('app', 'Model Ukuran Loglist'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajuanPembelianlog()
    {
        return $this->hasOne(TPengajuanPembelianlog::className(), ['pengajuan_pembelianlog_id' => 'pengajuan_pembelianlog_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogKontrak()
    {
        return $this->hasOne(TLogKontrak::className(), ['log_kontrak_id' => 'log_kontrak_id']);
    }
	
	public static function getOptionList()
    {
        $res = self::find()->orderBy('created_at DESC')->all();
		$map = [];
		foreach($res as $i => $resval){
			$map[$resval->loglist_id] = $resval->loglist_kode.' - '.$resval->logKontrak->nomor;
		}
        return $map;
    }
	public static function getOptionListNotIn($alreadyitem)
    {
		if(!empty($alreadyitem)){
			$res = self::find()->where("loglist_id NOT IN($alreadyitem)")->orderBy('created_at DESC')->all();
		}else{
			$res = self::find()->orderBy('created_at DESC')->all();
		}
		$map = [];
		foreach($res as $i => $resval){
			$map[$resval->loglist_id] = $resval->loglist_kode.' - '.$resval->logKontrak->nomor;
		}
        return $map;
    }
	
	public static function getOptionListPelunasanLog()
    {
        $res = self::find()->where("loglist_id NOT IN( SELECT loglist_id FROM t_log_bayar_muat )")->orderBy('created_at DESC')->all();
		$map = [];
		foreach($res as $i => $resval){
			$map[$resval->loglist_id] = $resval->loglist_kode.' - '.$resval->logKontrak->nomor;
		}
        return $map;
    }
	
	public static function getOptionListIncomingDKB()
    {
        $res = self::find()->orderBy('created_at DESC')->all();
		$map = [];
		foreach($res as $i => $resval){
			$map[$resval->loglist_id] = $resval->loglist_kode.' - '.$resval->logKontrak->pihak1_perusahaan;
		}
        return $map;
    }

    public static function getOptionListAdjustment($pengajuan_pembelianlog_id)
    {
        $res = self::find()->where("pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."")->orderBy("created_at DESC")->all();
		$map = [];
		foreach($res as $i => $resval){
			$map[$resval->loglist_id] = $resval->loglist_kode.' - '.$resval->logKontrak->nomor;
		}
        return $map;
    }

    public function searchLaporan(){
		$log_kontrak = TLogKontrak::tableName();
		$pengajuan_pembelianlog = TPengajuanPembelianlog::tableName();
		$query = self::find();
        
        //select  c.kode, c.nomor, b.kode, a.loglist_kode, a.tanggal, a.tongkang, a.lokasi_muat, a.model_ukuran_loglist, a.area_pembelian
        ///from t_loglist a
        //join t_log_kontrak b on b.log_kontrak_id = a.log_kontrak_id
        //join t_pengajuan_pembelianlog c on c.pengajuan_pembelianlog_id = a.pengajuan_pembelianlog_id
        //order by tanggal desc

		//$query->select('t_loglist.tanggal, t_loglist.loglist_kode, t_log_kontrak.nomor, t_loglist.tongkang, t_loglist.lokasi_muat, t_loglist.model_ukuran_loglist, t_loglist.area_pembelian, t_loglist.loglist_id');
        $query->select('t_loglist.tanggal, t_loglist.loglist_kode, t_log_kontrak.nomor, t_loglist.tongkang, t_loglist.lokasi_muat, t_log_kontrak.pihak1_perusahaan, t_loglist.area_pembelian, t_loglist.loglist_id');
		$query->join('JOIN', $log_kontrak, $log_kontrak.'.log_kontrak_id = '.self::tableName().'.log_kontrak_id');
		$query->join('JOIN', $pengajuan_pembelianlog, $pengajuan_pembelianlog.'.pengajuan_pembelianlog_id = '.self::tableName().'.pengajuan_pembelianlog_id');
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : 'tanggal DESC' );
		if(!empty($this->loglist_kode)){
			$query->andWhere("loglist_kode ILIKE '%".$this->loglist_kode."%'");
		}
		if(!empty($this->nomor)){
			$query->andWhere("nomor ILIKE '%".$this->nomor."%'");
		}
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->tongkang)){
			$query->andWhere("tongkang  = ".$this->tongkang);
		}
		if(!empty($this->lokasi_muat)){
			$query->andWhere("lokasi_muat = '".$this->lokasi_muat."'");
		}
        /*if(!empty($this->model_ukuran_loglist)){
			$query->andWhere("model_ukuran_loglist = '".$this->model_ukuran_loglist."'");
		}*/
        if(!empty($this->suplier_id)){
			$query->andWhere("t_pengajuan_pembelianlog.suplier_id = ".$this->suplier_id." ");
        }
        if(!empty($this->area_pembelian)){
			$query->andWhere("area_pembelian = '".$this->area_pembelian."'");
		}
		return $query;
	}
	
	public function searchLaporanDt(){
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
		$log_kontrak = TLogKontrak::tableName();
		$pengajuan_pembelianlog = TPengajuanPembelianlog::tableName();
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
		if(!empty($this->loglist_kode)){
			array_push($param['where'],"t_loglist.loglist_kode ILIKE '%".$this->loglist_kode."%'");
		}
		if(!empty($this->nomor)){
			array_push($param['where'],"t_log_kontrak.nomor ILIKE '%".$this->nomor."%'");
		}
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_loglist.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->tongkang)){
			array_push($param['where'],"t_loglist.tongkang ILIKE '%".$this->tongkang."%'");
		}
		if(!empty($this->lokasi_muat)){
			array_push($param['where'],"t_loglist.lokasi_muat ILIKE '%".$this->lokasi_muat."%'");
		}
		/*if(!empty($this->model_ukuran_loglist)){
			array_push($param['where'],"t_loglist.model_ukuran_loglist ILIKE '%".$this->model_ukuran_loglist."%'");
		}*/
        if(!empty($this->suplier_id)){
			array_push($param['where'],"t_pengajuan_pembelianlog.suplier_id = ".$this->suplier_id." ");
		}
		if(!empty($this->area_pembelian)){
			array_push($param['where'],"t_loglist.area_pembelian = '".$this->area_pembelian."'");
		}
		return $param;
	}

	public function searchLaporanRekap(){
		$query = self::find();
		$query->select('tongkang, nomor, pihak1_perusahaan, kayu_nama, count(*) as pcs, volume_range, SUM(volume_value) as volume_value'); 
		$query->join('JOIN', 't_loglist_detail', 't_loglist.loglist_id = t_loglist_detail.loglist_id');
		$query->join('JOIN', 'm_kayu', 'm_kayu.kayu_id = t_loglist_detail.kayu_id');
		$query->join('JOIN', 't_log_kontrak', 't_log_kontrak.log_kontrak_id = t_loglist.log_kontrak_id');
		$query->groupBy('nomor, pihak1_perusahaan, kayu_nama, volume_range, tongkang'); 
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'nomor ASC, pihak1_perusahaan ASC, kayu_nama, volume_range ASC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_loglist.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kayu_id)){
			$query->andWhere("t_loglist_detail.kayu_id  = '".$this->kayu_id."'");
		}
		if(!empty($this->volume_range)){
			$query->andWhere("volume_range  = '".$this->volume_range."'");
		}
		if(!empty($this->pihak1_perusahaan)){
			$query->andWhere("pihak1_perusahaan ilike '%".$this->pihak1_perusahaan."%'");
		}
		if(!empty($this->log_kontrak_id)){
			$query->andWhere("t_log_kontrak.log_kontrak_id = ".$this->log_kontrak_id);
		}
		if(!empty($this->tongkang)){
			$query->andWhere("tongkang ilike '%".$this->tongkang."%'");
		}
		return $query;
	}

	public function searchLaporanRekapDt(){
		$searchLaporan = $this->searchLaporanRekap();
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
			array_push($param['where'],"t_loglist.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kayu_id)){
			array_push($param['where'],"t_loglist_detail.kayu_id = ".$this->kayu_id);
		}
		if(!empty($this->volume_range)){
			array_push($param['where'],"volume_range = '".$this->volume_range."'");
		}
		if(!empty($this->pihak1_perusahaan)){
			array_push($param['where'],"pihak1_perusahaan ilike '%".$this->pihak1_perusahaan."%'");
		}
		if(!empty($this->log_kontrak_id)){
			array_push($param['where'],"t_log_kontrak.log_kontrak_id = ".$this->log_kontrak_id);
		}
		if(!empty($this->tongkang)){
			array_push($param['where'],"tongkang ilike '%".$this->tongkang."%'");
		}
		
		return $param;
	}

	public static function getOptionListTongkang(){
		$res = Yii::$app->db->createCommand("SELECT tongkang FROM t_loglist GROUP BY tongkang ORDER BY 1")->queryAll();
        return \yii\helpers\ArrayHelper::map($res, 'tongkang', 'tongkang');
	}
}
