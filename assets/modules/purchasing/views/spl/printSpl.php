<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanSpl',['paramprint'=>$paramprint,'model'=>$model]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<table style="width: 20cm; margin: 5px;" border="1" >
	<tr>
		<td colspan="2" style="vertical-align: top;">
			<table style="width: 100%; height: 1.3cm;" border="0">
				<tr style="">
								<td style="text-align: center; width: 13cm; vertical-align: middle;" colspan="2">
						<span style="font-size: 1.9rem; font-weight: 600"><u><?= $paramprint['judul'] ?></u></span>
					</td>
					<td style="text-align: right;">
						<table style="width: 100%; font-size: 1.3rem" border="0">
							<tr>
								<td style="width: 2cm;">No. &nbsp; </td>
								<td style="text-align: left;">: &nbsp; <?= $model->spl_kode; ?> </td>
							</tr>
							<tr >
								<td >Tanggal &nbsp; </td>
								<td style="text-align: left;">: &nbsp; <?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->spl_tanggal); ?> </td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table border="1" style="width: 100%; border-left: solid 1px transparent; border-right: solid 1px transparent;">
				<tr style="font-size: 1.4rem;">
					<td style="text-align: center; width: 0.7cm;">No.</td>
					<td style="text-align: center; width: 7cm;">Kode / Nama Barang</td>
					<td style="width: 1.3cm; text-align: center;">Qty</td>
					<td style="width: 3cm; text-align: center;">Harga Satuan</td>
					<td style="width: 5cm; text-align: center;">Keterangan</td>
					<td style="width: 3.5cm; text-align: center;">Nama Supplier</td>
				</tr>
				<?php 
					$max = count($modDetail);
					for($i=0;$i<$max;$i++){
					if( $max >= ($i+1) ){
				?>
					<tr style="font-size: 1.2rem;">
						<td style="padding: 2px; text-align: center;"><?= $i+1; ?></td>
						<td style="padding: 2px;"><?= $modDetail[$i]->bhp->Bhp_nm; ?></td>
						<td style="text-align: right;"><?= $modDetail[$i]->spld_qty.' '.$modDetail[$i]->bhp->bhp_satuan; ?></td>
						<td style="padding: 2px;">&nbsp;</td>
						<td style="padding: 2px; font-size: 1rem;"><?= !empty($modDetail[$i]->spld_keterangan)?$modDetail[$i]->spld_keterangan:" "; ?></td>
						<td style="padding: 2px;"><?= !empty($modDetail[$i]->suplier_id)?$modDetail[$i]->suplier->suplier_nm:" "; ?></td>
					</tr>
				<?php
					}else{
						?>
					<tr style="font-size: 1.2rem;">
						<td style="text-align: center;">&nbsp;</td>
						<td style="text-align: center;">&nbsp;</td>
						<td style="text-align: center;">&nbsp;</td>
						<td style="text-align: center;">&nbsp;</td>
						<td style="text-align: center;">&nbsp;</td>
					</tr>
				<?php } ?>
				<?php } ?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="vertical-align: bottom; border-top: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; height: 2cm; border-bottom: solid 1px transparent; border-left: solid 1px transparent; border-right: solid 1px transparent;" border="1">
				<tr style=" text-align: center;">
					<td rowspan="2" style="width: 8cm;">&nbsp;</td>
					<td style="height: 20px; width: 4.3cm;">Diketahui Oleh</td>
					<td style="width: 4.2cm;">Disetujui Oleh</td>
					<td style="width: 4.3cm;">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="font-size: 0.9rem;">&nbsp;</td>
					<td style="text-align: center; vertical-align: bottom; font-size: 0.9rem;"><?= strtoupper($model->splDisetujui->pegawai_nama); ?></td>
					<td style="text-align: center; vertical-align: bottom; font-size: 0.9rem;"><?= strtoupper(Yii::$app->user->getIdentity()->userProfile->fullname); ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
if(isset($_GET['caraprint'])){
	if($_GET['caraprint'] == 'PRINT'){
		echo "<span style='font-size:1.0rem'>";
		echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
		echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
		echo "</span>";
	}
}
?>
