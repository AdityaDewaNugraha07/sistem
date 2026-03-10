<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_tagihan".
 *
 * @property integer $pengajuan_tagihan_id
 * @property string $tanggal
 * @property integer $suplier_id
 * @property integer $terima_bhp_id
 * @property integer $spo_id
 * @property integer $spl_id
 * @property string $tanggal_nota
 * @property string $nomor_nota
 * @property string $nomor_kuitansi
 * @property boolean $is_notaasli
 * @property boolean $is_fakturpajak
 * @property double $nominal
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 * @property integer $kelengkapan_berkas
 * @property integer $no_fakturpajak
 * @property boolean $lunas
 * @property integer $open_voucher_id
 *
 * @property MSuplier $suplier
 * @property TCancelTransaksi $cancelTransaksi
 * @property TSpo $spo
 * @property TTerimaBhp $terimaBhp
 */
class TPengajuanTagihan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $tgl_awal, $tgl_akhir, $alasan_tolak,$terimabhp_kode,$spo_kode,$suplier_nm,$jenis_pembelian;
	public $is_notaasli,$is_kuitansi,$is_fakturpajak,$is_suratjalan,$keterangan_berkas,$kode_tbp,$kode_spo,$suplier;
    public static function tableName()
    {
        return 't_pengajuan_tagihan';
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
            [['tanggal', 'suplier_id', 'terima_bhp_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'tanggal_nota', 'nomor_nota', 'no_fakturpajak', 'nomor_kuitansi', 'created_at', 'updated_at'], 'safe'],
            [['suplier_id', 'terima_bhp_id', 'spo_id', 'spl_id', 'created_by', 'updated_by', 'cancel_transaksi_id', 'open_voucher_id'], 'integer'],
            [['nominal'], 'number'],
            [['status','no_fakturpajak'], 'string', 'max' => 50],
            [['keterangan', 'kelengkapan_berkas'], 'string'],
			[['lunas'], 'boolean'],
            [['suplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MSuplier::className(), 'targetAttribute' => ['suplier_id' => 'suplier_id']],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
            [['spo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TSpo::className(), 'targetAttribute' => ['spo_id' => 'spo_id']],
            [['terima_bhp_id'], 'exist', 'skipOnError' => true, 'targetClass' => TTerimaBhp::className(), 'targetAttribute' => ['terima_bhp_id' => 'terima_bhp_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_tagihan_id' => 'Pengajuan Tagihan',
                'tanggal' => 'Tanggal',
                'suplier_id' => 'Suplier',
                'terima_bhp_id' => 'Terima Bhp',
                'spo_id' => 'Spo',
                'spl_id' => 'Spl',
                'tanggal_nota' => 'Tanggal Nota',
                'nomor_nota' => 'Nomor Nota',
                'no_fakturpajak' => 'Nomor Faktur Pajak',
                'nomor_kuitansi' => 'Nomor Kuitansi',
                'nominal' => 'Nominal',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'kelengkapan_berkas' => 'Kelengkapan Berkas',
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
    public function getSpo()
    {
        return $this->hasOne(TSpo::className(), ['spo_id' => 'spo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerimaBhp()
    {
        return $this->hasOne(TTerimaBhp::className(), ['terima_bhp_id' => 'terima_bhp_id']);
    }
	
	public function searchLaporan() {
		$query = self::find();
		$query->select("pengajuan_tagihan_id,
						tanggal, 
						t_terima_bhp.terimabhp_kode, 
						t_spo.spo_kode, 
						t_spl.spl_kode, 
						m_suplier.suplier_nm, 
						tanggal_nota, 
						nomor_nota, 
						nomor_kuitansi, 
						kelengkapan_berkas, 
						nominal, 
						status");
		$query->join('JOIN', "m_suplier", "m_suplier.suplier_id = t_pengajuan_tagihan.suplier_id");
		$query->join('JOIN', "t_terima_bhp",'t_terima_bhp.terima_bhp_id = t_pengajuan_tagihan.terima_bhp_id');
		$query->join('LEFT JOIN', "t_spo",'t_spo.spo_id = t_pengajuan_tagihan.spo_id');
		$query->join('LEFT JOIN', "t_spl",'t_spl.spl_id = t_pengajuan_tagihan.spl_id');
		$query->andWhere(" t_pengajuan_tagihan.cancel_transaksi_id IS NULL ");
		$query->orderBy( !empty($_GET['sort']['col'])? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir']): 
			'tanggal DESC, t_pengajuan_tagihan.created_at DESC' );
		if( (!empty($this->tgl_awal)) && (!empty($this->tgl_akhir)) ){
			$query->andWhere("tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->suplier_id)){
			$query->andWhere("t_pengajuan_tagihan.suplier_id  = ".$this->suplier_id);
		}
		if(!empty($this->nomor_nota)){
			$query->andWhere("nomor_nota  ILIKE '%".$this->nomor_nota."%'");
		}
		if(!empty($this->jenis_pembelian)){
			if($this->jenis_pembelian == "spo"){
				$query->andWhere("t_pengajuan_tagihan.spo_id IS NOT NULL");
			}else if($this->jenis_pembelian == "spl"){
				$query->andWhere("t_pengajuan_tagihan.spl_id IS NOT NULL");
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
		$param['where'] = ['t_pengajuan_tagihan.cancel_transaksi_id IS NULL'];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		if(!empty($this->suplier_id)){
			array_push($param['where'],"t_pengajuan_tagihan.suplier_id = '".$this->suplier_id."'");
		}
		if(!empty($this->nomor_nota)){
			array_push($param['where'],"nomor_nota ILIKE '%".$this->nomor_nota."%'");
		}
		if(!empty($this->jenis_pembelian)){
			if($this->jenis_pembelian == "spo"){
				array_push($param['where'],"t_pengajuan_tagihan.spo_id IS NOT NULL");
			}else if($this->jenis_pembelian == "spl"){
				array_push($param['where'],"t_pengajuan_tagihan.spl_id IS NOT NULL");
			}
		}
		return $param;
	}
}
