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
                                            <th><?= Yii::t('app', 'Kode SPK'); ?></th>
                                            <th><?= Yii::t('app', 'Input Brakedown'); ?></th>
                                            <th><?= Yii::t('app', 'Output Bandsaw'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT t_spk_sawmill.kode, SUM(bd.vol_brakedown) AS vol_brakedown, SUM(bs.vol_bandsaw) AS vol_bandsaw
                                                FROM t_spk_sawmill
                                                JOIN (SELECT t_spk_sawmill.kode, SUM(t_brakedown_detail.volume_baru) AS vol_brakedown FROM t_spk_sawmill
                                                        JOIN t_brakedown ON t_brakedown.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id
                                                        JOIN t_brakedown_detail ON t_brakedown_detail.brakedown_id = t_brakedown.brakedown_id
                                                        WHERE t_brakedown.tanggal BETWEEN '$model->tgl_awal' AND '$model->tgl_akhir'
                                                        GROUP BY t_spk_sawmill.kode) bd ON bd.kode = t_spk_sawmill.kode
                                                JOIN (SELECT t_spk_sawmill.kode, SUM(total_volume_m3) AS vol_bandsaw FROM t_spk_sawmill
                                                        JOIN view_vol_output_bandsaw ON view_vol_output_bandsaw.spk_sawmill_id = t_spk_sawmill.spk_sawmill_id
                                                        WHERE view_vol_output_bandsaw.tanggal BETWEEN '$model->tgl_awal' AND '$model->tgl_akhir'
                                                        GROUP BY t_spk_sawmill.kode) bs ON bs.kode = t_spk_sawmill.kode";
                                        $where = '';
                                        if(!empty($model->kode)){
                                            if (is_array($model->kode)) {
                                                if (isset($model->kode)) {
                                                    $subq=null;
                                                    $cn=1;
                                                    $subq.='(';
                                                    foreach ($model->kode as $k) {
                                                        $subq.="AND t_spk_sawmill.kode = '".$k."' ";
                                                        if ($cn < count($model->kode)) {
                                                            $subq.=' OR ';
                                                        }
                                                        $cn++;
                                                    }
                                                    $subq.=')';
                                                    if (!empty($subq)) {
                                                        $where = $subq;
                                                    }
                                                }
                                            }else{
                                                $where = "AND t_spk_sawmill.kode = '".$model->kode."'";
                                            }            
                                        }
                                        if(!empty($where)){
                                            $sql .= " WHERE t_spk_sawmill.cancel_transaksi_id is null $where";
                                        }
                                        $sql .= " GROUP BY t_spk_sawmill.kode ORDER BY t_spk_sawmill.kode DESC";
                                        $datas = Yii::$app->db->createCommand($sql)->queryAll();
                                        if(count($datas) > 0){
                                            foreach ($datas as $data) { ?>
                                                <tr>
                                                    <td class="td-kecil text-align-center"><?= $data['kode']; ?></td>
                                                    <td class="td-kecil text-align-center"><?= $data['vol_brakedown']; ?></td>
                                                    <td class="td-kecil text-align-center"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['vol_bandsaw'], 2); ?></td>
                                                </tr>
                                            <?php }
                                        } else {
                                            echo "<tr>
                                                    <td colspan='3' class='td-kecil text-align-center'>Data tidak ditemukan</td>
                                                  </tr>";
                                        }?>
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