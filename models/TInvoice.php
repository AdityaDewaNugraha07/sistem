<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_invoice".
 *
 * @property integer $invoice_id
 * @property string $nomor
 * @property string $tanggal
 * @property integer $op_export_id
 * @property integer $packinglist_id
 * @property integer $cust_id
 * @property string $payment_method
 * @property string $payment_method_reff
 * @property string $term_of_price
 * @property integer $disetujui
 * @property string $mata_uang
 * @property double $total_harga
 * @property double $total_ppn
 * @property double $total_pph
 * @property double $total_potongan
 * @property double $total_biaya_tambahan
 * @property double $total_bayar
 * @property string $status
 * @property string $keterangan_potongan
 * @property string $keterangan_biaya_tambahan
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $notes
 * @property integer $fob
 * @property integer $freight
 * @property boolean $piutang_active
 * @property boolean $fob_preview
 * @property boolean $freight_preview
 *
 * @property MCustomer $cust
 * @property MPegawai $disetujui0
 * @property TCancelTransaksi $cancelTransaksi
 * @property TOpExport $opExport
 * @property TPackinglist $packinglist
 * @property TSpmKo $spmKo
 * @property mixed|null $status_inv
 * @property mixed|null $peb_tanggal
 * @property mixed|null $bl_tanggal
 * @property mixed|null $peb_no
 * @property mixed|null $marks
 * @property mixed|null $diff_size_diff_price
 * @property mixed|null $grouping_qty
 */
class TInvoice extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc $id,$invoice_id,
     */
	public $cust_an_nama,$cust_pr_nama,$shiper,$shipto,$port_of_loading,$final_destination,$harvesting_area,$static_product_code,$nomor_kontrak;
	public $hs_code,$svlk_no,$vlegal_no,$vessel,$container_kode,$seal_no,$etd,$eta,$goods_description,$applicant,$notify_party,$container_kode_seal_no;
	public $total_palet,$total_pcs,$total_kubikasi,$tgl_awal,$tgl_akhir,$kode,$size,$container_no,$total_container,$container_size,$tanggal_stuffing;
	public $total_volume,$penerbit,$cust_an_alamat,$tanggal_inv,$tgl_etd,$total_volume_inv;
    public $peb_kode_beacukai, $peb_no_pengajuan, $peb_kantorpabean_pemuatan, $peb_kantorpabean_ekspor, $peb_jenis_ekspor, $peb_kategori_ekspor, $peb_cara_perdagangan,
           $peb_cara_pembayaran, $peb_carapembayaran_lcno, $peb_carapembayaran_lctgl, $peb_eksportir_identitas, $peb_eksportir_nama, $peb_eksportir_alamat, $peb_eksportir_niper, $peb_eksportir_status,
           $peb_ppjk_npwp, $peb_ppjk_nama, $peb_ppjk_alamat, $peb_pengangkutan_cara_pengangkutan, $peb_pengangkutan_nama_bendera, $peb_pengangkutan_no,
           $peb_pengangkutan_tanggal_perkiraan, $peb_pelengkappabean_no_inv, $peb_pelengkappabean_tgl_inv, $peb_pelengkappabean_no_packing, $peb_pelengkappabean_tgl_packing,
           $peb_pelengkappabean_jenis_dok, $peb_pelengkappabean_no, $peb_pelengkappabean_tgl, $peb_pelengkappabean_kantor_beacukai,
           $peb_transaksiekspor_bank_devisa, $peb_transaksiekspor_jenis_valuta, $peb_transaksiekspor_fob, $peb_transaksiekspor_freight, $peb_transaksiekspor_asuransi, $peb_transaksiekspor_maklon, 
           $peb_petikemas_jml, $peb_petikemas_no, $peb_petikemas_ukuran, $peb_petikemas_status,
           $peb_barangekspor_bruto, $peb_barangekspor_netto, $peb_barangekspor_detail, $detail_uraian, $detail_he_barang, $detail_qty, $detail_asal, $detail_fob, $detail_freight,
           $peb_barangekspor_nilai_tukar, $peb_penerimaannegara_bea_keluar, $peb_penerimaannegara_pajak, $peb_beacukai_no_daftar, $peb_beacukai_tgl_daftar, $peb_beacukai_no_bc, $peb_beacukai_tgl_bc, $peb_beacukai_pos,
           $peb_penerima_nama, $peb_penerima_alamat, $peb_penerima_negara, $peb_pembeli_nama, $peb_pembeli_alamat, $peb_pembeli_negara,
           $peb_pelabuhanmuat_muat_asal, $peb_pelabuhanmuat_muat_ekspor, $peb_pelabuhanmuat_bongkar, $peb_pelabuhanmuat_tujuan, $peb_pelabuhanmuat_tujuan_ekspor,
           $peb_tempatperiksa_lokasi, $peb_tempatperiksa_kantor, $peb_tempatperiksa_gudang, $peb_penyerahan_cara, $peb_kemasan_jenis_jml;
    public static function tableName()
    {
        return 't_invoice';
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
            [['nomor', 'tanggal', 'jenis_produk' , 'op_export_id', 'packinglist_id', 'cust_id', 'payment_method', 'term_of_price', 'disetujui', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
			[['diff_size_diff_price','grouping_qty','piutang_active','fob_preview','freight_preview'], 'boolean'],
            [['tanggal', 'created_at', 'updated_at','fob','freight','peb_tanggal','payment_date_estimate','bl_tanggal'], 'safe'],
            [['op_export_id', 'packinglist_id', 'cust_id', 'disetujui', 'cancel_transaksi_id', 'created_by', 'updated_by','penerbit_bl_id'], 'integer'],
            [['payment_method', 'payment_method_reff', 'term_of_price', 'keterangan_potongan', 'keterangan_biaya_tambahan', 'notes','marks'], 'string'],
            [['total_harga', 'total_ppn', 'total_pph', 'total_potongan', 'total_biaya_tambahan', 'total_bayar'], 'number'],
            [['nomor'], 'string', 'max' => 25],
            [['mata_uang', 'status','jenis_produk','status_inv','peb_no','bl_no'], 'string', 'max' => 50],
            [['nomor'], 'unique'],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['disetujui'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['disetujui' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['op_export_id'], 'exist', 'skipOnError' => true, 'targetClass' => TOpExport::className(), 'targetAttribute' => ['op_export_id' => 'op_export_id']],
            [['packinglist_id'], 'exist', 'skipOnError' => true, 'targetClass' => TPackinglist::className(), 'targetAttribute' => ['packinglist_id' => 'packinglist_id']],
			[['penerbit_bl_id'], 'exist', 'skipOnError' => true, 'targetClass' => MPenerbitBl::className(), 'targetAttribute' => ['penerbit_bl_id' => 'penerbit_bl_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'invoice_id' => Yii::t('app', 'Invoice'),
                'nomor' => Yii::t('app', 'Nomor'),
                'tanggal' => Yii::t('app', 'Tanggal'),
                'op_export_id' => Yii::t('app', 'Op Export'),
                'packinglist_id' => Yii::t('app', 'Packinglist'),
                'cust_id' => Yii::t('app', 'Cust'),
                'payment_method' => Yii::t('app', 'Payment Method'),
                'payment_method_reff' => Yii::t('app', 'Payment Method Reff'),
                'term_of_price' => Yii::t('app', 'Term Of Price'),
                'disetujui' => Yii::t('app', 'Disetujui'),
                'mata_uang' => Yii::t('app', 'Mata Uang'),
                'total_harga' => Yii::t('app', 'Total Harga'),
                'total_ppn' => Yii::t('app', 'Total Ppn'),
                'total_pph' => Yii::t('app', 'Total Pph'),
                'total_potongan' => Yii::t('app', 'Total Potongan'),
                'total_biaya_tambahan' => Yii::t('app', 'Total Biaya Tambahan'),
                'total_bayar' => Yii::t('app', 'Total Bayar'),
                'status' => Yii::t('app', 'Status'),
                'keterangan_potongan' => Yii::t('app', 'Keterangan Potongan'),
                'keterangan_biaya_tambahan' => Yii::t('app', 'Keterangan Biaya Tambahan'),
                'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
                'created_at' => Yii::t('app', 'Create Time'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_at' => Yii::t('app', 'Last Update Time'),
                'updated_by' => Yii::t('app', 'Last Updated By'),
				'jenis_produk' => Yii::t('app', 'Jenis Produk'),
				'notes' => Yii::t('app', 'Notes'),
				'fob' => Yii::t('app', 'FOB'),
                'freight' => Yii::t('app', 'Freight'),
				'status_inv' => Yii::t('app', 'Status Invoice'),
				'peb_no' => Yii::t('app', 'No. PEB'),
				'peb_tanggal' => Yii::t('app', 'Tanggal PEB'),
				'bl_no' => Yii::t('app', 'No. B/L'),
				'bl_tanggal' => Yii::t('app', 'Tanggal B/L'),
				'penerbit_bl_id' => Yii::t('app', 'Penerbit B/L'),
				'piutang_active' => Yii::t('app', 'Piutang Aktif'),
				'fob_preview' => Yii::t('app', 'FOB Preview'),
                'freight_preview' => Yii::t('app', 'Freight Preview'),
				'payment_date_estimate' => Yii::t('app', 'Estimate Date of Payment'),
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
    public function getOpExport()
    {
        return $this->hasOne(TOpExport::className(), ['op_export_id' => 'op_export_id']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackinglist()
    {
        return $this->hasOne(TPackinglist::className(), ['packinglist_id' => 'packinglist_id']);
    }
	
	public static function getOptionListPaymentMethod()
    {
        $res = Yii::$app->db->createCommand("SELECT payment_method FROM t_invoice GROUP BY payment_method")->queryAll();
		$ret = [];
		foreach($res as $i => $asd){
			$ret[$asd['payment_method']] = $asd['payment_method'];
		}
        return $ret;
    }
	public static function getOptionListJenisProduk()
    {
        $res = Yii::$app->db->createCommand("SELECT jenis_produk FROM t_invoice GROUP BY 1")->queryAll();
		$ret = [];
		foreach($res as $i => $asd){
			$ret[$asd['jenis_produk']] = $asd['jenis_produk'];
		}
        return $ret;
    }
	public static function getOptionListTermOfPrice()
    {
        $res = Yii::$app->db->createCommand("SELECT term_of_price FROM t_invoice GROUP BY term_of_price")->queryAll();
		$ret = [];
		foreach($res as $i => $asd){
			$ret[$asd['term_of_price']] = $asd['term_of_price'];
		}
        return $ret;
    }
	
	public function searchLaporan() {
		$query = self::find();
		$query->select(['invoice_id', 't_invoice.nomor', 't_invoice.tanggal AS tanggal_inv', 
                        // '(SELECT CASE WHEN t_invoice.tanggal > \'2019-09-13\' 
                        //     THEN SUM( ROUND( kubikasi_display :: NUMERIC * harga_jual :: NUMERIC, 2 )  ) 
			            //     ELSE SUM( TRUNC((CEIL(kubikasi_display * harga_jual * 100) / 100)::numeric ,2) ) END 
                        //     FROM t_invoice_detail WHERE t_invoice_detail.invoice_id=t_invoice.invoice_id ) AS total_bayar',
                        't_invoice.total_harga',
						't_packinglist.total_volume AS total_volume_inv', 'm_customer.cust_an_nama', 'm_customer.cust_an_alamat', 't_packinglist.goods_description',
						'(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT MIN(thick) AS min_thick, MAX(thick) AS max_thick, MAX(thick_unit) AS thick_unit, MIN(width) AS min_width, MAX(width) AS max_width, MAX(width_unit) AS width_unit, MIN(length) AS min_length, MAX(length) AS max_length, MAX(length_unit) AS length_unit FROM t_packinglist_container WHERE packinglist_id=t_invoice.packinglist_id ) t) AS size',
						't_packinglist.nomor AS nomor_packinglist',
						'(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT container_kode FROM t_packinglist_container WHERE packinglist_id=t_invoice.packinglist_id GROUP BY 1 ) t) AS container_no', 
						't_packinglist.etd AS tgl_etd', 't_packinglist.total_container', 
						'(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT container_size FROM t_packinglist_container WHERE packinglist_id=t_invoice.packinglist_id GROUP BY 1 ) t) AS container_size',
						'(SELECT array_to_json(array_agg(row_to_json(t))) FROM ( SELECT tanggal FROM t_spm_ko WHERE packinglist_id=t_invoice.packinglist_id GROUP BY 1 ) t) AS tanggal_stuffing',
						't_invoice.payment_method', 't_invoice.term_of_price','t_invoice.peb_no','t_invoice.peb_tanggal','t_invoice.bl_no','t_invoice.bl_tanggal',"CONCAT(m_penerbit_bl.nama,' - ',m_penerbit_bl.alamat) AS penerbit",
						't_invoice.fob','t_packinglist.final_destination',
                        't_invoice.total_biaya_tambahan','t_invoice.total_potongan'
						]);
		$query->join('JOIN', 'm_customer','m_customer.cust_id = t_invoice.cust_id');
		$query->join('JOIN', 't_packinglist','t_packinglist.packinglist_id = t_invoice.packinglist_id');
		$query->join('LEFT JOIN', 'm_penerbit_bl','m_penerbit_bl.penerbit_bl_id = t_invoice.penerbit_bl_id');
		$query->where("t_invoice.peb_tanggal IS NOT NULL AND t_invoice.peb_no IS NOT NULL ");//AND piutang_active IS TRUE
		
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.invoice_id ASC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("t_invoice.peb_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->term_of_price)){
			$query->andWhere("t_invoice.term_of_price  = '".$this->term_of_price."'");
		}
		if(!empty($this->payment_method)){
			$query->andWhere("t_invoice.payment_method  = '".$this->payment_method."'");
		}
		if(!empty($this->cust_id)){
			$query->andWhere("t_invoice.cust_id  = ".$this->cust_id);
		}
		return $query;
	}
	
	public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
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
		$param['where'] = ["t_invoice.peb_tanggal IS NOT NULL AND t_invoice.peb_no IS NOT NULL"]; //AND piutang_active IS TRUE
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_invoice.peb_tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->term_of_price)){
			array_push($param['where'],"t_invoice.term_of_price ILIKE '%".$this->term_of_price."%'");
		}
		if(!empty($this->payment_method)){
			array_push($param['where'],"t_invoice.payment_method = '".$this->payment_method."'");
		}
		if(!empty($this->cust_id)){
			array_push($param['where'],"t_invoice.cust_id = ".$this->cust_id);
		}
		return $param;
	}
    
    public static function getOptionListPaymentPiutang($cust_id)
    {
        $res = self::find()->where("cancel_transaksi_id IS NULL AND status != 'PAID' AND cust_id = ".$cust_id." AND piutang_active IS TRUE AND peb_tanggal IS NOT NULL")->orderBy('created_at DESC')->all();
        return \yii\helpers\ArrayHelper::map($res, 'nomor', 'nomor');
    }
    
    public static function updateStatusPembayaran($kode,$status){
        $model = self::findOne(['nomor'=>$kode]);
		$model->status = $status;
        if($model->validate()){
            if($model->save()){
                return true;
            }else{
                return false;
            }
        }
    }
}
