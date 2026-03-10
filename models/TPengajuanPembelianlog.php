<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_pembelianlog".
 *
 * @property integer $pengajuan_pembelianlog_id
 * @property string $kode
 * @property integer $revisi
 * @property string $tanggal
 * @property string $nomor_kontrak
 * @property double $volume_kontrak
 * @property integer $suplier_id
 * @property string $asal_kayu
 * @property double $total_volume
 * @property string $term_of_price
 * @property string $waktu_penyerahan_awal
 * @property string $waktu_penyerahan_akhir
 * @property string $lokasi_muat
 * @property boolean $asuransi
 * @property double $nominal_dp
 * @property string $tanggal_bayar_dp
 * @property string $status
 * @property string $keterangan
 * @property string $history_revisi
 * @property string $approve_reason
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $log_kontrak_id
 * @property integer $by_owner
 * @property string $keterangan_pembelian
 * @property string $status_fsc
 *
 * @property MSuplier $suplier
 * @property TCancelTransaksi $cancelTransaksi
 */
class TPengajuanPembelianlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $suplier_nm,$by_kanit_name,$by_kadiv_name,$by_gmopr_name,$by_gmpurch_name,$by_dirut_name,$by_owner_name,$kode_po,$tgl_awal,$tgl_akhir,$total_permintaan;
    public static function tableName()
    {
        return 't_pengajuan_pembelianlog';
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
            [['kode', 'revisi', 'tanggal', 'nomor_kontrak', 'volume_kontrak', 'suplier_id', 'asal_kayu', 'total_volume', 'term_of_price', 'waktu_penyerahan_awal', 'waktu_penyerahan_akhir', 'lokasi_muat', 'asuransi', 'by_kanit', 'by_kadiv', 'by_gmopr', 'by_gmpurch', 'by_dirut', 'created_at', 'created_by', 'updated_at', 'updated_by', 'log_kontrak_id', 'by_owner'], 'required'],
            [['revisi', 'suplier_id', 'cancel_transaksi_id', 'created_by', 'updated_by', 'by_kanit', 'by_kadiv', 'by_gmopr', 'by_gmpurch', 'by_dirut','log_kontrak_id', 'by_owner'], 'integer'],
            [['tanggal', 'waktu_penyerahan_awal', 'waktu_penyerahan_akhir', 'tanggal_bayar_dp', 'created_at', 'updated_at','volume_kontrak', 'total_volume','nominal_dp'], 'safe'],
            [[], 'number'],
			[['keterangan','history_revisi','approve_reason','keterangan_pembelian'], 'string'],
            [['asuransi'], 'boolean'],
            [['kode', 'status'], 'string', 'max' => 25],
            [['nomor_kontrak', 'asal_kayu', 'term_of_price', 'lokasi_muat'], 'string', 'max' => 200],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['log_kontrak_id'], 'exist', 'skipOnError' => true, 'targetClass' => TLogKontrak::className(), 'targetAttribute' => ['log_kontrak_id' => 'log_kontrak_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_pembelianlog_id' => 'Pengajuan Pembelianlog',
                'kode' => 'Kode',
                'revisi' => 'Revisi',
                'tanggal' => 'Tanggal',
                'nomor_kontrak' => 'Nomor Kontrak',
                'volume_kontrak' => 'Volume Kontrak',
                'suplier_id' => 'Suplier',
                'asal_kayu' => 'Asal Kayu',
                'total_volume' => 'Total Volume',
                'term_of_price' => 'Term Of Price',
                'waktu_penyerahan_awal' => 'Waktu Penyerahan Awal',
                'waktu_penyerahan_akhir' => 'Waktu Penyerahan Akhir',
                'lokasi_muat' => 'Lokasi Muat',
                'asuransi' => 'Asuransi',
                'nominal_dp' => 'Nominal Dp',
                'tanggal_bayar_dp' => 'Tanggal Bayar Dp',
                'status' => 'Status',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
				'by_kanit_name' => 'Prepared By Kanit Log Purch', 
				'by_kadiv_name' => 'Reviewed By Kadiv Mkt', 
				'by_gmopr_name' => 'Reviewed By GM Opr', 
				'by_gmpurch_name' => 'Reviewed By GM Purch', 
				'by_dirut_name' => 'Reviewed By Direktur Utama',
				'by_owner' => 'Approved By Owner',
				'keterangan' => 'Keterangan',
				'keterangan_pembelian' => 'Keterangan Pembelian',
                'status_fsc' => 'Status FSC'
        ];
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
    public function getLogKontrak()
    {
        return $this->hasOne(TLogKontrak::className(), ['log_kontrak_id' => 'log_kontrak_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
	
	public function searchLaporan(){
		$query = self::find();
		$query->select('pengajuan_pembelianlog_id, t_pengajuan_pembelianlog.kode, revisi, t_pengajuan_pembelianlog.tanggal, t_log_kontrak.kode AS kode_po, nomor_kontrak, suplier_nm, waktu_penyerahan_awal, waktu_penyerahan_akhir');
		$query->join('JOIN', "t_log_kontrak",'t_log_kontrak.log_kontrak_id = t_pengajuan_pembelianlog.log_kontrak_id');
		$query->join('JOIN', "m_suplier",'m_suplier.suplier_id = t_pengajuan_pembelianlog.suplier_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			't_pengajuan_pembelianlog.kode DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_pengajuan_pembelianlog.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
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
			array_push($param['where'],"t_pengajuan_pembelianlog.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->suplier_id)){
			array_push($param['where'],"t_pengajuan_pembelianlog.suplier_id = '".$this->suplier_id."'");
		}
		
		return $param;
	}
	
	public static function getOptionListLoglist()
    {
		$map = [];
        $res = self::find()
                //->where("pengajuan_pembelianlog_id NOT IN (SELECT pengajuan_pembelianlog_id FROM t_loglist WHERE cancel_transaksi_id IS NULL)")
                ->where("1=1")
                ->andWhere("status = 'APPROVED' ")
                ->andWhere('cancel_transaksi_id is null')
                ->andWhere('pengajuan_pembelianlog_id not in (select pengajuan_pembelianlog_id from t_loglist where t_pengajuan_pembelianlog.pengajuan_pembelianlog_id = t_loglist.pengajuan_pembelianlog_id) ')
                ->orderBy('created_at DESC')->all();
		foreach($res as $i => $resval){
//			$cekapproval = TApproval::find()->where("reff_no = '{$resval->kode}'")->all();
//			$approved = true;
//			foreach($cekapproval as $i => $apprv){
//				if($apprv->status == TApproval::STATUS_APPROVED){
//					$approved &= true;
//				}else{
//					$approved = false;
//				}
//			}
             // Keputusan terakhir pada Dirut
            $approved = false;
            $cekapproval = TApproval::find()->where("reff_no = '{$resval->kode}' AND assigned_to = ".\app\components\Params::DEFAULT_PEGAWAI_ID_JENNY_CHANDRA)->one();
            if(!empty($cekapproval)){
                $approved = ($cekapproval->status == TApproval::STATUS_APPROVED)?true:false;
            }
			if($approved==true){
				$map[$resval->pengajuan_pembelianlog_id] = $resval->kode."-".$resval->revisi.' - '.$resval->nomor_kontrak;
			}
		}
        return $map;
    }

    public static function getOptionListLoglistAdjustment()
    {
		$map = [];
        $res = self::find()
                //->where("pengajuan_pembelianlog_id NOT IN (SELECT pengajuan_pembelianlog_id FROM t_loglist WHERE cancel_transaksi_id IS NULL)")
                ->where("1=1")
                ->andWhere("status = 'APPROVED' ")
                ->andWhere('cancel_transaksi_id is null')
                ->andWhere('pengajuan_pembelianlog_id in (select pengajuan_pembelianlog_id from t_loglist where t_pengajuan_pembelianlog.pengajuan_pembelianlog_id = t_loglist.pengajuan_pembelianlog_id) ')
                ->orderBy('created_at DESC')->all();
		foreach($res as $i => $resval){
            $approved = false;
            $cekapproval = TApproval::find()->where("reff_no = '{$resval->kode}' AND assigned_to = ".\app\components\Params::DEFAULT_PEGAWAI_ID_JENNY_CHANDRA)->one();
            if(!empty($cekapproval)){
                $approved = ($cekapproval->status == TApproval::STATUS_APPROVED)?true:false;
            }
			if($approved==true){
				$map[$resval->pengajuan_pembelianlog_id] = $resval->kode."-".$resval->revisi.' - '.$resval->nomor_kontrak;
			}
		}
        return $map;
    }

    public static function getOptionListPenerimaanLogAlam()
    {
		$map = [];
        $res = self::find()
            ->where("1=1")
            ->andWhere("spk_shipping_id is null")
            ->andWhere("status = 'APPROVED' ")
            ->andWhere("cancel_transaksi_id is NULL")
            ->orderBy('created_at DESC')->all();
		foreach($res as $i => $resval){
            $approved = false;
            $cekapproval = TApproval::find()->where("reff_no = '{$resval->kode}' AND assigned_to = ".\app\components\Params::DEFAULT_PEGAWAI_ID_JENNY_CHANDRA)->one();
            if(!empty($cekapproval)){
                $approved = ($cekapproval->status == TApproval::STATUS_APPROVED)?true:false;
            }
			if($approved==true){
				$map[$resval->pengajuan_pembelianlog_id] = $resval->kode."-".$resval->revisi.' - '.$resval->nomor_kontrak;
			}
		}
        return $map;
    }

    public static function getOptionListPenerimaanLogAlamLuarJawa($spk_shipping_id)
    {
		$map = [];
        $res = self::find()
            ->where("1=1")
            ->andWhere("spk_shipping_id = ".$spk_shipping_id."")
            ->andWhere("status = 'APPROVED' ")
            ->andWhere("cancel_transaksi_id is NULL")
            ->orderBy('created_at DESC')->all();
		foreach($res as $i => $resval){
            $approved = false;
            $suplier_id = $resval->suplier_id;
            $suplier_nm = Yii::$app->db->createCommand("select suplier_nm from m_suplier where suplier_id = ".$suplier_id."")->queryScalar();
            $cekapproval = TApproval::find()->where("reff_no = '{$resval->kode}' AND assigned_to = ".\app\components\Params::DEFAULT_PEGAWAI_ID_JENNY_CHANDRA)->one();
            if(!empty($cekapproval)){
                $approved = ($cekapproval->status == TApproval::STATUS_APPROVED)?true:false;
            }
			if($approved==true){
				$map[$resval->pengajuan_pembelianlog_id] = $resval->kode."-".$resval->revisi.' - '.$resval->nomor_kontrak.' - '.$suplier_nm.' - '.$resval->asal_kayu;
			}
		}
        return $map;
    }
}
