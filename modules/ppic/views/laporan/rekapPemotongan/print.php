<?php
/* @var $this yii\web\View */
$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] == "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . '.xls"');
    header('Cache-Control: max-age=0');
    $header = $paramprint['judul']." ". $paramprint['judul2'];
}
?>
<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $header; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-rekap">
                                    <thead>
                                        <tr>
                                            <th class='td-kecil'  rowspan="3"><?= Yii::t('app', 'No.'); ?></th>
                                            <th class='td-kecil'  rowspan='3'><?= Yii::t('app', 'Kode'); ?></th>
                                            <th class='td-kecil'  rowspan='3'><?= Yii::t('app', 'Peruntukan'); ?></th>
                                            <th class='td-kecil'  rowspan='3'><?= Yii::t('app', 'Nomor'); ?></th>
                                            <th class='td-kecil'  rowspan='3'><?= Yii::t('app', 'Tanggal'); ?></th>
                                            <th class='td-kecil'  rowspan='3'><?= Yii::t('app', 'Petugas'); ?></th>
                                            <th class='td-kecil'  colspan='5'><?= Yii::t('app', 'Asal Kayu Semula'); ?></th>
                                            <th class='td-kecil'  colspan='14'><?= Yii::t('app', 'Dipotong Menjadi'); ?></th>
                                        </tr>
                                        <tr>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'Jenis<br>Kayu'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'QRCode<br>No. Lapangan'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'P (cm)'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', '&#8709; (cm)'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'V (m<sup>3</sup>)'); ?></th>
                                            <!-- <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'Reduksi'); ?></th> -->
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'Jml<br>Potong'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'QRCode Baru<br>No. Lapangan'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'P (cm)'); ?></th>
                                            <th class='td-kecil'  colspan="4"><?= Yii::t('app', 'Diameter (cm)'); ?></th>
                                            <th class='td-kecil'  colspan="3"><?= Yii::t('app', 'Cacat (cm)'); ?></th>
                                            <!-- <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'Reduksi'); ?></th> -->
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'V (m<sup>3</sup>)'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'Alokasi'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'Gading<br>Rule'); ?></th>
                                            <th class='td-kecil'  rowspan='2'><?= Yii::t('app', 'Cut'); ?></th>
                                        </tr>
                                        <tr>
                                            <th class='td-kecil' ><?= Yii::t('app', 'U1'); ?></th>
                                            <th class='td-kecil' ><?= Yii::t('app', 'U2'); ?></th>
                                            <th class='td-kecil' ><?= Yii::t('app', 'P1'); ?></th>
                                            <th class='td-kecil' ><?= Yii::t('app', 'P2'); ?></th>
                                            <th class='td-kecil' ><?= Yii::t('app', 'P'); ?></th>
                                            <th class='td-kecil' ><?= Yii::t('app', 'Gb'); ?></th>
                                            <th class='td-kecil' ><?= Yii::t('app', 'Gr'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $tgl_awal   = app\components\DeltaFormatter::formatDateTimeForDb($model->tgl_awal);
                                        $tgl_akhir  = app\components\DeltaFormatter::formatDateTimeForDb($model->tgl_akhir);
                                        $sql    = " SELECT t_pemotongan_log.pemotongan_log_id, kode, peruntukan, nomor, tanggal, m_pegawai.pegawai_nama, kayu_nama, t_pemotongan_log_detail.no_barcode, panjang, diameter,
                                                    volume, jumlah_potong, no_barcode_baru, panjang_baru, diameter_ujung1_baru, diameter_ujung2_baru, diameter_pangkal1_baru, diameter_pangkal2_baru,
                                                    cacat_pjg_baru, cacat_gb_baru, cacat_gr_baru, volume_baru, alokasi, grading_rule, reduksi, reduksi_baru, t_pemotongan_log_detail_potong.no_lap_baru, 
                                                    h_persediaan_log.no_lap, potong
                                                    FROM t_pemotongan_log
                                                    LEFT JOIN t_pemotongan_log_detail ON t_pemotongan_log.pemotongan_log_id = t_pemotongan_log_detail.pemotongan_log_id
                                                    LEFT JOIN t_pemotongan_log_detail_potong ON t_pemotongan_log_detail.pemotongan_log_detail_id = t_pemotongan_log_detail_potong.pemotongan_log_detail_id
                                                    LEFT JOIN m_pegawai ON m_pegawai.pegawai_id = t_pemotongan_log.petugas
                                                    LEFT JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id 
                                                    JOIN h_persediaan_log ON h_persediaan_log.no_barcode = t_pemotongan_log_detail_potong.no_barcode_lama AND h_persediaan_log.reff_no = t_pemotongan_log.nomor
                                                    WHERE
                                                        (tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir')";
                                        if(!empty($model->peruntukan)) {
                                            $sql .= " AND peruntukan = '$model->peruntukan'";
                                        }
                                        if(!empty($model->kayu_id)) {
                                            $sql .= " AND t_pemotongan_log_detail.kayu_id = '$model->kayu_id'";
                                        }
                                        if(!empty($model->alokasi)) {
                                            $sql .= " AND alokasi = '$model->alokasi'";
                                        }
                                        if(!empty($model->nomor)) {
                                            $sql .= " AND nomor ilike '%$model->nomor%'";
                                        }
                                        if(!empty($model->grading_rule)) {
                                            $sql .= " AND grading_rule = '$model->grading_rule'";
                                        }
                                        if(!empty($model->panjang)) {
                                            $sql .= " AND t_pemotongan_log_detail_potong.panjang_baru = '$model->panjang'";
                                        }
                                        $sql .= "ORDER BY kode DESC, t_pemotongan_log_detail.no_barcode ASC, t_pemotongan_log_detail_potong.no_barcode_baru ASC";
                                        $query = Yii::$app->db->createCommand($sql);
                                        $no = 1;
                                        if(count($query->queryAll())>0){
                                        foreach($query->queryAll() as $data): ?>
                                            <tr>
                                                <td class='td-kecil text-align-center'><?= $no++ ?></td>
                                                <td class='td-kecil'><?= $data['kode'] ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['peruntukan'] ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['nomor'] ?></td>
                                                <td class='td-kecil text-align-center'><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']) ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['pegawai_nama'] ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['kayu_nama'] ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['no_barcode'] ?><br><?= $data['no_lap']; ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['panjang'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['diameter'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['volume'] ?></td>
                                                <!-- <td class='td-kecil text-align-right'><?= $data['reduksi'] ?></td> -->
                                                <td class='td-kecil text-align-center'><?= $data['jumlah_potong'] ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['no_barcode_baru'] ?><br><?= $data['no_lap_baru']; ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['panjang_baru'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['diameter_ujung1_baru'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['diameter_ujung2_baru'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['diameter_pangkal1_baru'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['diameter_pangkal2_baru'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['cacat_pjg_baru'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['cacat_gb_baru'] ?></td>
                                                <td class='td-kecil text-align-right'><?= $data['cacat_gr_baru'] ?></td>
                                                <!-- <td class='td-kecil text-align-right'><?= $data['reduksi_baru'] ?></td> -->
                                                <td class='td-kecil text-align-right'><?= $data['volume_baru'] ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['alokasi'] ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['grading_rule']?$data['grading_rule']:'-' ?></td>
                                                <td class='td-kecil text-align-center'><?= $data['potong']?'<input type="checkbox" disabled checked/>':'<input type="checkbox" disabled/>' ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="24" class="td-kecil text-align-center">Data tidak ditemukan</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>