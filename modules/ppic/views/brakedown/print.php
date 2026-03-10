<?php
/* @var $this yii\web\View */

$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<style>
table{
	font-size: 1.2rem;
}
table#table-detail{
	font-size: 1.2rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<table style="width: 20cm; margin: 10px;">
    <tr>
        <td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: middle; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
					</td>
					<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;">
						<table>
							<tr>
								<td><b>Kode</b></td>
								<td>: &nbsp; <?= $model->kode; ?></td>
							</tr>
							<tr>
								<td><b>Tanggal</b></td>
								<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
							</tr>
                        </table>
					</td>
				</tr>
			</table>
		</td>
    </tr>
    <tr>
		<td colspan="3" style="padding: 8px;">
			<table style="width: 100%">
				<tr>
					<td style="width: 60%; vertical-align: top; padding-left: 10px;">	
						<table>
							<tr>
								<td style="width: 3cm; vertical-align: top;"><b>Kode SPK</b></td>
								<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
								<td style="width: 6cm; vertical-align: top;"><?php $modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id); echo $modSpk->kode; ?></td>
							</tr>
                            <tr>
								<td style="vertical-align: top;"><b>Jenis Kayu</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?php $modKayu = \app\models\MKayu::findOne($model->kayu_id); echo $modKayu->kayu_nama; ?></td>
							</tr>
                            <tr>
								<td style="vertical-align: top;"><b>Line Sawmill</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?= $model->line_sawmill; ?></td>
							</tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
						</table>
					</td>
				</tr>
                <tr>
                    <td colspan="3" style="padding: 0px;">
                        <h5>Detail :</h5>
                        <table style="width: 100%" id="table-detail">
                            <thead>
                                <tr style="height: 0.5cm; border-bottom: solid 1px #000; border-top: solid 1px #000;">
                                    <th rowspan="2" style="padding: 5px 5px; border-right: solid 1px #000; border-left: solid 1px #000; text-align: center;">No.</th>
                                    <th rowspan="2" style="border-right: solid 1px #000; text-align: center">No. Lap</th>
                                    <th rowspan="2" style="border-right: solid 1px #000; text-align: center">No. Barcode</th>
                                    <th rowspan="2" style="border-right: solid 1px #000; text-align: center">Grade</th>
                                    <th rowspan="2" style="border-right: solid 1px #000; text-align: center">Panjang<br>(m)</th>
                                    <th colspan="4" style="border-right: solid 1px #000; text-align: center">Ukuran Diameter (cm)</th>
                                    <th colspan="3" style="border-right: solid 1px #000; text-align: center">Ukuran Cacat (cm)</th>
                                    <th rowspan="2" style="border-right: solid 1px #000; text-align: center;">Vol<br>(m<sup>3</sup>)</th>
                                </tr>
                                <tr style="border-bottom: solid 1px #000;">
                                    <th style="border-right: solid 1px #000; text-align: center">U1</th>
                                    <th style="border-right: solid 1px #000; text-align: center">U2</th>
                                    <th style="border-right: solid 1px #000; text-align: center">P1</th>
                                    <th style="border-right: solid 1px #000; text-align: center">P2</th>
                                    <th style="border-right: solid 1px #000; text-align: center">P</th>
                                    <th style="border-right: solid 1px #000; text-align: center">Gb</th>
                                    <th style="border-right: solid 1px #000; text-align: center">Gr</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($modDetail as $i => $detail){ ?>
                                    <tr>
                                        <td class="text-align-center" style="border: solid 1px #000;"><?= $i+1; ?></td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->no_lap_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->no_barcode_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->grading_rule?$detail->grading_rule:'-'; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->panjang_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->diameter_ujung1_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->diameter_ujung2_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->diameter_pangkal1_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->diameter_pangkal2_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->cacat_pjg_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->cacat_gb_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->cacat_gr_baru; ?>
										</td>
                                        <td class="text-align-center" style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
											<?= $detail->volume_baru; ?>
										</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
</table>