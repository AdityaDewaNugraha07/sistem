<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
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
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
										<tr>
                                            <th><?= Yii::t('app', 'Kode SPK'); ?></th>
                                            <th><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                            <th><?= Yii::t('app', 'Size'); ?></th>
                                            <th><?= Yii::t('app', 'Panjang'); ?></th>
                                            <th><?= Yii::t('app', 'Qty'); ?></th>
                                            <th><?= Yii::t('app', 'Volume (m<sup>3</sup>)'); ?></th>
                                        </tr>
									</thead>
									<tbody>
                                        <?php
                                        $sql = $model->searchLaporanMonitoring()->createCommand()->rawSql;
                                        $contents = Yii::$app->db->createCommand($sql)->queryAll();

                                        if (count($contents) > 0) {
                                            $group_kode_kayu = [];
                                            foreach ($contents as $row) {
                                                $key1 = $row['kode_spk'] . '_' . $row['kayu_nama'];
                                                $group_kode_kayu[$key1][] = $row;
                                            }

                                            foreach ($group_kode_kayu as $key1 => $rows_kode_kayu) {
                                                $rowspan_kode_kayu = count($rows_kode_kayu);
                                                
                                                $group_size = [];
                                                foreach ($rows_kode_kayu as $row) {
                                                    $size = $row['produk_t'] . 'x' . $row['produk_l'];
                                                    $group_size[$size][] = $row;
                                                }

                                                $first_kode_kayu = true;
                                                foreach ($group_size as $size => $rows_size) {
                                                    $rowspan_size = count($rows_size);
                                                    $first_size = true;

                                                    foreach ($rows_size as $data) {
                                                        $vol = $data['produk_t'] * $data['produk_l'] * $data['produk_p'] * $data['qty'] / 1000000;
                                                        ?>
                                                        <tr>
                                                            <?php if ($first_kode_kayu){ ?>
                                                                <td rowspan="<?= $rowspan_kode_kayu ?>" style="text-align:center;">
                                                                    <?= $data['kode_spk'] ?>
                                                                </td>
                                                                <td rowspan="<?= $rowspan_kode_kayu ?>" style="text-align:center;">
                                                                    <?= $data['kayu_nama'] ?>
                                                                </td>
                                                            <?php } ?>
                                                            <?php if ($first_size){ ?>
                                                                <td rowspan="<?= $rowspan_size ?>" style="text-align:center;">
                                                                    <?php
                                                                    if($data['produk_t']){
                                                                        echo $data['produk_t'] .'x'.$data['produk_l'];
                                                                    } else {
                                                                        echo '-';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            <?php } ?>
                                                            <td style="text-align:center;"><?= $data['produk_p']?$data['produk_p']:'-'; ?></td>
                                                            <td style="text-align:center;"><?= $data['qty']?$data['qty']:0; ?></td>
                                                            <td style="text-align:right;">
                                                                <?= \app\components\DeltaFormatter::formatNumberForAllUser($vol, 4) ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $first_kode_kayu = false;
                                                        $first_size = false;
                                                    }
                                                }
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' style='text-align:center;'>Data tidak ditemukan</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>