<?php
/* @var $this yii\web\View */
$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
use yii\helpers\Url;

$tgl_awal   = app\components\DeltaFormatter::formatDateTimeForDb($model->tgl_awal);
$tgl_akhir  = app\components\DeltaFormatter::formatDateTimeForDb($model->tgl_akhir);
$sql        = " SELECT t_pemotongan_log.pemotongan_log_id, kode, peruntukan, nomor, tanggal, m_pegawai.pegawai_nama, kayu_nama, t_pemotongan_log_detail.no_barcode, panjang, diameter,
                volume, jumlah_potong, no_barcode_baru, panjang_baru, diameter_ujung1_baru, diameter_ujung2_baru, diameter_pangkal1_baru, diameter_pangkal2_baru,
                cacat_pjg_baru, cacat_gb_baru, cacat_gr_baru, volume_baru, alokasi, grading_rule, reduksi, reduksi_baru, t_pemotongan_log_detail_potong.no_lap_baru, 
                h_persediaan_log.no_lap, t_pemotongan_log_detail_potong.pemotongan_log_detail_potong_id,h_persediaan_log.no_produksi,h_persediaan_log.no_btg,h_persediaan_log.no_grade
                FROM t_pemotongan_log
                LEFT JOIN t_pemotongan_log_detail ON t_pemotongan_log.pemotongan_log_id = t_pemotongan_log_detail.pemotongan_log_id
                LEFT JOIN t_pemotongan_log_detail_potong ON t_pemotongan_log_detail.pemotongan_log_detail_id = t_pemotongan_log_detail_potong.pemotongan_log_detail_id
                LEFT JOIN m_pegawai ON m_pegawai.pegawai_id = t_pemotongan_log.petugas
                LEFT JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id 
                JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama AND h_persediaan_log.reff_no = t_pemotongan_log.nomor
                WHERE alokasi='Gudang' AND tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'";
if(!empty($model->peruntukan)) {
    $sql .= " AND peruntukan = '$model->peruntukan'";
}
if(!empty($model->kayu_id)) {
    $sql .= " AND t_pemotongan_log_detail.kayu_id = '$model->kayu_id'";
}
if(!empty($model->no_lap)) {
    $sql .= " AND no_lap_baru ilike '%$model->no_lap%'";
}
if(!empty($model->panjang)) {
    $sql .= " AND t_pemotongan_log_detail_potong.panjang_baru = '$model->panjang'";
}
$sql .= "ORDER BY t_pemotongan_log.peruntukan DESC, t_pemotongan_log_detail.no_barcode ASC, t_pemotongan_log_detail_potong.no_barcode_baru ASC";
$query = Yii::$app->db->createCommand($sql);
if(count($query->queryAll())>0){
    foreach($query->queryAll() as $data):
        $qrCodeContent = "ID : " . $data['pemotongan_log_detail_potong_id'] .
            "\u000ANo : " . $data['no_barcode_baru'] .
            "";
?>
<div style="width: 77mm;height: 68mm; border: .5mm solid black;margin: 3mm 1mm; padding: 1mm;">
    <div style="display: flex;justify-content: space-between;">
        <div style="width: 8mm; height: 33m;border: .5mm dotted black; border-radius: 2px;position: relative;">
            <div style="font-size: 10px; position: absolute;width: 30mm;transform: rotate(90deg);top: 13mm;left: -11mm;text-align: center;">
                Area Staples
            </div>
        </div>
        <div style="height: 33mm">
            <div class="place-qrcode" style="margin-top: 4mm;margin-right: 1mm"></div>
        </div>
        <div style="height: 33mm;">
            <div class="place-qrcodek" style="margin-top: 2mm;margin-left: 1mm"></div>
        </div>
        <div style="width: 8mm; height: 33mm;border: .5mm dotted black; border-radius: 2px;position: relative;">
            <div style="font-size: 10px; position: absolute;width: 30mm;transform: rotate(-90deg);top: 13mm;left: -12mm;text-align: center;">
                Area Staples
            </div>
        </div>
    </div>
    <div style="display: flex; width: 100%;height: 12mm; text-align: center;align-items: center;margin-top: 2mm;margin-bottom: 2mm;">
        <div style="width: 16mm;">
            <img src="<?= Url::base() . '/themes/metronic/cis/img/logo-ciptana.png'?>" style="width: 10mm;">
        </div>
        <div style="width: 56mm; font-size: 5mm;font-weight: 600;">
            <?= $data['no_barcode_baru'];?>
        </div>
    </div>
    <div style="display: flex; width: 100%;border: .5mm solid black;height: 8mm;font-weight: 600; border-bottom: none;">
        <div style="border-right: .5mm solid black; display: flex; align-items: center;padding: 0 1mm;width: 100%;justify-content: center; position: relative">
            <label style="position: absolute;top: 0;left: 0;font-size: 8px;border: 1px solid black;border-top: none;padding: 0 3px;border-left: none; width: 44px;">Produksi</label>
            <span style="margin-top: 10px; margin-left: 30px;"><?= $data['no_produksi'];?></span>
        </div>
        <div style="display: flex; align-items: center;padding: 0 1mm;width: 100%;justify-content: center; position: relative;">
            <label style="position: absolute;top: 0;left: 0;font-size: 8px;border: 1px solid black;border-top: none;padding: 0 3px;border-left: none; width: 44px;">Batang</label>
            <span style="margin-top: 10px; margin-left: 30px;"><?= $data['no_btg'];?></span>
        </div>
    </div>
    <div style="display: flex; width: 100%;border: .5mm solid black;height: 8mm;font-weight: 600;">
        <div style="border-right: .5mm solid black; display: flex; align-items: center;padding: 0 1mm;width: 100%;justify-content: center; position: relative;">
            <label style="position: absolute;top: 0;left: 0;font-size: 8px;border: 1px solid black;border-top: none;padding: 0 3px;border-left: none; width: 44px;">Grade</label>
            <span style="margin-top: 10px; margin-left: 30px;"><?= $data['no_grade'];?></span>
        </div>
        <div style="display: flex; align-items: center;padding: 0 1mm;width: 100%;justify-content: center; position: relative;">
            <label style="position: absolute;top: 0;left: 0;font-size: 8px;border: 1px solid black;border-top: none;padding: 0 3px;border-left: none; width: 44px;">Lapangan</label>
            <span style="margin-top: 10px; margin-left: 30px;"><?= $data['no_lap_baru'];?></span>
        </div>
    </div>
</div>
<?php endforeach ?>
<?php } else {} ?>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery.qrcode.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    jQuery('.place-qrcode').qrcode({fill: '#666',width: 90,height: 90, text: '".$qrCodeContent."' });
    jQuery('.place-qrcodek').qrcode({fill: '#666',width: 110,height: 110, text: '".$qrCodeContent."' });
    ", yii\web\View::POS_READY); ?>
