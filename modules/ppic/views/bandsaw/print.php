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

$modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id); 
$modKayu = \app\models\MKayu::findOne($modSpk->kayu_id);

// untuk rowspan
$datas = [];
foreach ($modDetail as $detail) {
    $no   = $detail->nomor_bandsaw;
    $size = $detail->produk_t . 'x' . $detail->produk_l;
    if (!isset($datas[$no])) {
        $datas[$no] = [];
    }
    if (!isset($datas[$no][$size])) {
        $datas[$no][$size] = [];
    }
    $datas[$no][$size][] = $detail;
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
                        <br><b><?= $model->kode; ?></b>
					</td>
					<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding: 10px;">&nbsp;</td>
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
                                <td style="vertical-align: top;"><b>Tanggal</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
                            </tr>
                            <tr>
								<td style="width: 3cm; vertical-align: top;"><b>Kode SPK</b></td>
								<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
								<td style="width: 6cm; vertical-align: top;"><?php $modSpk = \app\models\TSpkSawmill::findOne($model->spk_sawmill_id); echo $modSpk->kode; ?></td>
							</tr>
                        </table>
                    </td>
					<td style="width: 40%; vertical-align: top; padding-left: 10px;">	
						<table>
                            <tr>
								<td style="vertical-align: top;"><b>Jenis Kayu</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo $modKayu->kayu_nama; ?></td>
							</tr>
                            <tr>
								<td style="width: 3cm; vertical-align: top;"><b>Line Sawmill</b></td>
								<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
								<td style="width: 6cm; vertical-align: top;"><?= $model->line_sawmill; ?></td>
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
                                    <th style="padding: 5px 5px; border-right: solid 1px #000; border-left: solid 1px #000; text-align: center; width: 50px;">No.</th>
                                    <th style="border-right: solid 1px #000; text-align: center; width: 170px;">No. Bandsaw</th>
                                    <th style="border-right: solid 1px #000; text-align: center">Size</th>
                                    <th style="border-right: solid 1px #000; text-align: center">Panjang<br>(m)</th>
                                    <th style="border-right: solid 1px #000; text-align: center">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                ksort($datas, SORT_NUMERIC);
                                foreach ($datas as $dt => $sizes) {
                                    $countrowspan = 0;
                                    foreach ($sizes as $size => $details) {
                                        $countrowspan += count($details);
                                    }

                                    $printedBandsaw = false;
                                    foreach ($sizes as $size => $details) {
                                        $rowspanSize = count($details);
                                        $printedSize = false;

                                        foreach ($details as $detail) { ?>
                                    <tr>

                                        <?php if (!$printedBandsaw) { ?>
                                            <td rowspan="<?= $countrowspan; ?>" style="border:1px solid #000; text-align:center;">
                                                <?= $no++; ?>
                                            </td>
                                            <td rowspan="<?= $countrowspan; ?>" style="border:1px solid #000; text-align:center;">
                                                <?= $dt; ?>
                                            </td>
                                        <?php $printedBandsaw = true; 
                                        } ?>

                                        <?php if (!$printedSize) { ?>
                                            <td rowspan="<?= $rowspanSize; ?>" style="border:1px solid #000; text-align:center;">
                                                <?= $size; ?>
                                            </td>
                                        <?php $printedSize = true; 
                                        } ?>

                                        <td style="border:1px solid #000; text-align:center;">
                                            <?= $detail->produk_p; ?>
                                        </td>

                                        <td style="border:1px solid #000; text-align:center;">
                                            <?= $detail->qty; ?>
                                        </td>
                                    </tr>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
</table>