<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_kuitansi".
 *
 * @property integer $kuitansi_id
 * @property string $nomor
 * @property string $tanggal
 * @property string $reff_tagihan
 * @property string $reff_penerimaan
 * @property integer $cust_id
 * @property string $terima_dari
 * @property string $untuk_pembayaran
 * @property string $cara_bayar
 * @property double $nominal
 * @property integer $petugas
 * @property string $keterangan
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MCustomer $cust
 * @property MPegawai $petugas0
 */
class TKuitansi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $cust_an_nama,$cust_an_alamat,$petugas_nama,$tgl_awal,$tgl_akhir;
    public static function tableName()
    {
        return 't_kuitansi';
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
            [['nomor', 'tanggal', 'reff_tagihan', 'reff_penerimaan', 'terima_dari', 'untuk_pembayaran', 'cara_bayar', 'nominal', 'petugas', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['cust_id', 'petugas', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['keterangan'], 'string'],
            [['nomor', 'reff_tagihan', 'reff_penerimaan', 'cara_bayar'], 'string', 'max' => 50],
            [['terima_dari', 'untuk_pembayaran'], 'string', 'max' => 250],
            [['cust_id'], 'exist', 'skipOnError' => true, 'targetClass' => MCustomer::className(), 'targetAttribute' => ['cust_id' => 'cust_id']],
            [['petugas'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['petugas' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'kuitansi_id' => 'Kuitansi',
                'nomor' => 'Nomor',
                'tanggal' => 'Tanggal',
                'reff_tagihan' => 'Reff Tagihan',
                'reff_penerimaan' => 'Reff Penerimaan',
                'cust_id' => 'Cust',
                'terima_dari' => 'Terima Dari',
                'untuk_pembayaran' => 'Untuk Pembayaran',
                'cara_bayar' => 'Cara Bayar',
                'nominal' => 'Nominal',
                'petugas' => 'Petugas',
                'keterangan' => 'Keterangan',
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
    public function getCust()
    {
        return $this->hasOne(MCustomer::className(), ['cust_id' => 'cust_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPetugas0()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'petugas']);
    }
	
	public function searchLaporan() {
		$query = self::find();
		$query->select("t_kuitansi.kuitansi_id,
						t_kuitansi.nomor, 
						t_kuitansi.tanggal, 
						t_kuitansi.cara_bayar, 
						(CASE WHEN cara_bayar = 'Tunai' THEN (SELECT kode || '/' || EXTRACT(year FROM tanggal) AS kode FROM t_kas_besar WHERE t_kuitansi.reff_penerimaan::integer = t_kas_besar.kas_besar_id ) ELSE t_kuitansi.reff_penerimaan END) AS reff_penerimaan, 
						t_kuitansi.terima_dari, 
						t_kuitansi.untuk_pembayaran, 
						t_kuitansi.nominal, 
						t_kuitansi.keterangan 
						");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			self::tableName().'.created_at DESC, t_kuitansi.kuitansi_id ASC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->cara_bayar)){
			$query->andWhere("cara_bayar ILIKE '%".$this->cara_bayar."%'");
		}
		if(!empty($this->terima_dari)){
			$query->andWhere("terima_dari ILIKE '%".$this->terima_dari."%'");
		}
		if(!empty($this->untuk_pembayaran)){
			$query->andWhere("untuk_pembayaran ILIKE '%".$this->untuk_pembayaran."%'");
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
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->cara_bayar)){
			array_push($param['where'],"cara_bayar ILIKE '%".$this->cara_bayar."%'");
		}
		if(!empty($this->terima_dari)){
			array_push($param['where'],"terima_dari ILIKE '%".$this->terima_dari."%'");
		}
		if(!empty($this->untuk_pembayaran)){
			array_push($param['where'],"untuk_pembayaran ILIKE '%".$this->untuk_pembayaran."%'");
		}
		return $param;
	}
}
