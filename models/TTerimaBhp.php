<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_bhp".
 *
 * @property integer $terima_bhp_id
 * @property integer $spo_id
 * @property integer $spl_id
 * @property integer $suplier_id
 * @property string $terimabhp_kode
 * @property string $tglterima
 * @property integer $pegawaipenerima
 * @property string $tanggal_jam_checker
 * @property integer $pegawai_cheker
 * @property string $nofaktur
 * @property string $terimabhp_status
 * @property string $terimabhp_keterangan
 * @property double $potonganharga
 * @property double $totalbayar
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property boolean $is_ppn
 * @property double $ppn_nominal
 * @property integer $voucher_pengeluaran_id
 * @property integer $cancel_transaksi_id
 * @property integer $kas_kecil_id
 * @property double $total_pbbkb
 * @property double $total_biayatambahan
 * @property string $label_biayatambahan
 * @property string $no_fakturpajak
 * @property string $no_suratjalan
 * @property string $label_potonganharga
 *
 * @property MPegawai $pegawaipenerima0
 * @property MPegawai $pegawai_checker0
 * @property MSuplier $suplier
 * @property TCancelTransaksi $cancelTransaksi
 * @property TKasKecil $kasKecil
 * @property TSpl $spl
 * @property TSpo $spo
 * @property TVoucherPengeluaran $voucherPengeluaran
 * @property TTerimaBhpDetail[] $tTerimaBhpDetails
 */ 
class TTerimaBhp extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $totalpph,$pbbkb,$tgl_awal,$tgl_akhir,$kode_voucher,$kode_kaskecil,$payment_status,$mata_uang,$bhp_nm,$bhp_id,$suplier_nm;
	public $tanggal,$keterangan,$tanggal_nota;
    public static function tableName()
    {
        return 't_terima_bhp';
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
            [['spo_id', 'spl_id', 'suplier_id', 'pegawaipenerima', 'pegawai_checker', 'created_by', 'updated_by', 'voucher_pengeluaran_id', 'cancel_transaksi_id', 'kas_kecil_id'], 'integer'],
            [['terimabhp_kode', 'tglterima', 'tanggal_jam_checker', 'pegawaipenerima', 'pegawai_checker', 'terimabhp_status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tglterima', 'tanggal_jam_checker', 'created_at', 'updated_at'], 'safe'],
            [['terimabhp_keterangan','approve_reason', 'reject_reason', 'no_suratjalan'], 'string'],
            [['potonganharga', 'totalbayar', 'ppn_nominal', 'total_pbbkb','total_biayatambahan'], 'number'],
            [['is_ppn'], 'boolean'],
            [['terimabhp_kode', 'nofaktur', 'terimabhp_status'], 'string', 'max' => 30],
            [['no_fakturpajak'], 'string', 'max' => 50],
            [['label_biayatambahan'], 'string', 'max' => 200],
            [['pegawaipenerima', 'pegawai_checker'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['pegawaipenerima' => 'pegawai_id']],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['kas_kecil_id'], 'exist', 'skipOnError' => true, 'targetClass' => TKasKecil::className(), 'targetAttribute' => ['kas_kecil_id' => 'kas_kecil_id']],
            [['spl_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpl::className(), 'targetAttribute' => ['spl_id' => 'spl_id']],
            [['spo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpo::className(), 'targetAttribute' => ['spo_id' => 'spo_id']],
            [['voucher_pengeluaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TVoucherPengeluaran::className(), 'targetAttribute' => ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']],
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'terima_bhp_id' => Yii::t('app', 'Terima Bhp'),
			'spo_id' => Yii::t('app', 'Kode PO'),
			'spl_id' => Yii::t('app', 'Kode SPL'),
			'suplier_id' => Yii::t('app', 'Suplier'),
			'terimabhp_kode' => Yii::t('app', 'Kode Penerimaan'),
			'tglterima' => Yii::t('app', 'Tanggal Penerimaan'),
			'pegawaipenerima' => Yii::t('app', 'Pegawai Penerima'),
			'pegawai_checker' => Yii::t('app', 'Pegawai Checker'),
			'tanggal_jam_checker' => Yii::t('app', 'Tanggal Jam Checker'),
			'nofaktur' => Yii::t('app', 'No. Faktur'),
			'terimabhp_status' => Yii::t('app', 'Status'),
			'terimabhp_keterangan' => Yii::t('app', 'Keterangan'),
			'potonganharga' => Yii::t('app', 'Potongan (Rp)'),
			'totalbayar' => Yii::t('app', 'Total Bayar'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'is_ppn' => Yii::t('app', 'Include PPN'),
			'ppn_nominal' => Yii::t('app', 'PPN'),
			'total_pbbkb' => Yii::t('app', 'PBBKB'),
			'total_biayatambahan' => Yii::t('app', 'Biaya Tambahan'),
			'label_biayatambahan' => Yii::t('app', 'Biaya Tambahan'),
			'voucher_pengeluaran_id' => Yii::t('app', 'Voucher Pengeluaran'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'kas_kecil_id' => Yii::t('app', 'Kas Kecil'),
			'no_fakturpajak' => Yii::t('app', 'No. Faktur Pajak'),
			'approve_reason' => Yii::t('app', 'Approve Reason'),
			'reject_reason' => Yii::t('app', 'Reject Reason'),
            'no_suratjalan' => Yii::t('app', 'No. Surat Jalan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTbpCreatedBy()
    {
        return $this->hasOne(MUser::className(), ['user_id' => 'created_by']);
    }
    public function getPegawaipenerima0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawaipenerima']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaichecker0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'pegawai_checker']);
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
    public function getKasKecil()
    {
        return $this->hasOne(TKasKecil::className(), ['kas_kecil_id' => 'kas_kecil_id']);
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpl()
    {
        return $this->hasOne(TSpl::className(), ['spl_id' => 'spl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpo()
    {
        return $this->hasOne(TSpo::className(), ['spo_id' => 'spo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTTerimaBhpDetails()
    {
        return $this->hasMany(TTerimaBhpDetail::className(), ['terima_bhp_id' => 'terima_bhp_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherPengeluaran()
    {
        return $this->hasOne(TVoucherPengeluaran::className(), ['voucher_pengeluaran_id' => 'voucher_pengeluaran_id']);
    } 
	public function getDefaultValue()
    {
        return $this->hasOne(MDefaultValue::className(), ['value' => 'mata_uang']);
    } 
	public function searchLaporan() {
		$suplier = MSuplier::tableName();
		$voucher = TVoucherPengeluaran::tableName();
		$spo = TSpo::tableName();
		$spl = TSpl::tableName();
		$kas = TKasKecil::tableName();
		$query = self::find();
		$query->select([self::tableName().'.terima_bhp_id',
						'tglterima',
						'tanggal_jam_checker',
                        'terimabhp_kode',
                        $spl.'.spl_kode',
                        $spo.'.spo_kode',
                        'suplier_nm',
						'nofaktur',
                        $voucher.'.status_bayar',
                        $kas.'.kode as kode_kaskecil',
                        $voucher.'.kode',
                        self::tableName().'.cancel_transaksi_id',
						self::tableName().'.spl_id',
                        self::tableName().'.spo_id',
                        self::tableName().'.voucher_pengeluaran_id',
						self::tableName().'.kas_kecil_id', 
                        'totalbayar',
                        't_terima_bhp_detail.bhp_id', 
                        'bhp_nm',
						'terimabhpd_qty', 
                        'bhp_satuan', 
                        'terimabhpd_harga',
                        't_retur_bhp.retur_bhp_id'
                        ]);
		$query->join('LEFT JOIN', $suplier,$suplier.'.suplier_id = '.self::tableName().'.suplier_id');
		$query->join('LEFT JOIN', $spo,$spo.'.spo_id = '.self::tableName().'.spo_id');
		$query->join('LEFT JOIN', $spl,$spl.'.spl_id = '.self::tableName().'.spl_id');
		$query->join('LEFT JOIN', $voucher,$voucher.'.voucher_pengeluaran_id = '.self::tableName().'.voucher_pengeluaran_id');
		$query->join('LEFT JOIN', $kas,$kas.'.kas_kecil_id = '.self::tableName().'.kas_kecil_id');
		$query->join('JOIN', 't_terima_bhp_detail','t_terima_bhp_detail.terima_bhp_id = t_terima_bhp.terima_bhp_id');
		$query->join('JOIN', 'm_brg_bhp','t_terima_bhp_detail.bhp_id = m_brg_bhp.bhp_id');
        $query->join('LEFT JOIN', "t_retur_bhp",'t_retur_bhp.terima_bhpd_id = t_terima_bhp_detail.terima_bhpd_id');
		$query->groupBy(self::tableName().'.terima_bhp_id, tglterima, terimabhp_kode, '.$spl.'.spl_kode, '.$spo.'.spo_kode, suplier_nm,
						nofaktur, '.$voucher.'.status_bayar, '.$kas.'.kode, '.$voucher.'.kode,
						t_terima_bhp_detail.bhp_id, bhp_nm, terimabhpd_qty, bhp_satuan, terimabhpd_harga, t_retur_bhp.retur_bhp_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tglterima BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->terimabhp_kode)){
			$query->andWhere("terimabhp_kode ILIKE '%".$this->terimabhp_kode."%'");
		}
		if(!empty($this->kode_voucher)){
			$query->andWhere($voucher.".kode ILIKE '%".$this->kode_voucher."%'");
		}
		if(!empty($this->nofaktur)){
			$query->andWhere("nofaktur ILIKE '%".$this->nofaktur."%'");
		}
		if(!empty($this->suplier_id)){
			$query->andWhere($suplier.".suplier_id = ".$this->suplier_id);
		}
		if(!empty($this->payment_status)){
			$query->andWhere($voucher.".status_bayar = '".$this->payment_status."'");
			$query->orWhere(self::tableName().".kas_kecil_id IS NOT NULL");
		}
		if(!empty($this->bhp_id)){
			$query->andWhere("m_brg_bhp.bhp_id = ".$this->bhp_id);
		}
		return $query;
	}
	
	public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$suplier = MSuplier::tableName();
		$voucher = TVoucherPengeluaran::tableName();
		$spo = TSpo::tableName();
		$spl = TSpl::tableName();
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
			array_push($param['where'],"tglterima BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->terimabhp_kode)){
			array_push($param['where'],"terimabhp_kode ILIKE '%".$this->terimabhp_kode."%'");
		}
		if(!empty($this->kode_voucher)){
			array_push($param['where'],$voucher.".kode ILIKE '%".$this->kode_voucher."%'");
		}
		if(!empty($this->nofaktur)){
			array_push($param['where'],"nofaktur ILIKE '%".$this->nofaktur."%'");
		}
		if(!empty($this->suplier_id)){
			array_push($param['where'],$suplier.".suplier_id = ".$this->suplier_id);
		}
		if(!empty($this->payment_status)){
			array_push($param['where']," ( ".$voucher.".status_bayar = '".$this->payment_status."' OR ".$param['table'].".kas_kecil_id IS NOT NULL )");
		}
		if(!empty($this->bhp_id)){
			array_push($param['where'],"m_brg_bhp.bhp_id = ".$this->bhp_id);
		}
		return $param;
	}
    
    // 2020/01/12 /cis/web/purchasing/laporan/TerimaBhpFilterNoFaktur
    public function searchLaporanFilterNoFaktur($tgl_awal, $tgl_akhir, $suplier_id) {
        $suplier = MSuplier::tableName();
        $spo = TSpo::tableName();
        $spl = TSpl::tableName();
        $query = self::find();
        $query->select([self::tableName().'.terima_bhp_id',				//0
                        'tglterima',									//1
                        'terimabhp_kode',								//2
                        $spl.'.spl_kode',								//3
                        $spo.'.spo_kode',								//4
                        $suplier.'.suplier_nm',							//5
                        'nofaktur',								//6
                        'totalbayar',									//7
                        ]);
        $query->join('LEFT JOIN', $spl,$spl.'.spl_id = '.self::tableName().'.spl_id');
        $query->join('LEFT JOIN', $spo,$spo.'.spo_id = '.self::tableName().'.spo_id');
        $query->join('LEFT JOIN', $suplier,$suplier.'.suplier_id = '.self::tableName().'.suplier_id');
        $query->orderBy( !empty($_GET['sort']['col']) ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : self::tableName().'.created_at DESC' );
        $query->andWhere("t_terima_bhp.ppn_nominal > 0");
        $query->andWhere("t_terima_bhp.no_fakturpajak = ''");
        $query->andWhere("t_terima_bhp.tglterima between '".$tgl_awal."' and '".$tgl_akhir."' ");
        $query->andWhere("t_terima_bhp.cancel_transaksi_id is null");
        $query->andWhere("t_terima_bhp.totalretur = 0");
        
        isset($suplier_id) && $suplier_id != '' ? $query->andWhere("t_terima_bhp.suplier_id = ".$suplier_id."") : "";

        return $query;
    }
	
	public function searchLaporanFilterNoFakturDt($tgl_awal, $tgl_akhir, $suplier_id) {
		$searchLaporan = $this->searchLaporanFilterNoFaktur($tgl_awal, $tgl_akhir, $suplier_id);
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
	
		if(!empty($searchLaporan->select)){
			$param['column'] = $searchLaporan->select;
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
        isset($suplier_id) && $suplier_id != '' ? $and_suplier_id = "   and t_terima_bhp.suplier_id = ".$suplier_id." " : $and_suplier_id = "";
        array_push($param['where'],"t_terima_bhp.ppn_nominal > 0 ". 
            "   and t_terima_bhp.no_fakturpajak = '' ". 
            "   and t_terima_bhp.tglterima between '".$tgl_awal."' and '".$tgl_akhir."' ".
            "   and t_terima_bhp.cancel_transaksi_id is null ". 
            "   and t_terima_bhp.totalretur = 0 ". 
            $and_suplier_id. 
            " ");
		
		return $param;
	}
}
