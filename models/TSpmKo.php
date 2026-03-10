<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spm_ko".
 *
 * @property integer $spm_ko_id
 * @property string $kode
 * @property string $tanggal
 * @property string $tanggal_kirim
 * @property string $kendaraan_jenis
 * @property string $kendaraan_nopol
 * @property string $kendaraan_supir
 * @property string $waktu_selesaimuat
 * @property integer $op_ko_id
 * @property string $alamat_bongkar
 * @property integer $cust_id
 * @property integer $diperiksa
 * @property integer $diperiksa_security
 * @property integer $dikeluarkan
 * @property integer $dibuat
 * @property string $status
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $waktu_mulaimuat
 *
 * @property MCustomer $cust
 * @property MPegawai $diperiksa0
 * @property MPegawai $dikeluarkan0
 * @property MPegawai $dibuat0
 * @property TCancelTransaksi $cancelTransaksi
 * @property TOpKo $opKo
 */
class TSpmKo extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $cust_an_nama,$cust_pr_nama,$cust_an_alamat,$dibuat_display,$jenis_produk,$kode_op;
	const REALISASI = "REALISASI";
	public $shipment_to,$port_of_loading,$final_destination,$nomor,$cust_tipe_penjualan,$applicant,$notify_party;
	public $container_kode,$seal_no;
	public $tgl_awal,$tgl_akhir,$payment_method,$term_of_price,$no_inv,$kode_kontainer,$size_kontainer,$size,$total_palet,$total_qty,$total_m3;
    public $tarik_data, $tarik_log, $terima_logalam_id;
    public static function tableName()
    {
        return 't_spm_ko';
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
            [['kode', 'tanggal','tanggal_rencanamuat', 'tanggal_kirim', 'kendaraan_nopol', 'kendaraan_supir', 'op_ko_id', 'alamat_bongkar', 'cust_id', 'dibuat', 'disetujui', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal','tanggal_rencanamuat', 'tanggal_kirim', 'waktu_selesaimuat', 'waktu_mulaimuat', 'created_at', 'updated_at'], 'safe'],
            [['op_ko_id', 'cust_id', 'diperiksa', 'diperiksa_security', 'dikeluarkan', 'dibuat', 'cancel_transaksi_id', 'created_by', 'updated_by','disetujui','packinglist_id'], 'integer'],
            [['container_no'], 'number'],
            [['alamat_bongkar', 'cust_alamat'], 'string'],
            [['kode'], 'string', 'max' => 25],
            [['kendaraan_jenis', 'jenis_penjualan'], 'string', 'max' => 20],
            [['kendaraan_nopol'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 50],
            [['kendaraan_supir'], 'string', 'max' => 250],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['diperiksa'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diperiksa' => 'pegawai_id']],
            [['diperiksa_security'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['diperiksa_security' => 'pegawai_id']],
            [['dikeluarkan'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['dikeluarkan' => 'pegawai_id']],
            [['dibuat'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['dibuat' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['op_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpKo::className(), 'targetAttribute' => ['op_ko_id' => 'op_ko_id']],
            [['disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spm_ko_id' => Yii::t('app', 'Spm Ko'),
                'kode' => Yii::t('app', 'Kode SPM'),
                'tanggal' => Yii::t('app', 'Tanggal SPM'),
                'tanggal_kirim' => Yii::t('app', 'Tanggal Kirim'),
                'tanggal_rencanamuat' => Yii::t('app', 'Rencana Muat'),
                'kendaraan_jenis' => Yii::t('app', 'Kendaraan Jenis'),
                'kendaraan_nopol' => Yii::t('app', 'Nopol Kendaraan'),
                'kendaraan_supir' => Yii::t('app', 'Nama Supir'),
                'waktu_selesaimuat' => Yii::t('app', 'Waktu Muat End'),
                'waktu_mulaimuat' => Yii::t('app', 'Waktu Muat Start'),
                'op_ko_id' => Yii::t('app', 'Op Ko'),
                'alamat_bongkar' => Yii::t('app', 'Alamat Bongkar'),
                'cust_id' => Yii::t('app', 'Cust'),
                'diperiksa' => Yii::t('app', 'Diperiksa'),
                'diperiksa_security' => Yii::t('app', 'Diperiksa Security'),
                'dikeluarkan' => Yii::t('app', 'Dikeluarkan'),
                'dibuat' => Yii::t('app', 'Dibuat'),
                'status' => Yii::t('app', 'Status'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
                'disetujui' => Yii::t('app', 'Disetujui'),
                'jenis_penjualan' => Yii::t('app', 'Jenis Penjualan'),
                'cust_alamat'       => Yii::t('app', 'Alamat Customer')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiperiksa0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'diperiksa']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiperiksaSecurity0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'diperiksa_security']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDikeluarkan0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'dikeluarkan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDibuat0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'dibuat']);
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisetujui0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'disetujui']);
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
    public function getOpKo()
    {
        return $this->hasOne(TOpKo::className(), ['op_ko_id' => 'op_ko_id']);
    }
	
	public static function getOptionListScanPemuatan()
    {
        $res = self::find()->select("*, m_customer.*")  
							->where("cancel_transaksi_id IS NULL AND (status != 'REALISASI' OR status IS NULL) AND kode NOT LIKE 'LGM%'")
							->andWhere("kode NOT IN ('PLM080119001','PLM080119002','MDM090119001','MDM090119002','MDM090119003','MDM090119004','MDM090119005','PLM120119001','PLM120119002')") // SPM Export Trial Running
							->join("JOIN", "m_customer", "m_customer.cust_id = t_spm_ko.cust_id")
							->orderBy(self::tableName().'.created_at DESC')->all();
		$return = [];
		foreach($res as $i => $val){
            $nomorKontrak = '';
            if ($val->cust_tipe_penjualan == "export") {
                // Query to get the contract number for export type sales
                $res2 = (new \yii\db\Query())
                    ->select(['oe.nomor_kontrak'])
                    ->from(['t' => 't_spm_ko'])
                    ->innerJoin('t_packinglist pl', 'pl.packinglist_id = t.packinglist_id')
                    ->innerJoin('t_op_export oe', 'oe.op_export_id = pl.op_export_id')
                    ->where(['t.spm_ko_id' => $val->spm_ko_id])
                    ->all();
                
                foreach ($res2 as $val2) {
                    $nomorKontrak = $val2['nomor_kontrak'];
                }
            }
			$return[$val['spm_ko_id']] = $val['kode'].' - '.$val['cust_an_nama']. (($val->cust_tipe_penjualan=="export") ?" (".strtoupper($val->cust_tipe_penjualan).") Nomor Kontrak : ".$nomorKontrak."":"");
		}
        return $return;
    }
	
	public static function getOptionListNotaBaru()
    {
        $res = \Yii::$app->db->createCommand("SELECT t_spm_ko.*,m_customer.cust_an_nama FROM t_spm_ko 
											JOIN m_customer ON m_customer.cust_id = t_spm_ko.cust_id
											LEFT JOIN t_nota_penjualan ON t_nota_penjualan.spm_ko_id = t_spm_ko.spm_ko_id
											WHERE t_spm_ko.cancel_transaksi_id IS NULL AND t_spm_ko.status = '".\app\models\TSpmKo::REALISASI."' AND nota_penjualan_id IS NULL AND t_spm_ko.jenis_penjualan = 'lokal'
											ORDER BY t_spm_ko.created_at")->queryAll();
		$return = [];
		foreach($res as $i => $val){
			$return[$val['spm_ko_id']] = $val['kode']." - ".$val['cust_an_nama'];
		}
        return $return;
    }
	public static function getOptionListInvoiceBaru()
    {
        $res = \Yii::$app->db->createCommand("SELECT * FROM t_spm_ko
											JOIN m_customer ON m_customer.cust_id = t_spm_ko.cust_id
											JOIN t_packinglist ON t_packinglist.packinglist_id = t_spm_ko.packinglist_id
											WHERE t_spm_ko.status = '".self::REALISASI."'
											ORDER BY t_spm_ko.spm_ko_id DESC")->queryAll();
		$return = [];
		foreach($res as $i => $val){
			$return[$val['spm_ko_id']] = $val['kode'].' - '.$val['nomor'];
		}
        return $return;
    }
	
	public function searchLaporanStuffing() {
		$query = self::find();
		$query->select(['t_spm_ko.spm_ko_id',
						't_spm_ko.kode',
						't_invoice.nomor AS no_inv',
						't_spm_ko.tanggal',
						't_spm_ko.tanggal_kirim',
						'm_customer.cust_an_nama',
						'm_customer.cust_an_alamat',
						't_spm_ko.alamat_bongkar AS final_destination',
						'kendaraan_supir',
						'kendaraan_nopol',
						'( SELECT container_kode FROM t_packinglist_container WHERE t_packinglist_container.packinglist_id = t_spm_ko.packinglist_id AND t_packinglist_container.container_no = t_spm_ko.container_no  LIMIT 1 ) AS kode_kontainer',
						'( SELECT container_size FROM t_packinglist_container WHERE t_packinglist_container.packinglist_id = t_spm_ko.packinglist_id AND t_packinglist_container.container_no = t_spm_ko.container_no  LIMIT 1 ) AS size_kontainer',
						'( SELECT produk_group FROM t_spm_ko_detail JOIN m_brg_produk ON m_brg_produk.produk_id = t_spm_ko_detail.produk_id WHERE t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id LIMIT 1) AS jenis_produk',
						'( SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT MIN(thick) AS min_thick, MAX(thick) AS max_thick, MAX(thick_unit) AS thick_unit, MIN(width) AS min_width, MAX(width) AS max_width, MAX(width_unit) AS width_unit, MIN(length) AS min_length, MAX(length) AS max_length, MAX(length_unit) AS length_unit FROM t_packinglist_container WHERE t_packinglist_container.packinglist_id = t_spm_ko.packinglist_id AND t_packinglist_container.container_no = t_spm_ko.container_no ) t) AS size',
						'( SELECT SUM(qty_besar_realisasi) FROM t_spm_ko_detail WHERE t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id ) AS total_palet',
						'( SELECT SUM(qty_kecil_realisasi) FROM t_spm_ko_detail WHERE t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id ) AS total_qty',
						'ROUND(( SELECT SUM(kubikasi_realisasi) FROM t_spm_ko_detail WHERE t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id )::numeric,4) AS total_m3'
						]);
		$query->join('JOIN', 't_packinglist','t_packinglist.packinglist_id = t_spm_ko.packinglist_id');
		$query->join('JOIN', 't_invoice','t_invoice.packinglist_id = t_packinglist.packinglist_id');
		$query->join('JOIN', 'm_customer','m_customer.cust_id = t_packinglist.cust_id');
		$query->andWhere("t_spm_ko.status = 'REALISASI' and jenis_penjualan = 'export'");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			't_invoice.nomor DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_spm_ko.tanggal_kirim BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->cust_id)){
			$query->andWhere("t_invoice.cust_id  = ".$this->cust_id);
		}
		if(!empty($this->jenis_produk)){
			$query->andWhere("t_invoice.jenis_produk  = '".$this->jenis_produk."'");
		}
		if(!empty($this->no_inv)){
			$query->andWhere("t_invoice.nomor ILIKE '%".$this->no_inv."%'");
		}
		return $query;
	}
	
	public function searchLaporanStuffingDt() {
		$searchLaporan = $this->searchLaporanStuffing();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
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
		array_push($param['where'],"t_spm_ko.status = 'REALISASI' and jenis_penjualan = 'export'");
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_spm_ko.tanggal_kirim BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->cust_id)){
			array_push($param['where'],"t_invoice.cust_id = ".$this->cust_id);
		}
		if(!empty($this->jenis_produk)){
			array_push($param['where'],"t_invoice.jenis_produk = '".$this->jenis_produk."'");
		}
		if(!empty($this->no_inv)){
			array_push($param['where'],"t_invoice.nomor ILIKE '%".$this->no_inv."%'");
		}
		return $param;
	}
    public static function getOptionListScanPemuatanLog()
    {
        $res = self::find()->select("*, m_customer.*")
							->where("cancel_transaksi_id IS NULL AND (status != 'REALISASI' OR status IS NULL) AND kode ilike 'LGM%'")
							->andWhere("kode NOT IN ('PLM080119001','PLM080119002','MDM090119001','MDM090119002','MDM090119003','MDM090119004','MDM090119005','PLM120119001','PLM120119002')") // SPM Export Trial Running
							->join("JOIN", "m_customer", "m_customer.cust_id = t_spm_ko.cust_id")
							->orderBy(self::tableName().'.created_at DESC')->all();
		$return = [];
		foreach($res as $i => $val){
			$return[$val['spm_ko_id']] = $val['kode'].' - '.$val['cust_an_nama']. (($val->cust_tipe_penjualan=="export")?" (".strtoupper($val->cust_tipe_penjualan).")":"");
		}
        return $return;
    }
}
