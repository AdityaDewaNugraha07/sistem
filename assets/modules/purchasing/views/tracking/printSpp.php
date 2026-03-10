<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanSpp',['model'=>$model,'paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<table id="print-spo" border="0">
<tr>
<td style="width: 20cm; padding-right: 1cm; vertical-align: top;">
	<table style="width: 100%; margin: 5px;" border="0" >
		<tr style="height: 2cm;">
			<td colspan="2" style="vertical-align: top; height: 2cm;">
				<?php echo $header; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top; ">
				<table border="1" style="width: 100%; font-size: 1.3rem;">
					<tr>
						<td style="font-weight: bold; text-align: center; width: 1cm; padding: 3px;">No.</td>
						<td style="font-weight: bold; text-align: center; width: 7cm;">Nama Barang</td>
						<td style="font-weight: bold; text-align: center; width: 2cm;">Satuan</td>
						<td style="font-weight: bold; text-align: center; width: 3cm;">Harga Satuan</td>
						<td style="font-weight: bold; text-align: center; width: 2cm;">Jumlah</td>
						<td style="font-weight: bold; text-align: center; width: 5cm;">Keterangan</td>
					</tr>
					<?php 
//						for($i=0;$i<20;$i++){
//						if( count($modDetail) >= ($i+1) ){
						if(count($modDetail)>0){
							foreach($modDetail as $i => $detail){
						
					?>
						<tr style="font-size: 1.2rem;">
							<td style="text-align: center;"><?= $i+1; ?></td>
							<td style="padding: 2px;"><?= $detail->bhp->Bhp_nm; ?></td>
							<td style="padding: 2px; text-align: center;"><?= $detail->bhp->bhp_satuan; ?></td>
							<td style="text-align: right; padding-right: 5px;"></td>
							<td style="text-align: center;"><?= $detail->sppd_qty; ?></td>
							<td style="padding: 2px; font-size: 1.1rem;"><?php echo $detail->sppd_ket; ?></td>
						</tr>
					<?php
//						}else{
							?>
						
					<?php } ?>
					<?php } ?>
				</table>
			</td>
		</tr>
		<tr style="height: 0.5cm;"><td style="">&nbsp;</td></tr>
		<tr>
			<td colspan="2" style="vertical-align: top; font-size: 1.2rem; height: 3cm;">
				<table style="width: 100%; height: 2.5cm;" border="1">
					<tr style="text-align: center; font-size: 1.2rem; height: 0.5cm;">
						<td style="width: 8cm; text-align: left; padding: 5px;">
							Catatan :
						</td>
						<td>Diketahui oleh</td>
						<td>Disetujui oleh</td>
						<td>Dibuat oleh</td>
					</tr>
					<tr style="">
						<td style="padding: 5px; text-align: left; vertical-align: top; font-size: 1.2rem; border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
							Dibutuhkan Tanggal :
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
