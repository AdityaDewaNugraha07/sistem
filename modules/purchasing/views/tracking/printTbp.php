<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanTbp',['model'=>$model,'paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<style>
#print-content tr td{
	border: solid 1px #000;
}
</style>
<table id="print-spo" border="0" style="margin: 10px; margin-left: 15px;">
<tr>
<td style="width: 20cm; padding-right: 1cm; vertical-align: top;">
	<table style="width: 100%; " border="0" >
		<tr style="height: 2.8cm;">
			<td colspan="2" style="vertical-align: top; height: 2cm;">
				<?php echo $header; ?>
			</td>
		</tr>
	</table>
	<table style="width: 100%; " border="0" id="print-content">
		<tr style="font-size: 1.3rem;">
			<td style="font-weight: bold; text-align: center; width: 1cm; padding: 3px;">No.</td>
			<td style="font-weight: bold; text-align: center; width: 6.5cm;">Nama Barang</td>
			<td style="font-weight: bold; text-align: center; width: 1cm;">Qty</td>
			<td style="font-weight: bold; text-align: center; width: 1cm;">Unit</td>
			<td style="font-weight: bold; text-align: center; width: 2.5cm;">Harga <span style="font-size: 1.1rem;">(<?= $matauang ?>)</span></td>
			<td style="font-weight: bold; text-align: center; width: 2.5cm;">Subtotal <span style="font-size: 1.1rem;">(<?= $matauang ?>)</span></td>
			<td style="font-weight: bold; text-align: center; width: 5.5cm;">Keterangan</td>
		</tr>
		<?php 
		$total = 0;
		$pph = 0;
		foreach($modDetail as $i => $detail){
		$pagebreak_css = "";
		$pagebreak_html = "";
		if ( ($i+1)%30 == 0){
			$pagebreak_css = "page-break-after:always;";
			$pagebreak_html = "<tr><td colspan='6' style='border-left: solid 1px transparent; border-right: solid 1px transparent;'>&nbsp;</td></tr>";
		}
		$total += $detail->terimabhpd_qty * $detail->terimabhpd_harga;
		$pph += $detail->pph_peritem;
		?>
		<tr style="font-size: 1.2rem; <?= $pagebreak_css ?>">
			<td style="text-align: center;"><?= $i+1; ?></td>
			<td style="padding: 2px;"><?= $detail->bhp->bhp_nm; ?></td>
			<td style="text-align: center;"><?= $detail->terimabhpd_qty; ?></td>
			<td style="padding: 2px; text-align: center;"><?= $detail->bhp->bhp_satuan; ?></td>
			<td style="padding: 2px; text-align: right; padding: 5px;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga); ?></td>
			<td style="padding: 2px; text-align: right; padding: 5px;"><?= \app\components\DeltaFormatter::formatNumberForUser($detail->terimabhpd_qty*$detail->terimabhpd_harga,2); ?></td>
			<td style="padding: 2px; font-size: 1.0rem; line-height: 10px;"><?php echo $detail->terimabhpd_keterangan; ?></td>
		</tr>
		<?php echo $pagebreak_html; ?>
		<?php } ?>
		<tr style="font-size: 1.2rem; font-weight: bold;">
			<td style="text-align: right;" colspan="5">Ppn &nbsp; </td>
			<td style="padding: 2px; text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($detail->terimaBhp->ppn_nominal,2); ?></td>
		</tr>
		<tr style="font-size: 1.2rem; font-weight: bold;">
			<td style="text-align: right;" colspan="5">Pph &nbsp; </td>
			<td style="padding: 2px; text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($pph); ?></td>
		</tr>
		<tr style="font-size: 1.2rem; font-weight: bold;">
			<td style="text-align: right;" colspan="5">Total &nbsp; </td>
			<td style="padding: 2px; text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUser($total + $detail->terimaBhp->ppn_nominal - $pph,2); ?></td>
		</tr>
	</table>
	<table style="width: 100%; " border="0" id="print-footer">
		<tr style="height: 0.5cm;"><td style="">&nbsp;</td></tr>
		<tr>
			<td colspan="2" style="vertical-align: top; font-size: 1.2rem; height: 3cm;">
				<table style="width: 100%; height: 2.5cm;" border="1">
					<tr style="text-align: center; font-size: 1.2rem; height: 0.5cm;">
						<td style="width: 8cm; text-align: left; padding: 5px;">
							Ket :
						</td>
						<td>Disetujui</td>
						<td>Diperiksa</td>
						<td>Diterima</td>
					</tr>
					<tr style="">
						<td style="padding: 5px; text-align: left; vertical-align: top; font-size: 1.2rem; border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
							<?= $model->terimabhp_keterangan; ?>
						</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr style="height: 0.5cm; font-size: 1.2rem;">
						<td style="text-align: left; vertical-align: top;">
							
						</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>

</tr>
</table>
<?php
echo "<span style='font-size:1.0rem'>";
echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
echo "</span>";
?>
