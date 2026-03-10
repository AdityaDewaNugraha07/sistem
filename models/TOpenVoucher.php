<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_open_voucher".
 *
 * @property integer $open_voucher_id
 * @property string $kode
 * @property string $tipe
 * @property string $tanggal
 * @property integer $departement_id
 * @property string $reff_no
 * @property string $reff_no2
 * @property string $mata_uang
 * @property string $cara_bayar
 * @property integer $penerima_voucher_id
 * @property string $penerima_reff_table
 * @property integer $penerima_reff_id
 * @property double $total_dpp
 * @property double $total_dp
 * @property double $total_sisa
 * @property double $total_ppn
 * @property double $total_pph
 * @property double $total_pembayaran
 * @property double $total_potongan
 * @property double $biaya_tambahan
 * @property string $status_bayar
 * @property string $status_approve
 * @property string $voucher_pengeluaran_id
 * @property integer $prepared_by
 * @property integer $approver_1
 * @property integer $approver_2
 * @property integer $approver_3
 * @property integer $approver_4
 * @property integer $approver_5
 * @property string $keterangan
 * @property string $penerima_voucher_qq
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property MDepartement $departement
 * @property MPegawai $preparedBy
 * @property MPegawai $approver1
 * @property MPegawai $approver2
 * @property MPegawai $approver3
 * @property MPegawai $approver4
 * @property MPegawai $approver5
 * @property TCancelTransaksi $cancelTransaksi
 * @property TOpenVoucherDetail[] $tOpenVoucherDetails
 */
class TOpenVoucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $departement_nama,$prepared_by_display,$approver_1_display,$approver_2_display, $dept_pegawai;
    public $per_tanggal ;
    public $tgl_awal, $tgl_akhir, $pegawai_nama, $keterangan_sengon, $total_jml, $nama_penerima, $nama_perusahaan;
    public $suplier_nm, $suplier_nm_company, $suplier_almt;
    public $kepada, $tanggal_bayar;
    public static function tableName()
    {
        return 't_open_voucher';
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
            [['kode', 'tipe', 'tanggal', 'departement_id', 'prepared_by', 'approver_1', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['departement_id', 'prepared_by', 'approver_1', 'voucher_pengeluaran_id', 'penerima_voucher_id', 'penerima_reff_id', 'approver_2', 'approver_3', 'approver_4', 'approver_5', 'cancel_transaksi_id', 'created_by', 'updated_by', 'pegawai_id'], 'integer'],
            [['total_dpp', 'total_dp', 'total_sisa', 'total_ppn', 'total_pph', 'total_pembayaran', 'total_potongan', 'biaya_tambahan'], 'number'],
            [['keterangan', 'penerima_voucher_qq', 'reff_no2'], 'string'],
            [['kode', 'tipe', 'reff_no', 'mata_uang', 'cara_bayar', 'status_bayar', 'status_approve','penerima_reff_table'], 'string', 'max' => 50],
            [['departement_id'], 'exist', 'skipOnError' => true, 'targetClass' => MDepartement::className(), 'targetAttribute' => ['departement_id' => 'departement_id']],
            [['penerima_voucher_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPenerimaVoucher::className(), 'targetAttribute' => ['penerima_voucher_id' => 'penerima_voucher_id']],
            [['voucher_pengeluaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']],
            [['prepared_by'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['prepared_by' => 'pegawai_id']],
            [['approver_1'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_1' => 'pegawai_id']],
            [['approver_2'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_2' => 'pegawai_id']],
            [['approver_3'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_3' => 'pegawai_id']],
            [['approver_4'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_4' => 'pegawai_id']],
            [['approver_5'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approver_5' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'open_voucher_id' => 'Open Voucher',
                'kode' => 'Kode',
                'tipe' => 'Tipe',
                'tanggal' => 'Tanggal',
                'departement_id' => 'Departement',
                'reff_no' => 'Reff No',
                'reff_no2' => 'Reff No2',
                'mata_uang' => 'Mata Uang',
                'cara_bayar' => 'Cara Bayar',
                'penerima_voucher_id' => 'Penerima Pembayaran',
                'penerima_reff_table' => 'Penerima Reff Table',
                'penerima_reff_id' => 'Penerima Reff ID',
                'total_dpp' => 'Total Dpp',
                'total_dp' => 'Total Dp',
                'total_sisa' => 'Total Sisa',
                'total_ppn' => 'Total Ppn',
                'total_pph' => 'Total Pph',
                'total_pembayaran' => 'Total Pembayaran',
                'total_potongan' => 'Total Potongan',
                'biaya_tambahan' => 'Biaya Tambahan',
                'status_bayar' => 'Status Bayar',
                'status_approve' => 'Status Approve',
                'voucher_pengeluaran_id' => 'Voucher Pengeluaran',
                'prepared_by' => 'Prepared By',
                'approver_1' => 'Approver 1',
                'approver_2' => 'Approver 2',
                'approver_3' => 'Approver 3',
                'approver_4' => 'Approver 4',
                'approver_5' => 'Approver 5',
                'keterangan' => 'Keterangan',
                'penerima_voucher_qq' => 'Penerima QQ',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'pegawai_id' => 'Nama Pegawai'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(MDepartement::className(), ['departement_id' => 'departement_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenerimaVoucher()
    {
        return $this->hasOne(MPenerimaVoucher::className(), ['penerima_voucher_id' => 'penerima_voucher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreparedBy()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'prepared_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover1()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover2()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover3()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_3']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover4()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_4']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover5()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approver_5']);
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
    public function getTOpenVoucherDetails()
    {
        return $this->hasMany(TOpenVoucherDetail::className(), ['open_voucher_id' => 'open_voucher_id']);
    }
        
    //
    public function searchLaporanTagihanSuplierBB() {
            $query = self::find();
            $select1 = "SUM(t_open_voucher.total_pembayaran)";
            $select2 = "CASE
                                WHEN status_bayar = 'PAID' then SUM(t_open_voucher.total_pembayaran)
                                ELSE 0
                        END ";
            $query->select(['t_open_voucher.penerima_reff_id', 'm_suplier.suplier_nm','m_suplier.type',
                                            "{$select1} AS totaltagihan" ,
                                            "{$select2} AS dibayar",
                                            "(	{$select1} - {$select2} ) AS hutang"]);
            $query->join('JOIN', "m_suplier",'m_suplier.suplier_id = t_open_voucher.penerima_reff_id');
            $query->groupBy("t_open_voucher.penerima_reff_id, m_suplier.suplier_nm, m_suplier.type");  
            $query->having("{$select1} - {$select2} > 0");
            $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
                    'm_suplier.suplier_nm ASC' );
            $query->andWhere("cancel_transaksi_id IS NULL");
            if(!empty($this->per_tanggal)){
                    $query->andWhere("tanggal <= '".$this->per_tanggal."'");
            }
            if(!empty($this->penerima_reff_id)){
                    $query->andWhere("t_open_voucher.penerima_reff_id = ".$this->penerima_reff_id);
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
            $param['where'] = ["cancel_transaksi_id IS NULL "];
            if(!empty($this->per_tanggal)){
                    array_push($param['where'],"tanggal <= '".$this->per_tanggal."'");
            }
            if(!empty($this->suplier_id)){
                    array_push($param['where'],"t_open_voucher.penerima_reff_id = ".$this->penerima_reff_id); //kondisikan jika tipe table pada t_open_voucher  = m_spulier
            }
            if(!empty($searchLaporan->having)){
                    $param['having'] = "HAVING ".$searchLaporan->having;
            }
            return $param;
    }

    public function searchLaporan() {
		$query = self::find();
		$query->select( self::tableName().".open_voucher_id,
                        ".self::tableName().".kode,
                        ".self::tableName().".tanggal,
                        ".self::tableName().".tipe,
                        ".self::tableName().".departement_id,
                        ".self::tableName().".reff_no,
                        ".self::tableName().".penerima_voucher_id,
                        m_penerima_voucher.nama_penerima,
                        m_penerima_voucher.nama_perusahaan,
                        ".self::tableName().".penerima_reff_id,
                        m_suplier.suplier_nm,
                        m_suplier.suplier_nm_company,
                        ".self::tableName().".pegawai_id,
                        peg.pegawai_nama,
                        dept.departement_nama,
                        ".self::tableName().".cara_bayar,
                        ".self::tableName().".total_pembayaran,
                        ".self::tableName().".status_approve,
                        ".self::tableName().".status_bayar,
                        m_pegawai.pegawai_nama,
                        t_open_voucher.voucher_pengeluaran_id,
                        t_voucher_pengeluaran.kode AS kode_voucher_pengeluaran,
                        t_voucher_pengeluaran.total_nominal AS nominal_pembayaran,
                        ".self::tableName().".keterangan AS keterangan,
                        ( SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT * FROM t_tagihan_sengon WHERE kode IN( SELECT reff_no FROM t_open_voucher_detail WHERE t_open_voucher_detail.open_voucher_id = t_open_voucher.open_voucher_id GROUP BY 1 ) ) t) AS keterangan_sengon,
                        ".self::tableName().".penerima_voucher_qq AS penerima_voucher_qq,
                        departement.departement_nama,
                        m_suplier.suplier_almt,
                        t_asuransi.kepada,
                        t_voucher_pengeluaran.tanggal_bayar
						");
		$query->join('JOIN', 'm_departement as departement','departement.departement_id = '.self::tableName().'.departement_id ');
        $query->join('JOIN', 'm_pegawai','m_pegawai.pegawai_id = '.self::tableName().'.prepared_by ');
        $query->join('LEFT JOIN', 't_voucher_pengeluaran','t_voucher_pengeluaran.voucher_pengeluaran_id = t_open_voucher.voucher_pengeluaran_id');
        $query->join('LEFT JOIN', 'm_penerima_voucher', 'm_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id');
        $query->join('LEFT JOIN', 'm_suplier', 'm_suplier.suplier_id = t_open_voucher.penerima_reff_id');
        $query->join('LEFT JOIN', 'm_pegawai as peg', 'peg.pegawai_id = t_open_voucher.pegawai_id');
        $query->join('LEFT JOIN', 'm_departement as dept', 'dept.departement_id = peg.departement_id');
        $query->join('LEFT JOIN', 't_asuransi', 't_asuransi.kode = '.self::tableName().'.reff_no');
		$query->where("status_approve = 'APPROVED'");
        $query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC, t_open_voucher.open_voucher_id ASC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere(self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->tipe)){
			$query->andWhere(self::tableName().".tipe ILIKE '%".$this->tipe."%'");
		}
		if(!empty($this->departement_id)){
			$query->andWhere(self::tableName().".departement_id = ".$this->departement_id);
		}
		if(!empty($this->cara_bayar)){
			$query->andWhere(self::tableName().".cara_bayar = '".$this->cara_bayar."'");
		}
        if(!empty($this->status_bayar)){
			$query->andWhere(self::tableName().".status_bayar = '".$this->status_bayar."'");
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
		$param['where'] = ["status_approve = 'APPROVED'"];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_open_voucher.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->tipe)){
			array_push($param['where'],"t_open_voucher.tipe ILIKE '%".$this->tipe."%'");
		}
        if(!empty($this->departement_id)){
			array_push($param['where'],"t_open_voucher.departement_id = ".$this->departement_id);
		}
		if(!empty($this->cara_bayar)){
			array_push($param['where'],"t_open_voucher.cara_bayar ILIKE '%".$this->cara_bayar."%'");
		}
		if(!empty($this->status_bayar)){
			array_push($param['where'],"t_open_voucher.status_bayar = '".$this->status_bayar."'");
		}
		return $param;
	}
    
}
