<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_pengajuan_repacking".
 *
 * @property integer $pengajuan_repacking_id
 * @property string $kode
 * @property string $tanggal
 * @property string $keperluan
 * @property string $status
 * @property string $keterangan
 * @property integer $prepared_by
 * @property integer $approved_by
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $cancel_transaksi_id
 *
 * @property MPegawai $preparedBy
 * @property MPegawai $approvedBy
 * @property TPengajuanRepackingDetail[] $tPengajuanRepackingDetails
 */
class TPengajuanRepacking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $total_palet, $total_pcs, $total_m3, $dibuat_oleh;
    public $tgl_awal, $tgl_akhir;
    public static function tableName()
    {
        return 't_pengajuan_repacking';
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
            [['kode', 'tanggal', 'keperluan', 'status', 'prepared_by', 'approved_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['keterangan'], 'string'],
            [['prepared_by', 'approved_by', 'approved2_by', 'created_by', 'updated_by', 'cancel_transaksi_id'], 'integer'],
            [['kode', 'keperluan', 'status'], 'string', 'max' => 50],
            [['prepared_by'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['prepared_by' => 'pegawai_id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => MPegawai::className(), 'targetAttribute' => ['approved_by' => 'pegawai_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'pengajuan_repacking_id' => 'Pengajuan Repacking',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'keperluan' => 'Keperluan',
                'status' => 'Status',
                'keterangan' => 'Keterangan',
                'prepared_by' => 'Prepared By',
                'approved_by' => 'Approved By',
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
    public function getPreparedBy()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'prepared_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(MPegawai::className(), ['pegawai_id' => 'approved_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTPengajuanRepackingDetails()
    {
        return $this->hasMany(TPengajuanRepackingDetail::className(), ['pengajuan_repacking_id' => 'pengajuan_repacking_id']);
    }
    
    public static function getOptionListMutasiKeluar()
    {
        $ret = [];
        $res = self::find()->orderBy('created_at DESC')->all();
        if(!empty($res)){
			foreach($res as $i => $asd){
				$cekapproval = TApproval::find()->where("reff_no = '{$asd->kode}'")->all();
				$approved = true;
				foreach($cekapproval as $i => $apprv){
					if($apprv->status == TApproval::STATUS_APPROVED){
						$approved &= true;
					}else{
						$approved = false;
					}
				}
				if($approved==true){
					$ret[$asd->kode] = $asd->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($asd->tanggal);
				}
			}
		}
        return $ret;
    }
    
    public static function getOptionListKirimGudang()
    {
        $ret = [];
        $res = self::find()->orderBy('created_at DESC')->all();
        if(!empty($res)){
			foreach($res as $i => $asd){
				$cekapproval = TApproval::find()->where("reff_no = '{$asd->kode}'")->all();
				$approved = true;
				foreach($cekapproval as $i => $apprv){
					if($apprv->status == TApproval::STATUS_APPROVED){
						$approved &= true;
					}else{
						$approved = false;
					}
				}
				if($approved==true){
					$ret[$asd->pengajuan_repacking_id] = $asd->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($asd->tanggal);
				}
			}
		}
        return $ret;
    }
    
    public static function getOptionListScanMutasi()
    {
        //$bulan_ini = date("m");
        $tahun_ini = date("Y");
        /*$res = self::find()->where("cancel_transaksi_id IS NULL 
                                AND (status != 'COMPLETE') 
                                AND extract(year from tanggal) = '".$tahun_ini."'")
							->orderBy(self::tableName().'.created_at DESC')->all();*/
        $res = self::find()->where("cancel_transaksi_id IS NULL 
                                AND (status != 'COMPLETE') 
                                AND tanggal > CURRENT_DATE - INTERVAL '2 months'")
							->orderBy(self::tableName().'.created_at DESC')->all();

        $return = [];
        if(!empty($res)){
			foreach($res as $i => $asd){
				$cekapproval = TApproval::find()->where("reff_no = '{$asd->kode}'")->all();
				$approved = true;
				foreach($cekapproval as $i => $apprv){
					if($apprv->status == TApproval::STATUS_APPROVED){
						$approved &= true;
					}else{
						$approved = false;
					}
				}
				if($approved==true){
					$return[$asd->pengajuan_repacking_id] = $asd->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($asd->tanggal)." - ".$asd->keperluan;
				}
			}
		}
        return $return;
    }

    public function searchLaporan(){
        $query = self::find();
        $pcs_permintaan = "COALESCE(detail.qty_besar, 0)";
        $vol_permintaan = "COALESCE(detail.vol, 0)";
        $pcs_mk = "COALESCE(mk.jml_mutasi_keluar, 0)";
        $vol_mk = "COALESCE(mk.vol, 0)";
        $pcs_tm = "COALESCE(tm.jml_terima_mutasi, 0)";
        $vol_tm = "COALESCE(tm.vol, 0)";
        $pcs_kg = "COALESCE(map.kirimke_gudang, 0)";
        $vol_kg = "COALESCE(map.vol, 0)";
        $pcs_tg = "COALESCE(tg.terima_gudang, 0)";
        $vol_tg = "COALESCE(tg.vol, 0)";
        $query->select([
            "t_pengajuan_repacking.kode", 
            "t_pengajuan_repacking.tanggal", 
            "t_pengajuan_repacking.approval_status",
            "{$pcs_permintaan} AS pcs_permintaan" ,
            "{$vol_permintaan} AS vol_permintaan" ,
            "{$pcs_mk} AS pcs_mk" ,
            "{$vol_mk} AS vol_mk" ,
            "{$pcs_tm} AS pcs_tm" ,
            "{$vol_tm} AS vol_tm" ,
            "{$pcs_kg} AS pcs_kg" ,
            "{$vol_kg} AS vol_kg" ,
            "{$pcs_tg} AS pcs_tg" ,
            "{$vol_tg} AS vol_tg"
        ]);
        $query->innerJoin("(SELECT pengajuan_repacking_id, sum(qty_besar) as qty_besar, sum(kubikasi) as vol FROM t_pengajuan_repacking_detail GROUP BY pengajuan_repacking_id, qty_besar) 
	                        detail", "detail.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id");
        $query->leftJoin("(SELECT pengajuan_repacking_id, COUNT(*) AS jml_mutasi_keluar, sum(kubikasi) as vol FROM t_mutasi_keluar 
                            LEFT JOIN t_retur_produk_detail ON t_retur_produk_detail.nomor_produksi = t_mutasi_keluar.nomor_produksi GROUP BY pengajuan_repacking_id) 
                            mk", "mk.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id");
        $query->leftJoin("(SELECT reff_no2, COUNT(*) AS jml_terima_mutasi, sum(kubikasi) as vol FROM t_terima_mutasi 
                            LEFT JOIN t_retur_produk_detail ON t_retur_produk_detail.nomor_produksi = t_terima_mutasi.nomor_produksi GROUP BY reff_no2) 
                            tm", "tm.reff_no2 = t_pengajuan_repacking.kode");
        $query->leftJoin("(SELECT pengajuan_repacking_id, COUNT(*) AS kirimke_gudang, sum(qty_m3) as vol FROM map_terimamutasi_hasilrepacking 
                            LEFT JOIN t_hasil_repacking ON t_hasil_repacking.hasil_repacking_id = map_terimamutasi_hasilrepacking.hasil_repacking_id 
                            GROUP BY pengajuan_repacking_id) 
                            map", "map.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id");
        $query->leftJoin("(SELECT mth.pengajuan_repacking_id, COUNT(*) AS terima_gudang, sum(qty_m3) as vol FROM map_terimamutasi_hasilrepacking mth
                            JOIN t_terima_ko tko ON tko.nomor_produksi = mth.nomor_produksi_baru GROUP BY mth.pengajuan_repacking_id ) 
                            tg", "tg.pengajuan_repacking_id = t_pengajuan_repacking.pengajuan_repacking_id");
        $query->groupBy("t_pengajuan_repacking.kode, t_pengajuan_repacking.tanggal, t_pengajuan_repacking.keperluan, t_pengajuan_repacking.approval_status,
                            t_pengajuan_repacking.pengajuan_repacking_id, mk.jml_mutasi_keluar, tm.jml_terima_mutasi, map.kirimke_gudang, tg.terima_gudang,
                            detail.qty_besar, detail.vol, mk.vol, tm.vol, map.vol,tg.vol");
        $query->orderBy( 
            !empty($_GET['sort']['col'])
            ? \app\components\SSP::cekTitik($query->select[$_GET['sort']['col']]) ." ".strtoupper($_GET['sort']['dir'])
            : self::tableName().'.pengajuan_repacking_id DESC' 
        );
        $query->andWhere("keperluan = 'Penanganan Barang Retur'");
		if(!empty($this->tgl_awal)){
			$query->andWhere("t_pengajuan_repacking.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."'");
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
		$param['where'] = ["keperluan = 'Penanganan Barang Retur'"];
		if( (!empty($this->tgl_awal)) || (!empty($this->tgl_akhir)) ){
			array_push($param['where'],"t_pengajuan_repacking.tanggal BETWEEN '".$this->tgl_awal."' AND '".$this->tgl_akhir."' ");
		}
		return $param;
	}
    
}
