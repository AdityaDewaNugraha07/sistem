<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_dokumen_penjualan_detail".
 *
 * @property integer $dokumen_penjualan_detail_id
 * @property integer $dokumen_penjualan_id
 * @property integer $produk_id
 * @property integer $spm_kod_id
 * @property double $qty_besar
 * @property string $satuan_besar
 * @property double $qty_kecil
 * @property string $satuan_kecil
 * @property double $kubikasi
 * @property string $keterangan
 * @property integer $spm_log_id
 *
 * @property MBrgProduk $produk
 * @property TDokumenPenjualan $dokumenPenjualan
 * @property TSpmKoDetail $spmKod
 * @property MBrgLog $log
 */
class TDokumenPenjualanDetail extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $tgl_awal, $tgl_akhir, $jenis_produk, $cust_id;
    public static function tableName()
    {
        return 't_dokumen_penjualan_detail';
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
            [['dokumen_penjualan_id', 'produk_id', 'spm_kod_id', 'qty_besar', 'satuan_besar', 'qty_kecil', 'satuan_kecil', 'kubikasi'], 'required'],
            [['dokumen_penjualan_id', 'produk_id', 'spm_kod_id', 'spm_log_id'], 'integer'],
            [['qty_besar', 'qty_kecil', 'kubikasi'], 'number'],
            [['keterangan'], 'string'],
            [['satuan_besar', 'satuan_kecil'], 'string', 'max' => 50],
//            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['dokumen_penjualan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TDokumenPenjualan::className(), 'targetAttribute' => ['dokumen_penjualan_id' => 'dokumen_penjualan_id']],
            [['spm_kod_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpmKoDetail::className(), 'targetAttribute' => ['spm_kod_id' => 'spm_kod_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'dokumen_penjualan_detail_id' => Yii::t('app', 'Dokumen Penjualan Detail'),
                'dokumen_penjualan_id' => Yii::t('app', 'Dokumen Penjualan'),
                'produk_id' => Yii::t('app', 'Produk'),
                'spm_kod_id' => Yii::t('app', 'Spm Kod'),
                'qty_besar' => Yii::t('app', 'Qty Besar'),
                'satuan_besar' => Yii::t('app', 'Satuan Besar'),
                'qty_kecil' => Yii::t('app', 'Qty Kecil'),
                'satuan_kecil' => Yii::t('app', 'Satuan Kecil'),
                'kubikasi' => Yii::t('app', 'Kubikasi'),
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
    public function getProdukJasa()
    {
        return $this->hasOne(MProdukJasa::className(), ['produk_jasa_id' => 'produk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenPenjualan()
    {
        return $this->hasOne(TDokumenPenjualan::className(), ['dokumen_penjualan_id' => 'dokumen_penjualan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpmKod()
    {
        return $this->hasOne(TSpmKoDetail::className(), ['spm_kod_id' => 'spm_kod_id']);
    }
	
	
	public function searchLaporan() {
		$query = self::find();
		if ($this->jenis_produk == "JasaKD") {
			$query->select("t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, 
							t_dokumen_penjualan.tanggal, 
							nomor_dokumen, 
							t_dokumen_penjualan.jenis_produk, 
							m_customer.cust_an_nama, 
							m_produk_jasa.nama, 
							'-' as produk_t, 
							'' as produk_t_satuan, 
							'-' as produk_l, 
							'' as produk_l_satuan, 
							'-' as produk_p, 
							'' as produk_p_satuan, 
							t_dokumen_penjualan_detail.qty_besar, 
							t_dokumen_penjualan_detail.qty_kecil, 
							t_dokumen_penjualan_detail.kubikasi, 
							t_op_ko_random.op_ko_random_id, 
							t_op_ko_random.t, 
							t_op_ko_random.l, 
							t_op_ko_random.p, 
							t_op_ko_random.qty_kecil AS random_qty_kecil, 
							t_op_ko_random.kubikasi AS random_kubikasi");
			$query->join('JOIN', 't_dokumen_penjualan','t_dokumen_penjualan_detail.dokumen_penjualan_id = t_dokumen_penjualan.dokumen_penjualan_id');
			$query->join('JOIN', 'm_produk_jasa','m_produk_jasa.produk_jasa_id = t_dokumen_penjualan_detail.produk_id');
			$query->join('JOIN', 'm_customer','m_customer.cust_id = t_dokumen_penjualan.cust_id');
			$query->join('JOIN', 't_spm_ko_detail','t_spm_ko_detail.spm_kod_id = t_dokumen_penjualan_detail.spm_kod_id');
			$query->join('JOIN', 't_spm_ko','t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id');
			$query->join('JOIN', 't_op_ko','t_op_ko.op_ko_id = t_spm_ko.op_ko_id');
			$query->join('JOIN', 't_op_ko_detail','t_op_ko.op_ko_id = t_op_ko_detail.op_ko_id');
			$query->join('LEFT JOIN', 't_op_ko_random','t_op_ko_random.op_ko_detail_id = t_op_ko_detail.op_ko_detail_id');
			$query->groupBy('t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, t_dokumen_penjualan.tanggal, nomor_dokumen, 
								t_dokumen_penjualan.jenis_produk, m_customer.cust_an_nama, m_produk_jasa.nama, produk_t, 
								produk_t_satuan, produk_l, produk_l_satuan, produk_p, produk_p_satuan,t_op_ko_random.op_ko_random_id, 
								t_op_ko_random.t, t_op_ko_random.l, t_op_ko_random.p, t_op_ko_random.qty_kecil, t_op_ko_random.kubikasi');
			$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
				't_dokumen_penjualan_detail.dokumen_penjualan_detail_id DESC' );
			if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
				$query->andWhere("t_dokumen_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
			}
			if(!empty($this->jenis_produk)){
				$query->andWhere("t_dokumen_penjualan.jenis_produk ILIKE '%".$this->jenis_produk."%'");
			}
			if(!empty($this->cust_id)){
				$query->andWhere("t_dokumen_penjualan.cust_id = ".$this->cust_id);
			}
			if(!empty($this->dokumen_penjualan_id)){
				$query->andWhere("t_dokumen_penjualan_detail.dokumen_penjualan_id = ".$this->dokumen_penjualan_id);
			}
		} else {
			/*$query->select("t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, 
							t_dokumen_penjualan.tanggal, nomor_dokumen, 
							t_dokumen_penjualan.jenis_produk, 
							m_customer.cust_an_nama, 
							m_brg_produk.produk_nama, 
							m_brg_produk.produk_t, 
							m_brg_produk.produk_t_satuan, 
							m_brg_produk.produk_l, 
							m_brg_produk.produk_l_satuan, 
							m_brg_produk.produk_p, 
							m_brg_produk.produk_p_satuan, 
							t_dokumen_penjualan_detail.qty_besar, 
							t_dokumen_penjualan_detail.qty_kecil, 
							t_dokumen_penjualan_detail.kubikasi, 
							t_op_ko_random.op_ko_random_id, 
							t_op_ko_random.t, t_op_ko_random.l, 
							t_op_ko_random.p, 
							t_op_ko_random.qty_kecil AS random_qty_kecil, 
							t_op_ko_random.kubikasi AS random_kubikasi");
			$query->join('JOIN', 't_dokumen_penjualan','t_dokumen_penjualan_detail.dokumen_penjualan_id = t_dokumen_penjualan.dokumen_penjualan_id');
			$query->join('JOIN', 'm_brg_produk','m_brg_produk.produk_id = t_dokumen_penjualan_detail.produk_id');
			$query->join('JOIN', 'm_customer','m_customer.cust_id = t_dokumen_penjualan.cust_id');
			$query->join('JOIN', 't_spm_ko_detail','t_spm_ko_detail.spm_kod_id = t_dokumen_penjualan_detail.spm_kod_id');
			$query->join('JOIN', 't_spm_ko','t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id');
			$query->join('JOIN', 't_op_ko','t_op_ko.op_ko_id = t_spm_ko.op_ko_id');
			$query->join('JOIN', 't_op_ko_detail','t_op_ko.op_ko_id = t_op_ko_detail.op_ko_id');
			$query->join('LEFT JOIN', 't_op_ko_random','t_op_ko_random.op_ko_detail_id = t_op_ko_detail.op_ko_detail_id');
			$query->groupBy('t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, t_dokumen_penjualan.tanggal, nomor_dokumen, t_dokumen_penjualan.jenis_produk, m_customer.cust_an_nama, m_brg_produk.produk_nama, m_brg_produk.produk_t, m_brg_produk.produk_t_satuan, m_brg_produk.produk_l, m_brg_produk.produk_l_satuan, m_brg_produk.produk_p, m_brg_produk.produk_p_satuan,t_op_ko_random.op_ko_random_id, t_op_ko_random.t, t_op_ko_random.l, t_op_ko_random.p, t_op_ko_random.qty_kecil, t_op_ko_random.kubikasi');*/
			$query->select("t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, 
							t_dokumen_penjualan.tanggal, nomor_dokumen, 
							t_dokumen_penjualan.jenis_produk, 
							m_customer.cust_an_nama, 
							m_brg_produk.produk_nama, 
							m_brg_produk.produk_t, 
							m_brg_produk.produk_t_satuan, 
							m_brg_produk.produk_l, 
							m_brg_produk.produk_l_satuan, 
							m_brg_produk.produk_p, 
							m_brg_produk.produk_p_satuan, 
							t_dokumen_penjualan_detail.qty_besar, 
							t_dokumen_penjualan_detail.qty_kecil, 
                            t_dokumen_penjualan_detail.kubikasi");
			$query->join('JOIN', 't_dokumen_penjualan','t_dokumen_penjualan_detail.dokumen_penjualan_id = t_dokumen_penjualan.dokumen_penjualan_id');
			$query->join('JOIN', 'm_brg_produk','m_brg_produk.produk_id = t_dokumen_penjualan_detail.produk_id');
			$query->join('JOIN', 'm_customer','m_customer.cust_id = t_dokumen_penjualan.cust_id');
			$query->join('JOIN', 't_spm_ko_detail','t_spm_ko_detail.spm_kod_id = t_dokumen_penjualan_detail.spm_kod_id');
			$query->join('JOIN', 't_spm_ko','t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id');
			$query->join('JOIN', 't_op_ko','t_op_ko.op_ko_id = t_spm_ko.op_ko_id');
			$query->join('JOIN', 't_op_ko_detail','t_op_ko.op_ko_id = t_op_ko_detail.op_ko_id');
            $query->groupBy("t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, t_dokumen_penjualan.tanggal, nomor_dokumen, 
                                t_dokumen_penjualan.jenis_produk, m_customer.cust_an_nama, 
                                m_brg_produk.produk_nama, 
                                m_brg_produk.produk_t, m_brg_produk.produk_t_satuan, 
                                m_brg_produk.produk_l, m_brg_produk.produk_l_satuan, 
                                m_brg_produk.produk_p, m_brg_produk.produk_p_satuan");
			$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
				't_dokumen_penjualan_detail.dokumen_penjualan_detail_id DESC' );
			if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
				$query->andWhere("t_dokumen_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
			}
			if(!empty($this->jenis_produk)){
				$query->andWhere("t_dokumen_penjualan.jenis_produk ILIKE '%".$this->jenis_produk."%'");
			}
			if(!empty($this->cust_id)){
				$query->andWhere("t_dokumen_penjualan.cust_id = ".$this->cust_id);
			}
			if(!empty($this->dokumen_penjualan_id)){
				$query->andWhere("t_dokumen_penjualan_detail.dokumen_penjualan_id = ".$this->dokumen_penjualan_id);
			}			
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
			array_push($param['where'],"t_dokumen_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->jenis_produk)){
			array_push($param['where'],"t_dokumen_penjualan.jenis_produk ILIKE '%".$this->jenis_produk."%' ");
		}
		if(!empty($this->cust_id)){
			array_push($param['where'],"t_dokumen_penjualan.cust_id = ".$this->cust_id);
		}
		if(!empty($this->dokumen_penjualan_id)){
			array_push($param['where'],"t_dokumen_penjualan_detail.dokumen_penjualan_id = ".$this->dokumen_penjualan_id);
		}
		return $param;
	}

	public function searchLaporanX() {
		$query = self::find();
		if ($this->jenis_produk == "JasaKD") {
			$query->select("t_dokumen_penjualan_detail.dokumen_penjualan_detail_id,
							t_dokumen_penjualan.tanggal, 
							t_dokumen_penjualan.nomor_dokumen, 
							t_dokumen_penjualan.jenis_produk, 
							m_customer.cust_an_nama, 
							m_produk_jasa.nama, 
							t_terima_jasa.t as produk_t, 
							'mm' as produk_t_satuan,
							t_terima_jasa.l as produk_l, 
							'mm' as produk_l_satuan,
							t_terima_jasa.p as produk_p, 
							'mm' as produk_p_satuan,
							'1' as total_palet,
							t_terima_jasa.qty_kecil, 
							t_terima_jasa.kubikasi,
							t_op_ko_random.op_ko_random_id,
							t_op_ko_random.t,
							t_op_ko_random.l,
							t_op_ko_random.p, 
							t_op_ko_random.qty_kecil AS random_qty_kecil, 
							t_op_ko_random.kubikasi AS random_kubikasi,
							t_spm_ko_detail.keterangan
						");
			$query->join("JOIN", "t_dokumen_penjualan", "t_dokumen_penjualan.dokumen_penjualan_id = t_dokumen_penjualan_detail.dokumen_penjualan_id");
			$query->join("JOIN", "t_spm_ko", "t_spm_ko.spm_ko_id = t_dokumen_penjualan.spm_ko_id");
			$query->join("JOIN", "t_spm_ko_detail", "t_spm_ko_detail.spm_ko_id = t_spm_ko.spm_ko_id");
			$query->join("JOIN", "m_customer", "m_customer.cust_id = t_spm_ko.cust_id");
			$query->join("JOIN", "t_op_ko", "t_op_ko.op_ko_id = t_spm_ko.op_ko_id");
			$query->join("JOIN", "t_op_ko_detail", "t_op_ko_detail.op_ko_id = t_op_ko.op_ko_id");
			$query->join("JOIN", "t_terima_jasa", "t_terima_jasa.op_ko_id = t_op_ko.op_ko_id ");
			$query->join("JOIN", "m_produk_jasa", "m_produk_jasa.produk_jasa_id = t_terima_jasa.produk_jasa_id ");
			$query->join('LEFT JOIN', 't_op_ko_random','t_op_ko_random.op_ko_detail_id = t_op_ko_detail.op_ko_detail_id');
			$query->orderBy( !empty($_GET['sort']['col']) ?
				\app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']) : 't_dokumen_penjualan_detail.dokumen_penjualan_detail_id DESC' );

			if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
				$query->andWhere("t_dokumen_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
			}
			if(!empty($this->jenis_produk)){
				$query->andWhere("t_dokumen_penjualan.jenis_produk ILIKE '%".$this->jenis_produk."%'");
				//$xxx = "'12','16','2','3','6','7','75'";
				$sql = "select t_spm_ko_detail.keterangan 
							from t_spm_ko_detail 
							left join t_spm_ko on t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id
							where t_spm_ko.tanggal between '".$this->tgl_awal."' and '".$this->tgl_akhir."'
							and (t_spm_ko_detail.produk_id = 31 
							or  t_spm_ko_detail.produk_id = 32 
							or  t_spm_ko_detail.produk_id = 33 
							or  t_spm_ko_detail.produk_id = 34) ";
				$xxx = Yii::$app->db->createCommand($sql)->queryScalar();
				$query->andWhere("t_terima_jasa.nomor_palet in (".$xxx.")");
			}
			if(!empty($this->cust_id)){
				$query->andWhere("t_dokumen_penjualan.cust_id = ".$this->cust_id);
			}
			if(!empty($this->dokumen_penjualan_id)){
				$query->andWhere("t_dokumen_penjualan_detail.dokumen_penjualan_id = ".$this->dokumen_penjualan_id);
			}

		} else {
			$query->select("t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, 
							t_dokumen_penjualan.tanggal, nomor_dokumen, 
							t_dokumen_penjualan.jenis_produk, 
							m_customer.cust_an_nama, 
							m_brg_produk.produk_nama, 
							m_brg_produk.produk_t, 
							m_brg_produk.produk_t_satuan, 
							m_brg_produk.produk_l, 
							m_brg_produk.produk_l_satuan, 
							m_brg_produk.produk_p, 
							m_brg_produk.produk_p_satuan, 
							t_dokumen_penjualan_detail.qty_besar, 
							t_dokumen_penjualan_detail.qty_kecil, 
							t_dokumen_penjualan_detail.kubikasi, 
							t_op_ko_random.op_ko_random_id, 
							t_op_ko_random.t, t_op_ko_random.l, 
							t_op_ko_random.p, 
							t_op_ko_random.qty_kecil AS random_qty_kecil, 
							t_op_ko_random.kubikasi AS random_kubikasi");
			$query->join('JOIN', 't_dokumen_penjualan','t_dokumen_penjualan_detail.dokumen_penjualan_id = t_dokumen_penjualan.dokumen_penjualan_id');
			$query->join('JOIN', 'm_brg_produk','m_brg_produk.produk_id = t_dokumen_penjualan_detail.produk_id');
			$query->join('JOIN', 'm_customer','m_customer.cust_id = t_dokumen_penjualan.cust_id');
			$query->join('JOIN', 't_spm_ko_detail','t_spm_ko_detail.spm_kod_id = t_dokumen_penjualan_detail.spm_kod_id');
			$query->join('JOIN', 't_spm_ko','t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id');
			$query->join('JOIN', 't_op_ko','t_op_ko.op_ko_id = t_spm_ko.op_ko_id');
			$query->join('JOIN', 't_op_ko_detail','t_op_ko.op_ko_id = t_op_ko_detail.op_ko_id');
			$query->join('LEFT JOIN', 't_op_ko_random','t_op_ko_random.op_ko_detail_id = t_op_ko_detail.op_ko_detail_id');
			$query->groupBy('t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, t_dokumen_penjualan.tanggal, nomor_dokumen, t_dokumen_penjualan.jenis_produk, m_customer.cust_an_nama, m_brg_produk.produk_nama, m_brg_produk.produk_t, m_brg_produk.produk_t_satuan, m_brg_produk.produk_l, m_brg_produk.produk_l_satuan, m_brg_produk.produk_p, m_brg_produk.produk_p_satuan,t_op_ko_random.op_ko_random_id, t_op_ko_random.t, t_op_ko_random.l, t_op_ko_random.p, t_op_ko_random.qty_kecil, t_op_ko_random.kubikasi');
			$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
				't_dokumen_penjualan_detail.dokumen_penjualan_detail_id DESC' );
			if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
				$query->andWhere("t_dokumen_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
			}
			if(!empty($this->jenis_produk)){
				$query->andWhere("t_dokumen_penjualan.jenis_produk ILIKE '%".$this->jenis_produk."%'");
			}
			if(!empty($this->cust_id)){
				$query->andWhere("t_dokumen_penjualan.cust_id = ".$this->cust_id);
			}
			if(!empty($this->dokumen_penjualan_id)){
				$query->andWhere("t_dokumen_penjualan_detail.dokumen_penjualan_id = ".$this->dokumen_penjualan_id);
			}		
		}			
		return $query;
	}	

	public function searchLaporanDtX() {
		$searchLaporan = $this->searchLaporanX();
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
			array_push($param['where'],"t_dokumen_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->jenis_produk)){
			array_push($param['where'],"t_dokumen_penjualan.jenis_produk ILIKE '%".$this->jenis_produk."%' ");
			if ($this->jenis_produk = 'JasaKD') {
				$sql = "select t_spm_ko_detail.keterangan 
							from t_spm_ko_detail 
							left join t_spm_ko on t_spm_ko.spm_ko_id = t_spm_ko_detail.spm_ko_id
							where t_spm_ko.tanggal between '".$this->tgl_awal."' and '".$this->tgl_akhir."'
							and (t_spm_ko_detail.produk_id = 31 
							or  t_spm_ko_detail.produk_id = 32 
							or  t_spm_ko_detail.produk_id = 33 
							or  t_spm_ko_detail.produk_id = 34) ";
				$xxx = Yii::$app->db->createCommand($sql)->queryScalar();
				if (!empty($xxx)) {
					array_push($param['where'],"t_terima_jasa.nomor_palet in (".$xxx.")");
				}
			}
		}
		if(!empty($this->cust_id)){
			array_push($param['where'],"t_dokumen_penjualan.cust_id = ".$this->cust_id);
		}
		if(!empty($this->dokumen_penjualan_id)){
			array_push($param['where'],"t_dokumen_penjualan_detail.dokumen_penjualan_id = ".$this->dokumen_penjualan_id);
		}
		return $param;
	}

#####################################################
	public function getLog()
    {
        return $this->hasOne(MBrgLog::className(), ['log_id' => 'produk_id']);
    }

	public function searchLaporanKb() {
		$query = self::find();
			$query->select("t_dokumen_penjualan_detail.dokumen_penjualan_detail_id, 
							t_dokumen_penjualan.tanggal, 
							t_dokumen_penjualan.nomor_dokumen, 
							m_customer.cust_an_nama, 
							m_kayu.group_kayu, 
							m_kayu.kayu_nama, 
							m_brg_log.range_awal, 
							m_brg_log.range_akhir,
							t_spm_log.panjang, 
							t_spm_log.diameter_ujung1,
							t_spm_log.diameter_ujung2,
							t_spm_log.diameter_pangkal1,
							t_spm_log.diameter_pangkal2,
							t_spm_log.diameter_rata, 
							t_spm_log.cacat_panjang,
							t_spm_log.cacat_gb,
							t_spm_log.cacat_gr,
							t_dokumen_penjualan_detail.kubikasi,
							t_spm_log.no_lap,
							t_spm_log.no_grade,
							t_spm_log.no_btg");
			$query->join('JOIN', 't_dokumen_penjualan','t_dokumen_penjualan_detail.dokumen_penjualan_id = t_dokumen_penjualan.dokumen_penjualan_id');
			$query->join('JOIN', 'm_brg_log','m_brg_log.log_id = t_dokumen_penjualan_detail.produk_id');
			$query->join('JOIN', 'm_customer','m_customer.cust_id = t_dokumen_penjualan.cust_id');
			$query->join('JOIN', 't_spm_log','t_spm_log.volume = t_dokumen_penjualan_detail.kubikasi');
			$query->join('JOIN', 'm_kayu','m_kayu.kayu_id = m_brg_log.kayu_id');
            $query->groupBy("t_dokumen_penjualan_detail.dokumen_penjualan_detail_id,t_dokumen_penjualan.tanggal, t_dokumen_penjualan.nomor_dokumen,
								t_dokumen_penjualan_detail.kubikasi,m_customer.cust_an_nama, m_customer.cust_an_nama, m_brg_log.log_nama, m_brg_log.range_awal, 
								m_brg_log.range_akhir, t_spm_log.panjang,t_spm_log.diameter_ujung1, t_spm_log.diameter_ujung2,t_spm_log.diameter_pangkal1, 
								t_spm_log.diameter_pangkal2,t_spm_log.diameter_rata, t_spm_log.cacat_panjang,t_spm_log.cacat_gb,t_spm_log.cacat_gr, m_kayu.group_kayu, 
								m_kayu.kayu_nama, t_spm_log.no_lap, t_spm_log.no_grade, t_spm_log.no_btg");
			$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
				't_dokumen_penjualan_detail.dokumen_penjualan_detail_id DESC' );
			$query->where("t_dokumen_penjualan.jenis_produk = 'Log'");
			if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
				$query->andWhere("t_dokumen_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
			}
			if(!empty($this->cust_id)){
				$query->andWhere("t_dokumen_penjualan.cust_id = ".$this->cust_id);
			}
			if(!empty($this->dokumen_penjualan_id)){
				$query->andWhere("t_dokumen_penjualan_detail.dokumen_penjualan_id = ".$this->dokumen_penjualan_id);
			}			
		return $query;
	}
	
	public function searchLaporanKbDt() {
		$searchLaporan = $this->searchLaporanKb();
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
		$param['where'] = ["t_dokumen_penjualan.jenis_produk = 'Log'"];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_dokumen_penjualan.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->cust_id)){
			array_push($param['where'],"t_dokumen_penjualan.cust_id = ".$this->cust_id);
		}
		if(!empty($this->dokumen_penjualan_id)){
			array_push($param['where'],"t_dokumen_penjualan_detail.dokumen_penjualan_id = ".$this->dokumen_penjualan_id);
		}
		return $param;
	}
}
