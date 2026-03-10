<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_terima_ko".
 *
 * @property integer $tbko_id
 * @property string $kode
 * @property string $tanggal
 * @property string $nomor_produksi
 * @property string $tanggal_produksi
 * @property integer $produk_id
 * @property integer $qty_palet
 * @property double $qty_kecil
 * @property string $qty_kecil_satuan
 * @property double $qty_m3
 * @property string $keterangan
 * @property string $barcode
 * @property integer $gudang_id
 * @property integer $petugas_penerima
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property string $jenis_penerimaan
 * @property double $p
 * @property string $p_satuan
 * @property double $l
 * @property string $l_satuan
 * @property double $t
 * @property string $t_satuan
 *
 * @property MBrgProduk $produk
 * @property MGudang $gudang
 * @property MPegawai $petugasPenerima
 * @property TCancelTransaksi $cancelTransaksi
 * @property TTerimaKoKd[] $tTerimaKoKds
 */ 
class TTerimaKo extends \app\models\DeltaBaseActiveRecord
{
    /**
     * @inheritdoc
     */
	public $produk_nama,$produk_jenis,$produk_dimensi,$qty_besar_satuan;
	public $total_qty,$total_m3,$total_qty_satuan,$nomor_urut_produksi,$petugas_penerima_nama,$produk_kode,$qty_m3_display;
    public $hasil_repacking_id,$gudang_nm,$total_bayar,$tanggal_scan_terima,$tgl_awal,$tgl_akhir,$username;
    public static function tableName()
    {
        return 't_terima_ko';
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
            [['kode', 'tanggal', 'nomor_produksi', 'tanggal_produksi', 'produk_id', 'qty_kecil_satuan', 'keterangan', 'barcode', 'gudang_id', 'petugas_penerima', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_produksi', 'created_at','qty_kecil', 'updated_at','jenis_penerimaan'], 'safe'],
            [['produk_id', 'qty_palet', 'gudang_id', 'petugas_penerima', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['qty_m3', 'p', 'l', 't'], 'number'],
            [['barcode','jenis_penerimaan'], 'string'],
            [['kode', 'nomor_produksi', 'qty_kecil_satuan','jenis_penerimaan', 'p_satuan', 'l_satuan', 't_satuan'], 'string', 'max' => 50],
            [['keterangan'], 'string', 'max' => 30],
            [['nomor_urut_produksi'], 'string', 'min' => 6],
            [['nomor_urut_produksi'], 'string', 'max' => 6],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
            [['gudang_id'], 'exist', 'skipOnError' => true, 'targetClass' => MGudang::className(), 'targetAttribute' => ['gudang_id' => 'gudang_id']],
            [['petugas_penerima'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['petugas_penerima' => 'pegawai_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
		return [
			'tbko_id' => Yii::t('app', 'Tbko'),
			'kode' => Yii::t('app', 'Kode Penerimaan'),
			'tanggal' => Yii::t('app', 'Tanggal Penerimaan'),
			'nomor_produksi' => Yii::t('app', 'Kode Barang Jadi'),
			'tanggal_produksi' => Yii::t('app', 'Tanggal Produksi'),
			'produk_id' => Yii::t('app', 'Produk'),
			'qty_palet' => Yii::t('app', 'Qty Palet'),
			'qty_kecil' => Yii::t('app', 'Qty Kecil'),
			'qty_kecil_satuan' => Yii::t('app', 'Qty Kecil Satuan'),
			'qty_m3' => Yii::t('app', 'Qty M3'),
			'keterangan' => Yii::t('app', 'Keterangan'),
			'barcode' => Yii::t('app', 'Barcode'),
			'gudang_id' => Yii::t('app', 'Gudang'),
			'petugas_penerima' => Yii::t('app', 'Petugas Penerima'),
			'created_at' => Yii::t('app', 'Create Time'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_at' => Yii::t('app', 'Last Update Time'),
			'updated_by' => Yii::t('app', 'Last Updated By'),
			'cancel_transaksi_id' => Yii::t('app', 'Cancel Transaksi'),
			'jenis_penerimaan' => Yii::t('app', 'Jenis Penerimaan'),
			'p' => Yii::t('app', 'P'),
			'p_satuan' => Yii::t('app', 'P Satuan'),
			'l' => Yii::t('app', 'L'),
			'l_satuan' => Yii::t('app', 'L Satuan'),
			't' => Yii::t('app', 'T'),
			't_satuan' => Yii::t('app', 'T Satuan'),
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
    public function getGudang()
    {
        return $this->hasOne(MGudang::className(), ['gudang_id' => 'gudang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPetugasPenerima()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'petugas_penerima']);
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
    public function getTTerimaKoKds()
    {
        return $this->hasMany(TTerimaKoKd::className(), ['tbko_id' => 'tbko_id']);
    } 
    
    public function searchLaporanRiwayatTerima() {
		$query = self::find();
		$query->select('t_terima_ko.tbko_id,
                        t_terima_ko.kode,
                        t_hasil_repacking.hasil_repacking_id,
                        t_terima_ko.nomor_produksi,
                        m_brg_produk.produk_kode,
                        m_brg_produk.produk_nama,
                        t_terima_ko.tanggal,
                        t_terima_ko.tanggal_produksi,
                        m_gudang.gudang_nm,
                        t_terima_ko.qty_kecil,
                        t_terima_ko.qty_m3,
                        t_terima_ko.created_at, m_user.username,m_brg_produk.produk_dimensi');
		$query->join('JOIN', 'm_brg_produk','m_brg_produk.produk_id = t_terima_ko.produk_id');
		$query->join('JOIN', 'm_gudang','m_gudang.gudang_id = t_terima_ko.gudang_id');
		$query->join('JOIN', 'm_user','m_user.user_id = t_terima_ko.created_by');
		$query->join('LEFT JOIN', 't_hasil_repacking','t_hasil_repacking.nomor_produksi = t_terima_ko.nomor_produksi');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere(self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
//                        $query->andWhere(self::tableName().".tanggal BETWEEN '2021-07-14' AND '2021-07-14' ");
		}
		if(!empty($this->produk_id)){
			$query->andWhere(self::tableName().".produk_id = ".$this->produk_id);
		}
		if(!empty($this->gudang_id)){
			$query->andWhere(self::tableName().".gudang_id = '".$this->gudang_id."'");
		}
		if(!empty($this->nomor_produksi)){
			$query->andWhere(self::tableName().".nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		return $query;
	}
	
	public function searchLaporanRiwayatTerimaDt() {
		$searchLaporan = $this->searchLaporanRiwayatTerima();
		$param['table']= self::tableName();
		$param['pk']= 't_terima_ko.'.self::primaryKey()[0];
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
			array_push($param['where'],$param['table'].".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->produk_id)){
			array_push($param['where'],self::tableName().".produk_id = ".$this->produk_id);
		}
		if(!empty($this->gudang_id)){
			array_push($param['where'],self::tableName().".gudang_id = '".$this->gudang_id."' ");
		}
		if(!empty($this->nomor_produksi)){
			array_push($param['where'],self::tableName().".nomor_produksi ILIKE '%".$this->nomor_produksi."%' ");
		}
		return $param;
	}
}
