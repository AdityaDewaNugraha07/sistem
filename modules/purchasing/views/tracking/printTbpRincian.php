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
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 3cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3; padding-right: 50px;">
						<span style="font-size: 1.9rem; font-weight: 600; "><?= $paramprint['judul']; ?></span><br>
						<span style="">
							<?php
							if(!empty($model->spo_id)){
								echo "<b>".$model->suplier->suplier_nm."</b>, ".$model->suplier->suplier_almt;
							}else if(!empty($model->spl_id)){
								$mods = app\models\TTerimaBhpDetail::find()
										->select('suplier_id')
										->groupBy('suplier_id')
										->where(['terima_bhp_id'=>$model->terima_bhp_id])
										->all();
								if(count($mods)==1){
									echo "<b>".$mods[0]->suplier->suplier_nm."</b>, ".$mods[0]->suplier->suplier_almt;
								}else{
									echo "-";
								}
							}
							?>
						</span>
					</td>
					<td style="width: 5.5cm; vertical-align: middle; padding: 2px;">
						<table border="0" style="width: 100%;">
							<tr>
								<td style="width:2.5cm;"><b>Kode TBP</b></td>
								<td style="width:10px;">:</td>
								<td><?= $model->terimabhp_kode; ?></td>
							</tr>
							<tr>
								<td><b>Tanggal</b></td>
								<td>:</td>
								<td> <?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tglterima); ?></td>
							</tr>
							<tr>
								<td ><b>Kode SPO/SPL</b></td>
								<td>:</td>
								<td>
									<?php
									if(!empty($model->spo_id)){
										echo $model->spo->spo_kode;
									}else if(!empty($model->spl_id)){
										echo $model->spl->spl_kode;
									}
									?>
								</td>
							</tr>
							<tr>
								<td><b>Nota / Invoice</b></td>
								<td>:</td>
								<td> <?= !empty($model->nofaktur)?$model->nofaktur:"-"; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<tr style="font-size: 1.3rem; border-bottom: 1px solid black;">
					<td style="font-weight: bold; text-align: center; width: 1cm; padding: 3px; border-right: 1px solid black;">No.</td>
					<td style="font-weight: bold; text-align: center; width: 6.5cm; border-right: 1px solid black;">Nama Barang</td>
					<td style="font-weight: bold; text-align: center; width: 1cm; border-right: 1px solid black;">Qty</td>
					<td style="font-weight: bold; text-align: center; width: 1cm; border-right: 1px solid black;">Unit</td>
					<td style="font-weight: bold; text-align: center; width: 2cm; border-right: 1px solid black;">Harga</td>
					<td style="font-weight: bold; text-align: center; width: 2.3cm; border-right: 1px solid black;">Subtotal</td>
					<td style="font-weight: bold; text-align: center;">Keterangan</td>
				</tr>
				<?php 
				$total = 0;
				$pph = 0;
				$pbbkb = !empty($model->total_pbbkb)?$model->total_pbbkb:0;
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
				<tr>
					<td style="padding: 2px 5px; border-right: 1px solid black; text-align: center;"><?= ($i+1); ?></td>
					<td style="padding: 2px 5px; border-right: 1px solid black;"><?= $detail->bhp->bhp_nm; ?></td>
					<td class="text-align-center" style="padding: 2px 5px; border-right: 1px solid black;"><?= $detail->terimabhpd_qty; ?></td>
					<td style="padding: 2px 5px; border-right: 1px solid black;"><?= $detail->bhp->bhp_satuan; ?></td>
					<td class="text-align-right"style="padding: 2px 5px; border-right: 1px solid black;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga); ?></td>
					<td class="text-align-right" style="padding: 2px 5px; border-right: 1px solid black;"><?= \app\components\DeltaFormatter::formatNumberForUser($detail->terimabhpd_qty*$detail->terimabhpd_harga,2); ?></td>
					<td style="padding: 2px 5px;"><?= $detail->terimabhpd_keterangan ?></td>
				</tr>
				<?php echo $pagebreak_html; ?>
				<?php } ?>
				<tr style="font-weight: bold; text-align: right; border-top: 1px solid black;">
                    <td colspan="5" style="padding: 3px; border-right: 1px solid black;">PPN &nbsp; </td>
                    <td style="padding: 3px; border-right: 1px solid black;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->ppn_nominal); ?></td>
                </tr>
                <tr style="font-weight: bold; text-align: right; border-top: 1px solid black;">
                    <td colspan="5" style="padding: 3px; border-right: 1px solid black;">Pph &nbsp; </td>
                    <td style="padding: 3px; border-right: 1px solid black;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($pph); ?></td>
                </tr>
                <?php if($pbbkb>0){ ?>
                <tr style="font-weight: bold; text-align: right; border-top: 1px solid black;">
                    <td colspan="5" style="padding: 3px; border-right: 1px solid black;">Pbbkb &nbsp; </td>
                    <td style="padding: 3px; border-right: 1px solid black;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($pbbkb); ?></td>
                </tr>
                <?php } ?>
                <?php 
                if ($model->total_biayatambahan != '' || $model->total_biayatambahan != NULL) { 
                ?>
                <tr style="text-align: right; border-top: 1px solid black;">
                    <td colspan="5" style="padding: 3px; font-size: 1rem;  line-height: 0.9; border-right: 1px solid black;"><b>Biaya Tambahan</b> <br><?= $model->label_biayatambahan ?></td>
                    <td style="padding: 3px; font-weight: bold; border-right: 1px solid black;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_biayatambahan); ?></td>
                </tr>
                <?php 
                } 
                ?>
				<?php 
                if ($model->potonganharga != '' || $model->potonganharga != NULL) { 
                ?>
                <tr style="text-align: right; border-top: 1px solid black;">
                    <td colspan="5" style="padding: 3px; font-size: 1rem;  line-height: 0.9; border-right: 1px solid black;"><b>Potongan Harga</b> <br><?= $model->label_potonganharga ?></td>
                    <td style="padding: 3px; font-weight: bold; border-right: 1px solid black;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->potonganharga); ?></td>
                </tr>
                <?php 
                } 
                ?>
                <tr style="font-weight: bold; text-align: right; border-top: 1px solid black; border-bottom: 1px solid black;">
                    <td colspan="5" style="padding: 3px; border-right: 1px solid black;">Total &nbsp; </td>
                    <td style="padding: 3px; border-right: 1px solid black;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->totalbayar); ?></td> <!-- langsung ambil ke table induk t_terima_bhp 26/6/19 -->
                </tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
		</td>
	</tr>
</table>