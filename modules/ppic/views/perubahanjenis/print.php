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
						
					</td>
				</tr>
			</table>
		</td>
    </tr>
    <tr>
        <td colspan="4" style="padding: 12px;">
            <table style="width: 100%; border-collapse: collapse;" border="0">
                <tr>
                    <td style="width: 15%; vertical-align: top;"><b>Kode</b></td>
                    <td style="width: 35%; text-align: left; vertical-align: top;">: <?= $model->kode; ?></td>
                    <td style="width: 15%; vertical-align: top;"><b>Peruntukan</b></td>
                    <td style="width: 35%; text-align: left; vertical-align: top;">: <?= $model->peruntukan; ?></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;"><b>Tanggal</b></td>
                    <td style="text-align: left; vertical-align: top;">: <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
                    <td style="vertical-align: top;"><b>Keterangan</b></td>
                    <td style="width: 35%; text-align: left; vertical-align: top;">: <?= $model->keterangan ? nl2br($model->keterangan) : '-'; ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
		<td colspan="3" style="padding: 8px;">
            <h4>Detail Log Yang Diubah</h4>
			<table style="width: 100%">
                <tr>
                    <td colspan="3" style="padding: 0px;">
                        <table style="width: 100%" id="table-detail">
                            <thead>
                                <tr style="height: 0.5cm; border-bottom: solid 1px #000; border-top: solid 1px #000;">
                                    <th style="padding: 5px 5px; border-right: solid 1px #000; border-left: solid 1px #000; text-align: center;">No.</th>
                                    <th style="border-right: solid 1px #000; text-align: center">No Barcode / No Lap</th>
                                    <th style="border-right: solid 1px #000; text-align: center">Jenis Kayu Lama</th>
                                    <th style="border-right: solid 1px #000; text-align: center">Jenis Kayu Baru</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
									$datadetails = \yii\helpers\Json::decode($model->datadetail, true);
									foreach($datadetails as $i => $detail){ 
                                        $modKayuOld = \app\models\MKayu::findOne($detail['kayu_id_old']);
                                        $modKayuNew = \app\models\MKayu::findOne($detail['kayu_id_new']);
                                        ?>
                                        <tr>
                                            <td style="border-right: solid 1px #000; border-left: solid 1px #000; border-bottom: solid 1px #000; padding-left: 10px;"><center><?= $i+1; ?></center></td>
                                            <td style="border-right: solid 1px #000; border-left: solid 1px #000; border-bottom: solid 1px #000; padding-left: 10px;"><?= $detail['no_barcode'] . ' / ' . $detail['no_lap']; ?></td>
                                            <td style="border-right: solid 1px #000; border-left: solid 1px #000; border-bottom: solid 1px #000; padding-left: 10px;"><?= $modKayuOld->kayu_nama; ?></td>
                                            <td style="border-right: solid 1px #000; border-left: solid 1px #000; border-bottom: solid 1px #000; padding-left: 10px;"><?= $modKayuNew->kayu_nama; ?></td>
                                        </tr>
                                <?php }
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="text-align: right; padding-top: 40px;">
            <table style="width: 100%;" border="0">
                <tr>
                    <td style="text-align: center; width: 40%;"></td>
                    <td style="text-align: center;">Diperiksa Oleh</td>
                    <td style="text-align: center;">Disetujui Oleh</td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td style="text-align: center; width: 40%;"></td>
                    <td style="text-align: center;">
                        <?php 
                        $modApprover1 = \app\models\MPegawai::findOne($model->approver1);
                        echo $modApprover1->pegawai_nama;
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <?php 
                        $modApprover2 = \app\models\MPegawai::findOne($model->approver2);
                        echo $modApprover2->pegawai_nama;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; width: 40%;"></td>
                    <td style="text-align: center; font-size: 1.1rem">(Kadep PPIC)</td>
                    <td style="text-align: center;"><?= $model->peruntukan=='Industri'?'(Kadiv Operasional)':'(Kadiv Marketing)' ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>