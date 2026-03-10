<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanSpo',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<table id="print-spo" border="0">
<tr>
<td style="width: 16cm; height: 20cm; padding-right: 1cm; vertical-align: top;">
	<table style="width: 100%; margin: 5px;" border="0" >
		<tr style="height: 3cm;">
			<td colspan="2" style="vertical-align: top; height: 3cm;">
				<?php echo $header; ?>
			</td>
		</tr>
		<tr style="height: 2cm;">
			<td style="text-align: left; vertical-align: top; font-size: 1.4rem;">
				Kepada Yth, <b><?= !empty($model->suplier_id)?$model->suplier->suplier_nm:""; ?></b><br>
				<?php
				if(!empty($model->suplier->fax)){
					echo "<span style='font-size:1.3rem; font-weight:bold; margin-bottom:30px;'>(FAX: ".$model->suplier->fax.")</span><br style='margin-bottom: 15px;' >";
				}
				if(!empty($model->tanggal_kirim)){
					echo 'Bersama ini kami mohon dikirim barang-barang sbb pada tanggal '.app\components\DeltaFormatter::formatDateTimeForUser($model->tanggal_kirim).' :';
				}else{
					echo 'Dengan Hormat,<br>Bersama ini kami mohon diberikan barang-barang sbb:';
				}
				?>
			</td>
			<td style="text-align: right; vertical-align: top; font-size: 1.4rem; width: 200px;">
				Tanggal, <?= \app\components\DeltaFormatter::formatDateTimeForUser($model->spo_tanggal); ?><br>
				<?php
				if($model->spo_is_ppn == TRUE){
					echo "<b><i>Include Ppn</i></b>";
				}else{
					echo "<b><i>Exclude Ppn</i></b>";
				}
				?>
				<br>
				<u><span style="font-weight: bold;">
					<?= $model->spo_kode ?>
				</span></u>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top; font-size: 1.4rem;">
				<table border="1" style="width: 100%;">
					<tr>
						<td style="font-weight: bold; font-size: 1.3rem; width: 1cm; text-align: center; padding: 3px;">No.</td>
						<td style="font-weight: bold; font-size: 1.3rem; text-align: center;  width: 5.5cm;">Nama Barang</td>
						<td style="font-weight: bold; font-size: 1.3rem; width: 2cm; text-align: center;">Qty</td>
						<td style="font-weight: bold; font-size: 1.3rem; text-align: center;  width: 2.5cm;">Harga <span style="font-size: 1.2rem;">(<?= $model->defaultValue->name_en; ?>)</span></td>
						<td style="font-weight: bold; font-size: 1.3rem; text-align: center;  width: 2.5cm;">Total <span style="font-size: 1.2rem;">(<?= $model->defaultValue->name_en; ?>)</span></td>
						<td style="font-weight: bold; font-size: 1.3rem; text-align: center;">Keterangan</td>
					</tr>
					<?php 
						$max = count($modDetail);
						$total_duid = "";
						for ($i=0;$i<$max;$i++) {
						
							if ( $max >= ($i+1) ){
							
								$ppn = $modDetail[$i]->spod_harga * 0.1;
								if ($model->spo_is_ppn == TRUE){
									$harga = $modDetail[$i]->spod_harga + $ppn;
								}else{
									$harga = $modDetail[$i]->spod_harga;
								}
					?>
						<tr style="font-size: 1.2rem;">
							<td style="text-align: center;"><?= $i+1; ?></td>
							<td style="padding: 2px;"><?= $modDetail[$i]->bhp->Bhp_nm; ?></td>
							<td style="text-align: center;"><?= $modDetail[$i]->spod_qty." (".$modDetail[$i]->bhp->bhp_satuan.")"; ?></td>
							<td style="text-align: right; padding-right: 5px;"><?= \app\components\DeltaFormatter::formatNumberForUser($harga); ?></td>
							<td style="text-align: right; padding-right: 5px;"><?= \app\components\DeltaFormatter::formatNumberForUser($modDetail[$i]->spod_qty * $harga); ?></td>
							<td style="padding: 2px;"><?php echo $modDetail[$i]->spod_keterangan; ?></td>
						</tr>
					<?php
							} else {
							?>
							<tr style="font-size: 1.2rem;">
								<td style="text-align: center;">&nbsp;</td>
								<td style="text-align: center;">&nbsp;</td>
								<td style="text-align: center;">&nbsp;</td>
								<td style="text-align: center;">&nbsp;</td>
								<td style="text-align: center;">&nbsp;</td>
							</tr>
							<?php 
							} 
							?>
						<?php
							$total_duid += $modDetail[$i]->spod_qty * $harga;
						}
						/*
						$pph = $model->spo_pph_nominal;
						$ppn = $model->spo_ppn_nominal;
						$grand_total = $total_duid + $pph + $ppn;
						*/
						$pph = $model->spo_pph_nominal;                                                
						if ($model->spo_is_ppn == TRUE){
							$ppn=0;
							$grand_total = $total_duid + $pph ;
						}else{                                                     
							$ppn = $model->spo_ppn_nominal;
							$grand_total = $total_duid + $pph + $ppn;   
						}
						?>
					
					<tr>
						<td style="font-weight: bold; font-size: 1.3rem; padding-right: 5px;" colspan="4" class="text-right">Total </td>
						<td style="font-weight: bold; font-size: 1.3rem; padding-right: 5px;" class="text-right"><?= \app\components\DeltaFormatter::formatNumberForUser($total_duid); ?></td>
						<td style="font-weight: bold; font-size: 1.3rem;">&nbsp;</td>
					</tr>
					<tr>
						<td style="font-weight: bold; font-size: 1.3rem; padding-right: 5px;" colspan="4" class="text-right">PPh </td>
						<td style="font-weight: bold; font-size: 1.3rem; padding-right: 5px;" class="text-right"><?= \app\components\DeltaFormatter::formatNumberForAllUser($pph); ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td style="font-weight: bold; font-size: 1.3rem; padding-right: 5px;" colspan="4" class="text-right">PPn </td>
						<td style="font-weight: bold; font-size: 1.3rem; padding-right: 5px;" class="text-right"><?= \app\components\DeltaFormatter::formatNumberForUser($ppn); ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td style="font-weight: bold; font-size: 1.3rem; padding-right: 5px;" colspan="4" class="text-right">Grand Total </td>
						<td style="font-weight: bold; font-size: 1.3rem; padding-right: 5px;" class="text-right"><?= \app\components\DeltaFormatter::formatNumberForUser($grand_total); ?></td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top; font-size: 1.4rem; height: 3cm;">
				<table style="width: 100%; " border="0">
					<tr style="">
						<td style="text-align: left; vertical-align: top; font-size: 1.4rem;">
							<p>
								Hormat Kami, 
							</p>
						</td>
					</tr>
					<tr style="">
						<td style="text-align: left; vertical-align: bottom; font-size: 1.4rem; height: 40px;">
							( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>

</tr>
</table>