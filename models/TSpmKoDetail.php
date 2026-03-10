<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_spm_ko_detail".
 *
 * @property integer $spm_kod_id
 * @property integer $spm_ko_id
 * @property integer $produk_id
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property double $qty_besar_realisasi
 * @property string $satuan_besar_realisasi
 * @property double $qty_kecil_realisasi
 * @property string $satuan_kecil_realisasi
 * @property double $kubikasi_realisasi
 * @property double $harga_hpp
 * @property double $harga_jual
 * @property double $harga_jual_realisasi
 * @property string $keterangan
 *
 * @property MBrgProduk $produk
 * @property TSpmKo $spmKo
 * @property MBrgLog $log
 */
class TSpmKoDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $qty_kecil_perpalet,$kubikasi_realisasi_display,$kubikasi_display;
	public $tgl_awal,$tgl_akhir,$jenis_produk,$cust_id,$no_inv,$size,$total_palet,$total_qty,$total_m3,$kode,$tanggal_kirim,$cust_an_nama;
	public $alamat_bongkar,$produk_group,$produk_nama,$produk_t,$produk_t_satuan,$produk_l,$produk_l_satuan,$produk_p,$produk_p_satuan,$nomor_palet_exist;
	public $log_nama;
    public static function tableName()
    {
        return 't_spm_ko_detail';
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
            [['spm_ko_id', 'produk_id', 'qty_besar', 'satuan_besar', 'qty_kecil', 'satuan_kecil', 'kubikasi', 'harga_hpp', 'harga_jual'], 'required'],
            [['spm_ko_id', 'produk_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi', 'qty_besar_realisasi', 'qty_kecil_realisasi', 'kubikasi_realisasi', 'harga_hpp', 'harga_jual', 'harga_jual_realisasi'], 'number'],
            [['keterangan'], 'string'],
            [['satuan_besar', 'satuan_kecil', 'satuan_besar_realisasi', 'satuan_kecil_realisasi'], 'string', 'max' => 50],
//            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['spm_ko_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpmKo::className(), 'targetAttribute' => ['spm_ko_id' => 'spm_ko_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'spm_kod_id' => Yii::t('app', 'Spm Kod'),
                'spm_ko_id' => Yii::t('app', 'Spm Ko'),
                'produk_id' => Yii::t('app', 'Produk'),
                'qty_besar' => Yii::t('app', 'Qty Besar'),
                'satuan_besar' => Yii::t('app', 'Satuan Besar'),
                'qty_kecil' => Yii::t('app', 'Qty Kecil'),
                'satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
                'kubikasi' => Yii::t('app', 'Kubikasi'),
                'qty_besar_realisasi' => Yii::t('app', 'Qty Besar Realisasi'),
                'satuan_besar_realisasi' => Yii::t('app', 'Satuan Besar Realisasi'),
                'qty_kecil_realisasi' => Yii::t('app', 'Qty Kecil Realisasi'),
                'satuan_kecil_realisasi' => Yii::t('app', 'Satuan Kecil Realisasi'),
                'kubikasi_realisasi' => Yii::t('app', 'Kubikasi Realisasi'),
                'harga_hpp' => Yii::t('app', 'Harga Hpp'),
                'harga_jual' => Yii::t('app', 'Harga Jual'),
                'harga_jual_realisasi' => Yii::t('app', 'Harga Jual Realisasi'),
                'keterangan' => Yii::t('app', 'Keterangan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduk()
    {
        return $this->hasOne(MBrgProduk::className(), ['produk_id' => 'produk_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLimbah()
    {
        return $this->hasOne(MBrgLimbah::className(), ['limbah_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpmKo()
    {
        return $this->hasOne(TSpmKo::className(), ['spm_ko_id' => 'spm_ko_id']);
    }
	
	public function searchLaporanStuffingDetail() {
		$query = self::find();
		$query->select(['t_spm_ko_detail.spm_kod_id',
						't_spm_ko.kode',
						't_invoice.nomor AS no_inv',
						't_spm_ko.tanggal_kirim',
						'm_customer.cust_an_nama',
						't_spm_ko.alamat_bongkar',
						'm_brg_produk.produk_group',
						'm_brg_produk.produk_nama',
						'm_brg_produk.produk_t',
						'm_brg_produk.produk_t_satuan',
						'm_brg_produk.produk_l',
						'm_brg_produk.produk_l_satuan',
						'm_brg_produk.produk_p',
						'm_brg_produk.produk_p_satuan',
						't_spm_ko_detail.qty_besar_realisasi',
						't_spm_ko_detail.qty_kecil_realisasi',
						'ROUND( t_spm_ko_detail.kubikasi_realisasi::numeric,4 ) AS kubikasi',
						]);
		$query->join('JOIN', 't_spm_ko','t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id');
		$query->join('JOIN', 't_packinglist','t_packinglist.packinglist_id = t_spm_ko.packinglist_id');
		$query->join('JOIN', 't_invoice','t_invoice.packinglist_id = t_packinglist.packinglist_id');
		$query->join('JOIN', 'm_customer','m_customer.cust_id = t_packinglist.cust_id');
		$query->join('JOIN', 'm_brg_produk','m_brg_produk.produk_id = t_spm_ko_detail.produk_id');
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
	
	public function searchLaporanStuffingDetailDt() {
		$searchLaporan = $this->searchLaporanStuffingDetail();
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

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getLog()
    {
        return $this->hasOne(MBrgLog::className(), ['log_id' => 'produk_id']);
    }
}
