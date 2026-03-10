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
$totalrow = 0;
?>
<style>
table{
	font-size: 1.3rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<table style="width: 25cm; margin: 10px;" border="0">
	<tr style="height: 2cm;">
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: middle; padding: 10px; line-height: 1.3;">
						<u><span style="font-size: 1.8rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br></u>
						Nomor : <?= $model->nomor ?>
					</td>
					<td style="width: 5cm; vertical-align: top; padding: 10px;"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="">
		<td colspan="3" style="padding: 8px; background-color: #F1F4F7;">
			<table style="width: 100%">
				<tr>
					<td style="width: 100%; vertical-align: top; padding-left: 10px;">	
						<table style="width: 100%;">
							<tr>
								<td colspan="3" style="padding: 10px 0 10px 0;">Yang bertanda tangan di bawah ini :</td>
							</tr>
							<tr>
								<td style="width: 3cm; vertical-align: top; padding-left: 15px;"><b>Nama</b></td>
								<td style="width: 0.3cm; vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><b><?= $model->petugas0->pegawai->pegawai_nama ?></b></td>
							</tr>
							<tr>
								<td style="vertical-align: top; padding-left: 15px;"><b>Jabatan</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><b>Penerbit SKSHHK</b></td>
							</tr>
							<tr>
								<td colspan="3" style="padding: 10px 0 10px 0;">
									Telah mengadakan pemeriksaan atas hasil hutan Kayu bulat milik PT. Cipta Wijaya Mandiri yang telah dirubah bentuk / terjadi pemotongan.<br>
									Adapun perinciannya adalah sebagai berikut : 
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="">
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail" border="1">
				<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
					<td rowspan="2" style="font-size: 1.2rem; width: 1cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><center>No</center></td>
					<td colspan="5" style="font-size: 1.2rem; border-right: solid 1px #000;"><center>Asal Kayu Semula</center></td>
					<td colspan="3" style="font-size: 1.2rem; border-right: solid 1px #000; vertical-align: middle; text-align: right;"><center> Dipotong Menjadi</center></td>
				</tr>
				<tr style="border-bottom: solid 1px #000;">
					<td style="font-size: 1.2rem; width: 6cm; border-right: solid 1px #000;"><center>Nomor Batang Asal</center></td>
					<td style="font-size: 1.2rem; width: 2cm; border-right: solid 1px #000;"><center>Kel. Jenis</center></td>
					<td style="font-size: 1.2rem; width: 2cm; border-right: solid 1px #000;"><center>Panjang <span class="fontsize-1-0">(m)</span></center></td>
					<td style="font-size: 1.2rem; width: 2cm; border-right: solid 1px #000;"><center>&#8709; <span class="fontsize-1-0">(cm)</span></center></td>
					<td style="font-size: 1.2rem; width: 2cm; border-right: solid 1px #000;"><center>Volume <span class="fontsize-1-0">(m<sup>3</sup>)</span></center></td>
					<td style="font-size: 1.2rem; width: 1.8cm; border-right: solid 1px #000; line-height: 1"><center>Jumlah<br>Potong</center></td>
					<td style="font-size: 1.2rem; width: 6cm; border-right: solid 1px #000;"><center>Nomor Batang Baru</center></td>
					<td style="font-size: 1.2rem; border-right: solid 1px #000;"><center>Panjang <span class="fontsize-1-0">(m)</span></center></td>
				</tr>
				<?php
				if(count($modDetail)>0){
					foreach($modDetail as $i => $detail){ ?>
					<tr style="border-bottom: solid 1px #000;">
						<td class="text-align-center" style="padding: 2px; border-right: solid 1px #000;"><?= ($i+1) ?></td>
						<td class="text-align-center" style="padding: 2px; border-right: solid 1px #000;">
							<?= $detail->no_barcode ?>
						</td>
						<td class="text-align-center" style="padding: 2px; border-right: solid 1px #000;">
							<?= $detail->kayu->group_kayu ?>
						</td>
						<td class="text-align-right" style="padding: 2px; padding-right: 15px; border-right: solid 1px #000;">
							<?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->panjang) ?>
						</td>
						<td class="text-align-center" style="padding: 2px; padding-right: 15px; border-right: solid 1px #000;">
							<?= (is_numeric($detail->reduksi)?\app\components\DeltaFormatter::formatNumberForUserFloat($detail->reduksi):"") ?>
						</td>
						<td class="text-align-right" style="padding: 2px; padding-right: 15px; border-right: solid 1px #000;">
							<?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->volume) ?>
						</td>
						<td class="text-align-right" style="padding: 2px; padding-right: 15px; border-right: solid 1px #000;">
							<?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->jumlah_potong) ?>
						</td>
						<?php
						$hasil_pemotongan = yii\helpers\Json::decode($detail->hasil_pemotongan);
						?>
						<td class="text-align-center" style="padding: 0px; border-right: solid 1px #000; border-bottom: solid 1px transparent;">
							<table style="width: 100%; font-size: 1.1rem;" border="0">
								<?php 
								if(count($hasil_pemotongan)>0){
									foreach($hasil_pemotongan as $ii => $potongan){
										$totalrow++;
										echo "<tr>
												<td style='width: 100%; height:0.5cm; border-bottom: solid 1px #000;'>".$potongan['no_barcode_baru']."</td>
											</tr>";
									}
								}
								?>
								
							</table>
						</td>
						<td class="text-align-right" style="padding: 0px; border-right: solid 1px #000; border-bottom: solid 1px transparent;">
							<table style="width: 100%; font-size: 1.1rem;" border="0">
								<?php 
								if(count($hasil_pemotongan)>0){
									foreach($hasil_pemotongan as $ii => $potongan){
										echo "<tr>
												<td style='width: 100%; height:0.5cm; border-bottom: solid 1px #000;'>".$potongan['panjang_baru']."&nbsp; &nbsp; </td>
											</tr>";
									}
								}
								?>
								
							</table>
						</td>
					</tr>
				<?php
					}
				}
				?>
			</table>
		</td>
	</tr>
	<?php 
	$max = 17; 
	$max = $max-$totalrow;
	$max = $max * 0.5;
	?>
	<tr style="height: <?= $max ?>cm; border: solid 1px transparent; "><td colspan="3"></td></tr>
	<tr style="height: 2cm; border: solid 1px transparent;">
		<td colspan="3" style=" border-top: solid 1px transparent;">
			<table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
				<tr style="height: 1cm;  border-right: solid 1px transparent; border-top: solid 1px transparent;">
					<td style="text-align: left; border-bottom: solid 1px transparent; border-right: solid 1px transparent;"></td>
					<td style="vertical-align: middle; width: 6cm; text-align: center; padding-right: 5px; border: solid 1px transparent;">
						<span style="font-size: 1.2rem;">Semarang, <?= \app\components\DeltaFormatter::formatDateTimeId($model->tanggal) ?></span>
						<center>Penerbit SKSHHK</center>
					</td>
				</tr>
				<tr>
					<td style="font-size:1.4rem; text-align: left; vertical-align: top; border: solid 1px transparent;"></td>
					<td style="height: 1.5cm; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;"></td>
				</tr>
				<tr>
					<td style="vertical-align: bottom; font-size: 0.9rem; text-align: left; border: solid 1px transparent;">
						<?php
						echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
						echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
						?>
					</td>
					<td style="background-color: #F1F4F7; height: 0.7cm; vertical-align: top;  border: solid 1px transparent;  line-height: 1">
						<?php
						if(!empty($model->petugas)){
							echo "<span style='font-size:0.9rem'><b><u>".\app\models\MPegawai::findOne(app\models\MPetugasLegalkayu::findOne($model->petugas)->pegawai_id)->pegawai_nama."</u></b></span><br>";
							echo "<span style='font-size:0.9rem'>".app\models\MPetugasLegalkayu::findOne($model->petugas)->noreg."</span>";
						}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>