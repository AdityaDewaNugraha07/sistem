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
$company = app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
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
<table style="width: 20cm; margin: 10px; height: 10cm;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0" id="table-detail">
				<tr style="">
					<td colspan="13" style="padding: 5px; border-bottom: solid 1px #000;">
						<table style="width: 100%; " border="0">
							<tr style="">
								<td style="width: 4cm; text-align: left; vertical-align: top; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
									<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 75px;"> 	
								</td>
								<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
									<span style="font-size: 2rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
								</td>
								<td style="width: 6.5cm; height: 1cm; vertical-align: top; padding: 10px 10px 10px 3px;">
									<table>
										<tr>
											<td style="width:2cm;"><b>Nomor</b></td>
											<td>: &nbsp; <?= $model->nomor_dokumen; ?></td>
										</tr>
										<tr>
											<td><b>Tanggal</b></td>
											<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal ); ?> </td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="height: 0.5cm; border-bottom: solid 1px #000; border-top: solid 1px #000;">
					<td rowspan="2" style="width: 6cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Jenis Kayu</center></b></td>
					<td rowspan="2" style="width: 2cm; border-right: solid 1px #000; vertical-align: middle;"><center><b>QR Code</b><br>Lapangan<br>Grade<br>Batang</center></td>
					<td colspan="4" style="width: 4cm; border-right: solid 1px #000;"><b><center>Diameter (cm)</center></b></td>
					<td colspan="3" style="width: 3cm; border-right: solid 1px #000;"><b><center>Cacat (cm)</center></b></td>
					<td colspan="3" style="width: 3cm; border-right: solid 1px #000;"><b><center>Ukuran</center></b></td>
					<td rowspan="2" style="width: 2cm; vertical-align: middle;"><b><center>Keterangan</center></b></td>
				</tr>
				<tr style="border-bottom: solid 1px #000;">
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Ujung<br>1</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Ujung<br>2</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Pangkal<br>1</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Pangkal<br>2</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>P</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Gb</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Gr</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Panjang<br>(m)</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Diameter<br>(cm)</center></b></td>
					<td style="width: 2cm; border-right: solid 1px #000; vertical-align: middle"><b><center>Volume<br>(m<sup>3</sup>)</center></b></td>
				</tr>
				<?php
				$max = 35;
				$total_besar = 0;
				$total_kecil = 0;
				$total_kubik = 0;
				$trtotal = 0;
				?>
				<?php foreach($modDetail as $i => $detail){
						$total_besar += $detail->qty_besar;
						$total_kecil += $detail->qty_kecil;
						$total_kubik += $detail->kubikasi;
						$modSpm = \app\models\TSpmKo::findOne(['spm_ko_id'=>$model->spm_ko_id]);
                        $modSPMDetail = \app\models\TSpmKoDetail::findOne(['spm_ko_id'=>$model->spm_ko_id,'produk_id'=>$detail->produk_id]);
						// $modSpmLog = \app\models\TSpmLog::findOne(['reff_no'=>$modSpm->kode, 'volume'=>$detail->kubikasi]);
						$modSpmLog = \app\models\TSpmLog::findOne($detail->spm_log_id);
						$modKayu = \app\models\MKayu::findOne($modSpmLog->kayu_id);

                        $trtotal = $trtotal+1;?>
						<tr>
							<td style="padding: 2px 5px; border-right: 1px solid black;">
                                <?php 
								echo $modKayu->group_kayu .' - '. $modKayu->kayu_nama;
								//echo $detail->log->log_nama;
								?>
                            </td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><b>".$modSpmLog->no_barcode."</b><br>".$modSpmLog->no_lap."<br>".$modSpmLog->no_grade."<br>".$modSpmLog->no_btg."</span>";?>
							</td>
							<!-- <td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php //echo "<span><center>".$modSpmLog->no_lap."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php //echo "<span><center>".$modSpmLog->no_grade."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php //echo "<span><center>".$modSpmLog->no_btg."</center></span>";?>
							</td> -->
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->diameter_ujung1."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->diameter_ujung2."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->diameter_pangkal1."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->diameter_pangkal2."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->cacat_panjang."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->cacat_gb."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->cacat_gr."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->panjang."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
                                <?php echo "<span><center>".$modSpmLog->diameter_rata."</center></span>";?>
							</td>
							<td style="padding: 2px 5px; border-right: solid 1px #000;">
								<span style="float: right">
                                    <?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi);?>
								</span> 
							</td>
							<td style="padding: 2px 5px;">
								<?= $detail->keterangan ?>
							</td>
						</tr>
					<?php } ?>
				<?php
				$max = $max-$trtotal;
				$trkosong = '<tr><td style="padding: 2px 5px; border-right: 1px solid black; border-left: solid 1px transparent;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px; border-right: 1px solid black;">&nbsp;</td>
							<td style="padding: 2px 5px;">&nbsp;</td></tr>';
				for($i=0;$i<$max;$i++){ echo $trkosong; }
				?>
				<tr style="border-top: solid 1px #000; background-color: #F1F4F7;" >
					<td colspan="11" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>TOTAL</b> &nbsp;</td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
						<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kubik) ?>
					</b></td>
					<td></td>
				</tr>
				<tr style="border-top: solid 1px #000; background-color: #F1F4F7;" >
					<td colspan="11" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>TOTAL QTY</b> &nbsp;</td>
					<td class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>
					<?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil) ?> <i>(<?= $modDetail[0]->satuan_kecil ?>)</i>
					</b></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr style="border-bottom: solid 1px transparent;">
		<td colspan="3" style=" border-top: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
				<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
					<td rowspan="2" style="text-align: justify;  line-height: 1; vertical-align: top; padding: 8px; border-right: solid 1px transparent; border-bottom: solid 1px transparent;">
						
					</td>
					<td rowspan="3" style="width: 8cm; text-align: center; vertical-align: bottom;">
						
					</td>
					<td style="width: 4.5cm; vertical-align: middle; background-color: #F1F4F7; padding: 3px;">Diterbitkan Oleh</td>
				</tr>
				<tr>
					<td style="height: 1.75cm; font-size:1rem; text-align: left; vertical-align: bottom; padding: 3px; border-right: solid 1px transparent;">
						&nbsp; Tgl : <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal ); // date("d/m/Y") ?>
					</td>
				</tr>
				<tr>
					<td style="text-align: left; vertical-align: bottom; padding-bottom: 2px; padding-left: 3px; border-right: solid 1px transparent; font-size: 1rem;">
						<?php
						echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
						echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
						?>
					</td>
					<td style="background-color: #F1F4F7; height: 20px; vertical-align: middle;  border-right: solid 1px transparent; font-weight: bold; line-height: 0.9; padding: 3px;">
						<?php
						if(!empty($model->petugas_legalkayu_id)){
							echo "<span style='font-size:0.9rem'>".$model->petugasLegalkayu->pegawai->pegawai_nama."</span><br>";
							echo "<span style='font-size:0.9rem'>Reg No. (".$model->petugasLegalkayu->noreg.")</span>";
						}
						?><br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;">
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">
				<?php echo "CWM-FK-TUK-03-0"; ?>
			</span>
		</td>
	</tr>
</table>
<?php $this->registerJs("
    setpagebreak();
", yii\web\View::POS_READY); ?>
<script>
function setpagebreak(){
	var pagerow = [50,100,150];
	$.each(pagerow,function(index,value){
		$("#table-detail > tbody > tr:eq( "+value+" )").attr('style','page-break-after:always;');
		$("<tr><td colspan='8' style='height:50px;'>&nbsp;</td></tr>").insertAfter("#table-detail > tbody > tr:eq( "+(value)+" )");
		$("<tr>"+( $("#table-detail > tbody > tr:eq( 2 )").html() )+"</tr>"+"<tr>"+( $("#table-detail > tbody > tr:eq( 3 )").html() )+"</tr>").insertAfter("#table-detail > tbody > tr:eq( "+(value+1)+" )");
	});
}
</script>