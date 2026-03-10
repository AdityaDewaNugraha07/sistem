<?php
/* @var $this yii\web\View */
$this->title = 'Print ' . $paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan', ['paramprint' => $paramprint]);
if ($_GET['caraprint'] == "EXCEL") {
    if($model->tgl_awal == $model->tgl_akhir){
        $tanggal = $model->tgl_awal;
    } else {
        $tanggal = $model->tgl_awal .' sd '. $model->tgl_akhir;
    }
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' ' . $tanggal . '.xls"');
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
                                            <th><?= Yii::t('app', 'Alokasi'); ?></th>
                                            <th><?= Yii::t('app', 'Grade'); ?></th>
                                            <th><?= Yii::t('app', 'Kayu'); ?></th>
                                            <th><?= Yii::t('app', 'Panjang (m)'); ?></th>
                                            <th><?= Yii::t('app', 'Volume (m<sup>3</sup>)'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT alokasi, grading_rule, m_kayu.kayu_nama, panjang_baru, SUM(volume_baru) AS vol
                                                FROM t_pemotongan_log_detail_potong
                                                JOIN t_pemotongan_log_detail ON t_pemotongan_log_detail.pemotongan_log_detail_id = t_pemotongan_log_detail_potong.pemotongan_log_detail_id
                                                JOIN t_pemotongan_log ON t_pemotongan_log.pemotongan_log_id = t_pemotongan_log_detail.pemotongan_log_id
                                                JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id";
                                        $where = [];
                                        if (!empty($model->tgl_awal) && !empty($model->tgl_akhir)) {
                                            $where[] = "t_pemotongan_log.tanggal between '".$model->tgl_awal."' and '".$model->tgl_akhir."'";
                                        }
                                        if (!empty($model->kayu_id)) {
                                            $kayu_id = $model->kayu_id;
                                            $where[] = "t_pemotongan_log_detail.kayu_id = ".$kayu_id;
                                        }
                                        if (!empty($model->alokasi)) {
                                             $alokasi = $model->alokasi;
                                            $where[] = "alokasi = '".$alokasi."'";
                                        }
                                        if (!empty($model->grading_rule)) {
                                            $grading_rule = $model->grading_rule;
                                            $where[] = "grading_rule = '".$grading_rule."'";
                                        }
                                        if (!empty($model->panjang_baru)) {
                                            $panjang = $model->panjang_baru;
                                            $where[] = "panjang_baru = ".$panjang;
                                        }
                                        if (count($where) > 0) {
                                            $sql .= " WHERE " . implode(' AND ', $where);
                                        }
                                        $sql .= " GROUP BY alokasi, grading_rule, m_kayu.kayu_nama, panjang_baru ORDER BY alokasi, grading_rule, kayu_nama, panjang_baru";
                                        $datas = Yii::$app->db->createCommand($sql)->queryAll();
                                        $total = 0;
                                        if(count($datas) > 0){
                                            foreach ($datas as $data) { 
                                                $total += $data['vol'];
                                                ?>
                                                <tr>
                                                    <td class="td-kecil"><?= $data['alokasi']; ?></td>
                                                    <td class="td-kecil text-align-center"><?= $data['grading_rule']?$data['grading_rule']:'-'; ?></td>
                                                    <td class="td-kecil"><?= $data['kayu_nama']; ?></td>
                                                    <td class="td-kecil text-align-right"><?= $data['panjang_baru']; ?></td>
                                                    <td class="td-kecil text-align-right"><?= $data['vol']; ?></td>
                                                </tr>
                                            <?php }
                                        } else {
                                            echo "<tr>
                                                    <td colspan='5' class='td-kecil text-align-center'>Data tidak ditemukan</td>
                                                  </tr>";
                                        }?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="td-kecil text-align-right"><b>Total</b></td>
                                            <td class="td-kecil text-align-right"><b><?= app\components\DeltaFormatter::formatNumberForUserFloat($total, 2); ?></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>