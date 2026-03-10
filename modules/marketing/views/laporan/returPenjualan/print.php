<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$kode = $model->kode;
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
$max = 12;
$sql = $model->searchLaporan()->createCommand()->rawSql;
$modDetail = Yii::$app->db->createCommand($sql)->queryAll();
if(count($modDetail) > $max){
	$max = count($modDetail);
}
$total_besar = 0;
$total_kecil = 0;
$total_kubikasi = 0;
$total_retur = 0;
?>
<style>
table{
	font-size: 1.1rem;
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
		<td colspan="3" style="padding: 5px; border-bottom: solid 1px transparent;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 4cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= $paramprint['judul2'] ?>
					</td>
					<td style="width: 3cm; height: 1cm; vertical-align: top; padding: 10px;">
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<tr style="border-bottom: solid 1px #000; border-top: solid 1px #000;">
					<th style="width: 110px; text-align: center;  padding: 10px; border-right: solid 1px #000;"><?= Yii::t('app', 'Kode Retur') ?></th>
					<th style="width: 90px; text-align: center;  padding: 10px; border-right: solid 1px #000;"><?= Yii::t('app', 'Tanggal') ?></th>
					<th style="width: 110px; text-align: center;  padding: 10px; border-right: solid 1px #000;"><?= Yii::t('app', 'Nomor Nota') ?></th>
					<th style="text-align: center;  padding: 10px; border-right: solid 1px #000;"><?= Yii::t('app', 'Customer'); ?></th>
					<th style="width: 90px; text-align: center;  padding: 10px; border-right: solid 1px #000; line-height: 1;"><?= Yii::t('app', 'Total<br>Pcs'); ?></th>
					<th style="width: 80px; text-align: center;  padding: 10px; border-right: solid 1px #000; "><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
					<th style="width: 105px; text-align: center;  padding: 10px; "><?= Yii::t('app', 'Total'); ?></th>
				</tr>
				<?php for($i=0;$i<$max;$i++){
					if( count($modDetail) >= ($i+1) ){
						$total_kecil += $modDetail[$i]['pcs'];
						$total_kubikasi += $modDetail[$i]['kubikasi'];
						$total_retur += $modDetail[$i]['total_retur'];
				?>
					<tr>
						<td style="text-align: center; padding: 3px; border-right: solid 1px #000;"><?= $modDetail[$i]['kode']; ?></td>
						<td style="text-align: center; padding: 3px; border-right: solid 1px #000;"><?= \app\components\DeltaFormatter::formatDateTimeForUser($modDetail[$i]['tanggal']); ?></td>
						<td style="text-align: center; padding: 3px; border-right: solid 1px #000;"><?= $modDetail[$i]['nomor_nota']; ?></td>
						<td style="padding: 3px; border-right: solid 1px #000;"><?= $modDetail[$i]['cust_an_nama']; ?></td>
						<td style="text-align: right; padding: 3px; border-right: solid 1px #000;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]['pcs']); ?></td>
						<td style="text-align: right; padding: 3px; border-right: solid 1px #000;"><?= number_format($modDetail[$i]['kubikasi'],4); ?></td>
						<td style="text-align: right; padding: 3px;">
							<?php if($_GET['caraprint'] != "EXCEL"){ ?>
								<span class="pull-left">Rp. </span>
							<?php } ?>
							<span class="pull-right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[$i]['total_retur']); ?></span>
						</td>
					</tr>
				<?php }else{ ?>
					<tr>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">&nbsp;</td>
						<td style="padding: 2px 5px; border-right: solid 1px #000;">&nbsp;</td>
						<td style="padding: 2px 5px;">&nbsp;</td>
					</tr>
				<?php } ?>
				<?php } ?>
				<tr style="border-top: solid 1px #000;" >
					<th colspan="4" style="padding: 5px; text-align: right; border-right: solid 1px #000;">Total &nbsp;</th>
					<td style="padding: 5px; text-align: right; border-right: solid 1px #000;"><b><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_kecil); ?></b></td>
					<td style="padding: 5px; text-align: right; border-right: solid 1px #000;"><b><?= (strlen(substr(strrchr($total_kubikasi, "."), 1)) > 4)? $total_kubikasi*10000/10000: $total_kubikasi ?></b></td>
					<td style="padding: 5px; text-align: right;"><b>
						<?php if($_GET['caraprint'] != "EXCEL"){ ?>
							<span class="pull-left" style="margin-left: -3px;">Rp. </span>
						<?php } ?>
							<span class="pull-right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_retur); ?></span>
					</b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
			<?php
			echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
			echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
			?>
			<span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-MKT-12-0</span>
		</td>
	</tr>
	<tr style="border: solid 1px transparent; border-top: solid 1px #000;">
		<td colspan="3" style="border-right: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; text-align: center;">
				<tr style="height: 0.4cm;  ">
					<td rowspan="3" style="vertical-align: middle;">&nbsp;</td>
					<td style="vertical-align: middle; width: 4cm; ">Disetujui Oleh</td>
					<td style="vertical-align: middle; width: 4cm; ">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
					<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left;"></td>
				</tr>
				<tr>
					<td style="height: 20px; vertical-align: middle; line-height: 1">
						<?php
							echo "<span style='font-size:0.9rem'><b><u> ".app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_IWAN_SULISTYO)->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Kadiv Marketing</span>";
						?>
					</td>
					<td style="height: 20px; vertical-align: middle; line-height: 1">
						<?php
							echo "<span style='font-size:0.9rem'><b><u> ".app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_RYA)->pegawai_nama." </u></b></span><br>";
//                                                      if(($model->tanggal)<='2020-01-21'){
//                                                                echo "<span style='font-size:0.9rem'><b><u> ".app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_FITRIYANAH)->pegawai_nama." </u></b></span><br>";
//							}elseif(($model->tanggal)<='2020-04-01'){
//                                                                echo "<span style='font-size:0.9rem'><b><u> ".app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_LINGGA)->pegawai_nama." </u></b></span><br>";
//							}else{
//                                                                echo "<span style='font-size:0.9rem'><b><u> ".app\models\MPegawai::findOne(\app\components\Params::DEFAULT_PEGAWAI_ID_RYA)->pegawai_nama." </u></b></span><br>";
//                                                      }
							echo "<span style='font-size:0.8rem'>Kanit Adm Marketing</span>";
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>