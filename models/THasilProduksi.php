<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_hasil_produksi".
 *
 * @property integer $hasil_produksi_id
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
 * @property integer $petugas_penerima
 * @property string $jenis_penerimaan
 * @property double $p
 * @property double $l
 * @property double $t
 * @property string $p_satuan
 * @property string $l_satuan
 * @property string $t_satuan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MBrgProduk $produk
 * @property MPegawai $petugasPenerima
 * @property TCancelTransaksi $cancelTransaksi
 * @property THasilProduksiRandom[] $tHasilProduksiRandoms
 */
class THasilProduksi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $produk_nama,$produk_jenis,$produk_dimensi,$qty_besar_satuan;
	public $total_qty,$total_m3,$total_qty_satuan,$nomor_urut_produksi,$petugas_penerima_nama,$produk_kode,$qty_m3_display;
    public $hasil_repacking_id,$total_bayar,$tgl_awal,$tgl_akhir;
    public static function tableName()
    {
        return 't_hasil_produksi';
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
            [['kode', 'tanggal', 'nomor_produksi', 'tanggal_produksi', 'produk_id', 'qty_kecil_satuan', 'keterangan', 'petugas_penerima', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_produksi', 'created_at', 'updated_at'], 'safe'],
            [['produk_id', 'qty_palet', 'petugas_penerima', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['qty_m3', 'p', 'l', 't'], 'number'],
            [['kode', 'nomor_produksi', 'qty_kecil_satuan', 'jenis_penerimaan', 'p_satuan', 'l_satuan', 't_satuan'], 'string', 'max' => 50],
            [['keterangan'], 'string', 'max' => 30],
            [['produk_id'], 'exist', 'skipOnError' => true, 'targetClass' => MBrgProduk::className(), 'targetAttribute' => ['produk_id' => 'produk_id']],
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
                'hasil_produksi_id' => 'Hasil Produksi',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'nomor_produksi' => 'Nomor Produksi',
                'tanggal_produksi' => 'Tanggal Produksi',
                'produk_id' => 'Produk',
                'qty_palet' => 'Qty Palet',
                'qty_kecil' => 'Qty Kecil',
                'qty_kecil_satuan' => 'Qty Kecil Satuan',
                'qty_m3' => 'Qty M3',
                'keterangan' => 'Keterangan',
                'petugas_penerima' => 'Petugas Penerbit',
                'jenis_penerimaan' => 'Jenis Palet',
                'p' => 'P',
                'l' => 'L',
                't' => 'T',
                'p_satuan' => 'P Satuan',
                'l_satuan' => 'L Satuan',
                't_satuan' => 'T Satuan',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
                'cancel_transaksi_id' => 'Cancel Transaksi',
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
    public function getTHasilProduksiRandoms()
    {
        return $this->hasMany(THasilProduksiRandom::className(), ['hasil_produksi_id' => 'hasil_produksi_id']);
    }
    
    public static function getOptionProdukAvail()
    {
        $return = [];
        $mod = self::find()
                ->select("t_hasil_produksi.produk_id, m_brg_produk.produk_nama")
                ->join("JOIN", "m_brg_produk", "m_brg_produk.produk_id = t_hasil_produksi.produk_id")
                ->where(['active'=>true])->orderBy('t_hasil_produksi.created_at DESC')->all();
        foreach($mod as $i => $produk){
            $return[$produk->produk_id] = $produk->produk_nama;
        }
        return $return;
    }
    
    public function searchLaporan() {
        $query = self::find();
        $produk_dimensi = str_replace("'","",$this->produk_dimensi);
        $produk_dimensi = str_replace(",","",$produk_dimensi);
        $produk_dimensi = str_replace(" ","%",$produk_dimensi);        
		$query->select(self::tableName().'.hasil_produksi_id,
                        tanggal, 
                        kode, 
                        nomor_produksi, 
                        tanggal_produksi, 
                        jenis_penerimaan, 
                        produk_nama, 
                        produk_dimensi, 
                        qty_kecil, 
                        qty_m3');
		$query->join('JOIN', 'm_brg_produk','m_brg_produk.produk_id = t_hasil_produksi.produk_id');
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere(self::tableName().".tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->produk_id)){
			$query->andWhere(self::tableName().".produk_id = ".$this->produk_id);
		}
		if(!empty($this->produk_dimensi)){
			$query->andWhere("translate(m_brg_produk.produk_dimensi, ',','') ILIKE '%".$produk_dimensi."%'");
		}
		if(!empty($this->nomor_produksi)){
			$query->andWhere(self::tableName().".nomor_produksi ILIKE '%".$this->nomor_produksi."%'");
		}
		return $query;
	}
	
	public function searchLaporanDt() {
		$searchLaporan = $this->searchLaporan();
		$param['table']= self::tableName();
		$param['pk']= $param['table'].'.'.self::primaryKey()[0];
        $produk_dimensi = str_replace("'","",$this->produk_dimensi);
        $produk_dimensi = str_replace(",","",$produk_dimensi);
        $produk_dimensi = str_replace(" ","%",$produk_dimensi);    
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
		if(!empty($this->produk_dimensi)){
			array_push($param['where'],"translate(m_brg_produk.produk_dimensi, ',','') ILIKE '%".$produk_dimensi."%' ");
		}
		if(!empty($this->nomor_produksi)){
			array_push($param['where'],self::tableName().".nomor_produksi ILIKE '%".$this->nomor_produksi."%' ");
		}
		return $param;
	}
}
