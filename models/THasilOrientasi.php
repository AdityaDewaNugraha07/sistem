<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_hasil_orientasi".
 *
 * @property integer $hasil_orientasi_id
 * @property string $kode
 * @property string $tanggal
 * @property string $nama_iuphhk
 * @property string $lokasi_muat
 * @property double $rkt_pertahun
 * @property string $kondisi_logpond
 * @property string $sistem_pemuatan
 * @property string $lama_pemuatan
 * @property string $jenis_alat_berat
 * @property string $kondisi_alat_berat
 * @property string $lokasi_produksi
 * @property string $perjanjian_scaling
 * @property string $kualitas_kayu
 * @property string $rendemen_produksi
 * @property double $rendemen_bl
 * @property string $kondisi_perusahaan
 * @property string $rekomendasi_grader
 * @property string $alasan_pertimbangan
 * @property string $grader_terlibat
 * @property string $status
 * @property string $reject_reason
 * @property integer $cancel_transaksi_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $approve_reason
 * @property string $nama_ipk
 * @property string $selisih_ukur
 * @property string $target_rkt
 * @property string $target_rkt_sebelumnya
 * @property string $tahun_target_rkt1
 * @property string $target_rkt1
 * @property string $tahun_target_rkt2
 * @property string $target_rkt2
 * @property string $jumlah_sampling_log
 * @property string $perlakuan_log_tidak_standard
 * @property string $informasi_pembeli_sebelumnya 
 *
 * @property TCancelTransaksi $cancelTransaksi
 */
class THasilOrientasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $sp_langsung,$sp_langsung_feet, $sp_estafet, $sp_estafet_kendaraan, $sp_estafet_feet, $sp_estafet_induk_feet;
	public $lp_langsung, $lp_langsung_hari,$lp_estafet, $lp_estafet_m3, $lp_estafet_hari;
	public $jab_traktor, $jab_logging, $jab_loader, $jab_lainnya;
	public $lpr_blok2tpn, $lpr_blok2tpn_kondisi, $lpr_tpn2tpk, $lpr_tpn2tpk_kondisi;
	public $rp_sawnmill,$rp_plymill,$rp_face,$rp_back,$rp_core;
	public $by_kanit_name,$by_kadiv_name,$by_gmopr_name,$by_gmpurch_name,$by_dirut_name,$gt_dkg_id,$gt_dkg_kode,$graderlog_id,$gt_tipe_dinas,$gt_nama_grader,$gt_wilayah_dinas;
    public $tahun_target_rkt1, $target_rkt1, $realisasi_rkt1, $tahun_target_rkt2, $target_rkt2, $realisasi_rkt2;
    public static function tableName()
    {
        return 't_hasil_orientasi';
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
            [['kode', 'tanggal', 'nama_iuphhk', 'lokasi_muat', 'rkt_pertahun', 'kondisi_logpond', 'sistem_pemuatan', 'lama_pemuatan', 'jenis_alat_berat', 'lokasi_produksi', 'rendemen_produksi', 'grader_terlibat'
                , 'created_at', 'created_by', 'updated_at', 'updated_by', 'by_kanit', 'by_kadiv', 'by_gmopr', 'by_gmpurch', 'by_dirut', 'target_rkt', 'target_rkt_sebelumnya'
                , 'jumlah_sampling_log', 'perlakuan_log_tidak_standard'], 'required'],
            [['tanggal', 'created_at', 'updated_at', 'rkt_pertahun'], 'safe'],
            [['rendemen_bl','perjanjian_scaling_trimming'], 'number'],
            [['sistem_pemuatan', 'lama_pemuatan', 'jenis_alat_berat', 'lokasi_produksi', 'rendemen_produksi', 'alasan_pertimbangan', 'grader_terlibat', 'target_rkt', 'target_rkt_sebelumnya'
                , 'perlakuan_log_tidak_standard_lain'], 'string'],
            [['cancel_transaksi_id', 'created_by', 'updated_by', 'by_kanit', 'by_kadiv', 'by_gmopr', 'by_gmpurch', 'by_dirut'], 'integer'],
            [['kode', 'status'], 'string', 'max' => 25],
            [['reject_reason','approve_reason','informasi_pembeli_sebelumnya'], 'string'],
            [['nama_iuphhk', 'lokasi_muat', 'kondisi_logpond', 'kondisi_alat_berat', 'perjanjian_scaling', 'kualitas_kayu', 'kondisi_perusahaan', 'rekomendasi_grader','nama_ipk','selisih_ukur'], 'string', 'max' => 200],
            [['cancel_transaksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TCancelTransaksi::className(), 'targetAttribute' => ['cancel_transaksi_id' => 'cancel_transaksi_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                'hasil_orientasi_id' => 'Hasil Orientasi',
                'kode' => 'Kode',
                'tanggal' => 'Tanggal',
                'nama_iuphhk' => 'Nama HPH',
                'lokasi_muat' => 'Lokasi Muat',
                'rkt_pertahun' => 'Rkt Pertahun',
                'kondisi_logpond' => 'Kondisi Logpond',
                'sistem_pemuatan' => 'Sistem Pemuatan',
                'lama_pemuatan' => 'Lama Pemuatan',
                'jenis_alat_berat' => 'Jenis Alat Berat',
                'kondisi_alat_berat' => 'Kondisi Alat Berat',
                'lokasi_produksi' => 'Lokasi Produksi',
                'perjanjian_scaling' => 'Perjanjian Scaling',
                'perjanjian_scaling_trimming' => 'Trimming',
                'kualitas_kayu' => 'Kualitas Kayu',
                'rendemen_produksi' => 'Rendemen Produksi',
                'rendemen_bl' => 'Rendemen Bl',
                'kondisi_perusahaan' => 'Kondisi Supplier',
                'rekomendasi_grader' => 'Rekomendasi Grader',
                'alasan_pertimbangan' => 'Alasan Pertimbangan',
                'grader_terlibat' => 'Grader Terlibat',
                'status' => 'Status',
                'cancel_transaksi_id' => 'Cancel Transaksi',
                'created_at' => 'Create Time',
                'created_by' => 'Created By',
                'updated_at' => 'Last Update Time',
                'updated_by' => 'Last Updated By',
				'by_kanit_name' => 'Prepared By Kanit Log Purch', 
				'by_kadiv_name' => 'Reviewed By Kadiv Mkt', 
				'by_gmopr_name' => 'Reviewed By GM Opr', 
				'by_gmpurch_name' => 'Reviewed By GM Purch', 
				'by_dirut_name' => 'Approved By Direktur Utama',
                'nama_ipk' => 'Nama IPK',
                'selisih_ukur' => 'Selisih Ukur',
                'target_rkt' => 'Target RKT',
                'target_rkt_sebelumnya' => 'Target RKT Sebelumnya',
                'tahun_target_rkt1' => 'Tahun',
                'target_rkt1' => '',
                'tahun_target_rkt2' => 'Tahun',
                'target_rkt2' => '',
                'jumlah_sampling_log' => 'Jumlah Sampling Log',
                'perlakuan_log_tidak_standard' => 'Perlakuan Log Tidak Standard',
                'perlakuan_log_tidak_standard_lain' => 'Lain-lain',
                'informasi_pembeli_sebelumnya' => 'Informasi pembeli sebelumnya & bulan, tahun kirim',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelTransaksi()
    {
        return $this->hasOne(TCancelTransaksi::className(), ['cancel_transaksi_id' => 'cancel_transaksi_id']);
    }
	
	public static function getOptionListPO(){
		$ret = [];
		$res = self::find()->orderBy('created_at DESC')->all();
		if (!empty($res)) {
			foreach($res as $i => $asd) {
//				$cekapproval = TApproval::find()->where("reff_no = '{$asd->kode}'")->all();
//				$approved = true;
//				foreach($cekapproval as $i => $apprv){
//					if($apprv->status == TApproval::STATUS_APPROVED){
//						$approved &= true;
//					}else{
//						$approved = false;
//					}
//				}
                // Keputusan terakhir pada Dirut
                $approved = false;
                $cekapproval = TApproval::find()->where("reff_no = '{$asd->kode}' AND assigned_to = ".\app\components\Params::DEFAULT_PEGAWAI_ID_AGUS_SOEWITO)->one();
                if(!empty($cekapproval)){
                    $approved = ($cekapproval->status == TApproval::STATUS_APPROVED)?true:false;
                }
				if($approved==true){
					$ret[$asd->hasil_orientasi_id] = $asd->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($asd->tanggal);
				}
			}
		}
        return $ret;
	}
}
