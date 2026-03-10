<?php
/* @var $this yii\web\View */

use app\components\DeltaFormatter;
use app\models\TTerimaLogalam;
use app\models\TTerimaLogalamDetail;

$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] == "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - Rev_' . time() . '.xls"');
    header('Cache-Control: max-age=0');
    $header = $paramprint['judul']." ".$paramprint['judul2'];
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
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'No'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Kode'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Tanggal'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'No. Truk'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'No. Dokumen'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'PIC UKUR'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Peruntukan'); ?></th>
                                            <th class='td-kecil' colspan='5'><?= Yii::t('app', 'Nomor'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Panjang<br>(cm)'); ?></th>
                                            <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Kode<br>Potong'); ?></th>
                                            <th class='td-kecil' colspan='5'><?= Yii::t('app', 'Diameter (cm)'); ?></th>
                                            <th class='td-kecil' colspan='3'><?= Yii::t('app', 'Cacat (cm)'); ?></th>
                                            <th class='td-kecil' rowspan="2"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>)'); ?></th>
                                            <th class='td-kecil' rowspan="2"><?= Yii::t('app', 'Status FSC'); ?></th>
                                        </tr>
                                        <tr>
                                            <th class='td-kecil'><?= Yii::t('app', 'QRCode'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Lapangan'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Grade'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Batang'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Produksi'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Ujung 1'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Ujung 2'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Pangkal 1'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Pangkal 2'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Rata<sup>2</sup>'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Panjang'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Gubal'); ?></th>
                                            <th class='td-kecil'><?= Yii::t('app', 'Growong'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $tgl_awal   = DeltaFormatter::formatDateTimeForDb($model->tgl_awal);
                                        $tgl_akhir  = DeltaFormatter::formatDateTimeForDb($model->tgl_akhir);
                                        $sql    = " SELECT 
                                                    * 
                                                    FROM t_terima_logalam
                                                        INNER JOIN t_terima_logalam_detail ON t_terima_logalam_detail.terima_logalam_id = t_terima_logalam.terima_logalam_id
                                                        INNER JOIN m_pegawai ON m_pegawai.pegawai_id = t_terima_logalam.pic_ukur
                                                        INNER JOIN m_kayu ON m_kayu.kayu_id = t_terima_logalam_detail.kayu_id 
                                                    WHERE
                                                        (t_terima_logalam.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir')";
                                        if(!empty($model->peruntukan)) {
                                            $sql .= " AND t_terima_logalam.peruntukan = '$model->peruntukan'";
                                        }
                                        if(!empty($model->no_dokumen)) {
                                            $sql .= " AND t_terima_logalam.no_dokumen ilike '%".$model->no_dokumen."%'";
                                        }
                                        if(!empty($modDetail->fsc)) {
                                            $sql .= " AND t_terima_logalam_detail.fsc = ".$modDetail->fsc;
                                        }
                                        if(!empty($model->lokasi_tujuan)) {
                                            $sql .= " AND t_terima_logalam.lokasi_tujuan ilike '%".$model->lokasi_tujuan."%'";
                                        }
                                        $query = Yii::$app->db->createCommand($sql);
                                        $no = 0;
                                        foreach($query->queryAll() as $data):
                                        ?>
                                        <tr>
                                            <td style="text-align: center;"><?= ++$no ?></td>
                                            <td style="text-align: center;"><?= $data['kode'] ?></td>
                                            <td style="text-align: center;"><?= DeltaFormatter::formatDateTimeForUser2($data['tanggal']) ?></td>
                                            <td style="text-align: center;"><?= $data['no_truk'] ?></td>
                                            <td style="text-align: center;"><?= $data['no_dokumen'] ?></td>
                                            <td style="text-align: center;"><?= $data['pegawai_nama'] ?></td>
                                            <td style="text-align: center;"><?= $data['kayu_nama'] ?></td>
                                            <td style="text-align: center;"><?= $data['peruntukan'] ?></td>
                                            <td style="text-align: center;"><?= $data['no_barcode'] ?></td>
                                            <td style="text-align: center;"><?= $data['no_lap'] ?></td>
                                            <td style="text-align: center;"><?= $data['no_grade'] ?></td>
                                            <td style="text-align: center;"><?= $data['no_btg'] ?></td>
                                            <td style="text-align: center;"><?= $data['no_produksi'] ?></td>
                                            <td style="text-align: right;"><?= $data['panjang'] ?></td>
                                            <td style="text-align: center;"><?= $data['kode_potong'] ?></td>
                                            <td style="text-align: center;"><?= $data['diameter_ujung1'] ?></td>
                                            <td style="text-align: center;"><?= $data['diameter_ujung2'] ?></td>
                                            <td style="text-align: center;"><?= $data['diameter_pangkal1'] ?></td>
                                            <td style="text-align: center;"><?= $data['diameter_pangkal2'] ?></td>
                                            <td style="text-align: center;"><?= $data['diameter_rata'] ?></td>
                                            <td style="text-align: center;"><?= $data['cacat_panjang'] ?></td>
                                            <td style="text-align: center;"><?= $data['cacat_gb'] ?></td>
                                            <td style="text-align: center;"><?= $data['cacat_gr'] ?></td>
                                            <td style="text-align: right;"><?= $data['volume'] ?></td>
                                            <td style="text-align: center;"><?= ($data['fsc'])?'FSC 100%':'Non FSC' ?></td>
                                        </tr>
                                        <?php endforeach ?>
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