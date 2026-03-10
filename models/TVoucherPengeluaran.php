<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_voucher_pengeluaran".
 *
 * @property integer $voucher_pengeluaran_id
 * @property string $kode
 * @property string $tipe
 * @property string $tanggal
 * @property string $nomor_terkait
 * @property string $deskripsi
 * @property double $total_nominal
 * @property string $status_bayar
 * @property string $detail_item
 * @property boolean $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $suplier_id
 * @property string $cara_bayar
 * @property string $cara_bayar_reff
 * @property integer $akun_debit
 * @property string $tanggal_bayar
 * @property integer $cancel_transaksi_id
 * @property double $total_dpp
 * @property double $total_dp
 * @property double $total_sisa
 * @property double $total_ppn
 * @property double $total_pph
 * @property double $total_pembayaran
 * @property double $total_pbbkb
 * @property double $total_potongan
 * @property string $mata_uang
 * @property double $biaya_tambahan
 * @property string $status_drp
 * @property string $penerima_pembayaran
 *
 * @property TDpBhp[] $tDpBhps
 * @property TDpBhp[] $tDpBhps0
 * @property TPengeluaranKaskecil[] $tPengeluaranKaskecils
 * @property TTerimaBhp[] $tTerimaBhps
 * @property MAcctRekening $akunDebit
 * @property MSuplier $suplier
 * @property TCancelTransaksi $cancelTransaksi
 */ 
class TVoucherPengeluaran extends \app\models\DeltaBaseActiveRecord
{
	const SCENARIO_STATUS_PAID = 'scenarioStatusPaid';
    /**
     * @inheritdoc
     */
	public $totalkredit,$totaldebit,$accountdebet;
	public $suplier_nm,$urutan_kode,$ppk_id,$ppk_kode,$bkk_id,$bkk_kode,$gkk_id,$gkk_kode,$ovk_kode;
	public $ajuandinas_grader_id,$pdg_kode,$ajuanmakan_grader_id,$pmg_kode,$pdl_kode,$log_bayar_dp_id,$mlg_kode,$log_bayar_muat_id,$open_voucher_id;
	public $tgl_awal,$tgl_akhir;
	public $nama_bank, $rekening, $an_bank, $suplier_bank;
    public static function tableName()
    {
        return 't_voucher_pengeluaran';
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
            [['kode', 'tipe', 'tanggal', 'total_nominal', 'created_at', 'created_by', 'updated_at', 'updated_by', 'akun_debit'], 'required'],
            [['tanggal', 'created_at', 'updated_at', 'tanggal_bayar'], 'safe'],
            [['deskripsi', 'detail_item', 'penerima_pembayaran'], 'string'],
            [['total_nominal', 'total_dpp', 'total_dp', 'total_sisa', 'total_ppn', 'total_pph', 'total_pembayaran', 'total_pbbkb', 'total_potongan', 'biaya_tambahan'], 'number'],
            [['active'], 'boolean'],
            [['created_by', 'updated_by', 'suplier_id', 'akun_debit', 'cancel_transaksi_id'], 'integer'],
            [['kode', 'nomor_terkait', 'cara_bayar', 'cara_bayar_reff','mata_uang'], 'string', 'max' => 50],
            [['tipe', 'status_bayar'], 'string', 'max' => 20],
			[['status_drp'], 'string', 'max' => 25],
            [['kode'], 'checkUnikPertahun'],
            [['akun_debit'], 'exist', 'skipOnError' => true, 'targetClass' => MAcctRekening::className(), 'targetAttribute' => ['akun_debit' => 'acct_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
			[['tanggal_bayar'], 'required', 'on' => self::SCENARIO_STATUS_PAID],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
                'kode' => Yii::t('app', 'Kode'),
                'tipe' => Yii::t('app', 'Tipe'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'nomor_terkait' => Yii::t('app', 'Berkas Terkait'),
                'deskripsi' => Yii::t('app', 'Deskripsi'),
                'total_nominal' => Yii::t('app', 'Total Nominal'),
                'status_bayar' => Yii::t('app', 'Status Bayar'),
                'detail_item' => Yii::t('app', 'Detail Item'),
                'active' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
				'akun_debit' => Yii::t('app', 'Debt Account'),
				'suplier_id' => Yii::t('app', 'Supplier'),
				'cara_bayar_reff' => Yii::t('app', 'Reff Cara Bayar'),
				'akun_debit' => Yii::t('app', 'Akun Debit'),
                'tanggal_bayar' => Yii::t('app', 'Tanggal Bayar'),
				'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
				'total_dpp' => Yii::t('app', 'Total Dpp'),
                'total_dp' => Yii::t('app', 'Total Dp'),
                'total_sisa' => Yii::t('app', 'Total Sisa'),
                'total_ppn' => Yii::t('app', 'Total Ppn'),
                'total_pph' => Yii::t('app', 'Total Pph'),
                'total_pembayaran' => Yii::t('app', 'Total Pembayaran'),
				'mata_uang' => Yii::t('app', 'Mata Uang'),
				'biaya_tambahan' => Yii::t('app', 'Biaya Tambahan'),
				'status_drp' => Yii::t('app', 'Status Drp'),
				'penerima_pembayaran' => 'Penerima Pembayaran',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhps()
    {
        return $this->hasMany(TTerimaBhp::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    } 
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getAkunDebit()
    {
        return $this->hasOne(MAcctRekening::className(), ['acct_id' => 'akun_debit']);
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
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTVoucherPengeluarandetails()
    {
        return $this->hasMany(TVoucherPengeluarandetail::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    } 
	
	public function getDefaultValue()
    {
        return $this->hasOne(MDefaultValue::className(), ['value' => 'mata_uang']);
    } 

	public function getStatus_bayar(){
		if($this->status_bayar == "PAID"){
			return '<span class="label label-success">'.$this->status_bayar.'<span>';
		}else if($this->status_bayar == "UNPAID"){
			$pengajuanDrp = $this->pengajuanDrp;
            $statusApprove = $pengajuanDrp ? $pengajuanDrp->status_approve : null;
			
			if ($statusApprove == 'APPROVED'){
				if($this->statusPengajuan == 'Ditunda'){
					return '<span class="label label-warning">'.$this->status_bayar.'<span>';
				} else {
					return '<span class="label label-warning" style="cursor:pointer" onclick="changeStatus('.$this->voucher_pengeluaran_id.')">'.$this->status_bayar.'<span>'; 
				}
			} else {
				return '<span class="label label-warning">'.$this->status_bayar.'<span>';
			}
		}
	}

	public function getStatus_bayarLite(){
		if($this->status_bayar == "PAID"){
			return '<span class="label label-success" style="font-size: 9px;">'.$this->status_bayar.'<span>';
		}else if($this->status_bayar == "UNPAID"){
			return '<span class="label label-warning" style="cursor:pointer; font-size: 9px;" onclick="changeStatus('.$this->voucher_pengeluaran_id.')">'.$this->status_bayar.'<span>';
		}
	}

	public function searchLaporan() {
		$query = self::find();
		$query->select(self::tableName().".voucher_pengeluaran_id,
						".self::tableName().".kode, 
						tanggal_bayar, 
						".self::tableName().".cara_bayar, 
						m_suplier.suplier_nm, 
						(SELECT jsonb_array_elements(penerima_pembayaran::jsonb)->>'nama_bank' FROM t_voucher_pengeluaran AS sub
							WHERE sub.voucher_pengeluaran_id = ".self::tableName().".voucher_pengeluaran_id
							LIMIT 1) AS nama_bank, 
						(SELECT jsonb_array_elements(penerima_pembayaran::jsonb)->>'rekening' FROM t_voucher_pengeluaran AS sub
							WHERE sub.voucher_pengeluaran_id = ".self::tableName().".voucher_pengeluaran_id
							LIMIT 1) AS rekening,  
						".self::tableName().".cara_bayar, 
						t_voucher_pengeluarandetail.keterangan, 
						t_voucher_pengeluarandetail.jumlah, 
						acct_nm, 
						".self::tableName().".tipe, 
						cara_bayar_reff, 
						graderdinas.graderlog_nm AS graderdinas_nm, 
						gradermakan.graderlog_nm AS gradermakan_nm, 
						penerima AS penerimagkk,
						total_nominal,
                        openvoucher.tipe AS tipe_openvoucher,
                        openvouchersuplier.suplier_nm AS penerima_openvoucher,
                        openvouchersuplier.suplier_nm_company AS company_openvoucher,
						m_penerima_voucher.nama_penerima AS nama_penerima, 
					    m_penerima_voucher.nama_perusahaan AS nama_perusahaan,
						t_asuransi.kepada,
						t_voucher_pengeluaran.status_drp,
						(SELECT t_pengajuan_drp.status_approve
							FROM t_pengajuan_drp_detail 
							JOIN t_pengajuan_drp ON t_pengajuan_drp.pengajuan_drp_id = t_pengajuan_drp_detail.pengajuan_drp_id
							WHERE t_pengajuan_drp_detail.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
							AND status_approve <> 'REJECTED' ORDER BY pengajuan_drp_detail_id DESC LIMIT 1) AS status_approve,
						(SELECT t_pengajuan_drp_detail.status_pengajuan
							FROM t_pengajuan_drp_detail 
							JOIN t_pengajuan_drp ON t_pengajuan_drp.pengajuan_drp_id = t_pengajuan_drp_detail.pengajuan_drp_id
							WHERE t_pengajuan_drp_detail.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
							AND status_approve <> 'REJECTED' ORDER BY pengajuan_drp_detail_id DESC LIMIT 1) AS status_pengajuan,
						t_voucher_pengeluaran.mata_uang,
						t_voucher_pengeluaran.status_bayar,
						t_voucher_pengeluaran.tanggal_bayar,
						");
		$query->join('LEFT JOIN', 't_voucher_pengeluarandetail','t_voucher_pengeluarandetail.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_suplier','m_suplier.suplier_id = '.self::tableName().'.suplier_id');
		$query->join('LEFT JOIN', 't_ajuandinas_grader','t_ajuandinas_grader.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_graderlog AS graderdinas','graderdinas.graderlog_id = t_ajuandinas_grader.graderlog_id');
		$query->join('LEFT JOIN', 't_ajuanmakan_grader','t_ajuanmakan_grader.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_graderlog AS gradermakan','gradermakan.graderlog_id = t_ajuanmakan_grader.graderlog_id');
		$query->join('LEFT JOIN', 't_gkk AS gkk','gkk.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 't_open_voucher AS openvoucher','openvoucher.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_suplier AS openvouchersuplier','openvouchersuplier.suplier_id = openvoucher.penerima_reff_id');
		$query->join('LEFT JOIN', 'm_penerima_voucher','m_penerima_voucher.penerima_voucher_id = openvoucher.penerima_voucher_id');
		$query->join('JOIN', 'm_acct_rekening','m_acct_rekening.acct_id = '.self::tableName().'.akun_debit');
		$query->join('LEFT JOIN', 't_asuransi', 't_asuransi.kode = openvoucher.reff_no');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC, t_voucher_pengeluarandetail.voucher_detail_id ASC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal_bayar BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			$query->andWhere("t_voucher_pengeluaran.kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->tipe)){
			$query->andWhere(self::tableName().".tipe ILIKE '%".$this->tipe."%'");
		}
		if(!empty($this->status_bayar)){
			$query->andWhere("t_voucher_pengeluaran.status_bayar = '".$this->status_bayar."'");
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
			array_push($param['where'],"tanggal_bayar BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			array_push($param['where'],"t_voucher_pengeluaran.kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->tipe)){
			array_push($param['where'],self::tableName().".tipe ILIKE '%".$this->tipe."%'");
		}
		if(!empty($this->status_bayar)){
			array_push($param['where'],"t_voucher_pengeluaran.status_bayar = '".$this->status_bayar."'");
		}
		return $param;
	}
	

	public function searchLaporanPbb() {
		$query = self::find();
		$query->select(self::tableName().'.voucher_pengeluaran_id,
						'.self::tableName().'.kode, 
						tanggal_bayar, 
						'.self::tableName().'.cara_bayar, 
						m_suplier.suplier_nm, 
						m_suplier.suplier_bank,
                        m_suplier.suplier_norekening,
                        m_suplier.suplier_an_rekening, 
						t_voucher_pengeluarandetail.keterangan, 
						jumlah, 
						acct_nm, 
						'.self::tableName().'.tipe, 
						cara_bayar_reff, 
						graderdinas.graderlog_nm AS graderdinas_nm, 
						gradermakan.graderlog_nm AS gradermakan_nm, 
						penerima AS penerimagkk,
						total_nominal,
                        openvoucher.tipe AS tipe_openvoucher,
                        openvouchersuplier.suplier_nm AS penerima_openvoucher,
                        openvouchersuplier.suplier_nm_company AS company_openvoucher,
						t_voucher_pengeluaran.penerima_pembayaran,
						t_voucher_pengeluaran.status_bayar,
						t_voucher_pengeluaran.tanggal_bayar
						');
		$query->join('LEFT JOIN', 't_voucher_pengeluarandetail','t_voucher_pengeluarandetail.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_suplier','m_suplier.suplier_id = '.self::tableName().'.suplier_id');
		$query->join('LEFT JOIN', 't_ajuandinas_grader','t_ajuandinas_grader.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_graderlog AS graderdinas','graderdinas.graderlog_id = t_ajuandinas_grader.graderlog_id');
		$query->join('LEFT JOIN', 't_ajuanmakan_grader','t_ajuanmakan_grader.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_graderlog AS gradermakan','gradermakan.graderlog_id = t_ajuanmakan_grader.graderlog_id');
		$query->join('LEFT JOIN', 't_gkk AS gkk','gkk.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 't_open_voucher AS openvoucher','openvoucher.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', 'm_suplier AS openvouchersuplier','openvouchersuplier.suplier_id = openvoucher.penerima_reff_id');
		$query->join('JOIN', 'm_acct_rekening','m_acct_rekening.acct_id = '.self::tableName().'.akun_debit');

		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): self::tableName().'.created_at DESC, t_voucher_pengeluarandetail.voucher_detail_id ASC' );
		
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal_bayar BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->kode)){
			$query->andWhere("t_voucher_pengeluaran.kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->tipe)){
			$query->andWhere("openvoucher.tipe ILIKE '%".$this->tipe."%'");
		} else {
			$query->andWhere("( openvoucher.tipe = 'DP LOG SENGON' or openvoucher.tipe = 'PEMBAYARAN LOG ALAM' or openvoucher.tipe = 'PELUNASAN LOG SENGON')");
		}
		if(!empty($this->status_bayar)){
			$query->andWhere("t_voucher_pengeluaran.status_bayar = '".$this->status_bayar."'");
		}
		return $query;
	}
	
	public function searchLaporanPbbDt() {
		$searchLaporan = $this->searchLaporanPbb();
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
			array_push($param['where'],"tanggal_bayar BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}

		if (!empty($this->tipe)) {
			array_push($param['where']," openvoucher.tipe = '".$this->tipe."'");
		} else {
			array_push($param['where']," ( openvoucher.tipe = 'DP LOG SENGON' or openvoucher.tipe = 'PEMBAYARAN LOG ALAM' or openvoucher.tipe = 'PELUNASAN LOG SENGON') ");
		}

		if(!empty($this->kode)){
			array_push($param['where'],"t_voucher_pengeluaran.kode ILIKE '%".$this->kode."%'");
		}
		if(!empty($this->status_bayar)){
			array_push($param['where'],"t_voucher_pengeluaran.status_bayar = '".$this->status_bayar."'");
		}
		return $param;
	}


	// Validasi nomor sama jika pada tahun yang sama
	public function checkUnikPertahun($attribute, $params)
    {
		if(empty($this->voucher_pengeluaran_id)){
			$kode = $this->kode;
			$tahun_bayar = date("Y", strtotime($this->tanggal_bayar));
			$check = Yii::$app->db->createCommand("SELECT * FROM t_voucher_pengeluaran WHERE t_voucher_pengeluaran.kode = '{$kode}' AND EXTRACT(year FROM tanggal_bayar) = '{$tahun_bayar}'")->queryOne();
			if(!empty($check)){
				$this->addError($attribute, Yii::t('app', 'Kode "'.$kode.'" telah dipergunakan di tahun ini.'));
			}
		}
    }

	// untuk mengambil status approve mengubah unpaid (getStatus_bayar) di voucher pengeluaran
	public function getPengajuanDrp(){
		return $this->hasOne(TPengajuanDrp::className(), ['pengajuan_drp_id' => 'pengajuan_drp_id'])
			->via('pengajuanDrpDetails')
			->andWhere(['<>', 'status_approve', 'REJECTED']);
	}

	public function getPengajuanDrpDetails(){
		return $this->hasMany(TPengajuanDrpDetail::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
	}

	//mengambil status pengajuan untuk kondisi getStatus_bayar
	public function getStatusPengajuan(){
		$status = TPengajuanDrpDetail::find()
								->select('status_pengajuan')
								->join('join', 't_voucher_pengeluaran', 't_pengajuan_drp_detail.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id')
								->join('join', 't_pengajuan_drp', 't_pengajuan_drp.pengajuan_drp_id = t_pengajuan_drp_detail.pengajuan_drp_id')
								->where(['t_voucher_pengeluaran.voucher_pengeluaran_id'=>$this->voucher_pengeluaran_id])
								->andWhere(['<>', 'status_approve', 'REJECTED'])
								->orderBy('pengajuan_drp_detail_id DESC')
								->one();
		if($status){
			return $status['status_pengajuan'];
		} else {
			return '';
		}
	}
	
}
