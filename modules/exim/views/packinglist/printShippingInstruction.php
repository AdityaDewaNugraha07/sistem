<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
//$kode = explode("-", $model->kode)[0];
$kode = $model->kode;
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
//	$satuanbesar = "CRATES";
//	$satuanbesar2 = "CRTE";
	$satuanbesar = "BUNDLES";
	$satuanbesar2 = "BNDL";
}else{
	$satuanbesar = "BUNDLES";
	$satuanbesar2 = "BNDL";
}
?>
<style>
table{
	font-size: 1rem;
}
table#table-detail{
	font-size: 1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<?php
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
$total_all_pcs = 0; $total_all_volume = 0; $total_all_gross=0; $total_all_nett=0;
$blankspace = 1;
$shipper = ""; $consignee = ""; $notify = "";
if(!empty($model->data_si)){
	$data_si = yii\helpers\Json::decode($model->data_si);
	$si_shipper = $data_si['si_shipper'];
	$si_consignee = $data_si['si_consignee'];
	$si_notify = $data_si['si_notify'];
	$si_gd_product = $data_si['si_gd_product'];
	$si_gd_sizegrade = $data_si['si_gd_sizegrade'];
	$si_gd_total = $data_si['si_gd_total'];
	$si_gdrepeater = $data_si['si_gdrepeater'];
	$si_gd_ket = $data_si['si_gd_ket'];
	$si_instruction = $data_si['si_instruction'];
}
?>
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="width: 3cm; text-align: center; vertical-align: middle; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="width: 8cm; text-align: left; vertical-align: top; padding: 5px; line-height: 1.1;">
						<span style="font-size: 1.3rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
						<span style="font-size: 1rem;"><?= $modCompany->alamat; ?></span><br>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="border-bottom: solid 1px transparent;">
			<table style="width: 100%;" border="0">
				<tr style="">
					<td style="width: 5cm; text-align: left; vertical-align: middle;border-right: solid 1px transparent;"></td>
					<td style="text-align: center; vertical-align: middle; padding: 5px; line-height: 1; height: 1.4cm">
						<span style="font-size: 1.6rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
					</td>
					<td style="width: 5cm; vertical-align: top;">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 5px; background-color: #F1F4F7;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 50%; height: auto; vertical-align: top; padding-left: 5px;">
						<table style="width: 100%;">
							<tr>
								<td style="width: 3cm; vertical-align: top; font-size: 0.9rem;"><b>SHIPPER</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?php echo str_replace("\n", "<br>", $si_shipper); ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>CONSIGNEE</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo str_replace("\n", "<br>", $si_consignee); ?></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>NOTIFY PARTY</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo str_replace("\n", "<br>", $si_notify); ?></td>
							</tr>
							<tr>
								<td style="width: 2cm; vertical-align: top; font-size: 0.9rem;"><b>OCEAN VESSEL / VOY NO.</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->vessel)?$model->vessel:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="width: 2cm; vertical-align: top; font-size: 0.9rem;"><b>PORT OF DISCHARGE</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->final_destination)?$model->final_destination:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="width: 2cm; vertical-align: top; font-size: 0.9rem;"><b>PLACE OF DELIVERY</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->final_destination)?$model->final_destination:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="width: 2cm; vertical-align: top; font-size: 0.9rem;"><b>PORT OF LOADING</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->port_of_loading)?$model->port_of_loading:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
								<td style="width: 2cm; vertical-align: top; font-size: 0.9rem;"><b>PLACE OF RECEIPT</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->port_of_loading)?$model->port_of_loading:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							</tr>
								<td style="width: 2cm; vertical-align: top; font-size: 0.9rem;"><b>ETD</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->etd)?app\components\DeltaFormatter::formatDateTimeEn($model->etd):"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							</tr>
								<td style="width: 2cm; vertical-align: top; font-size: 0.9rem;"><b>ETA</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->eta)?app\components\DeltaFormatter::formatDateTimeEn($model->eta):"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
						</table>
					</td>
					<td style="width: 50%; vertical-align: top; padding-left: 15px;">
						<table style="width: 100%;">
							<tr>
								<td style="width: 3cm; vertical-align: top; font-size: 0.9rem;"><b>PEB NO./DATE</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?php echo (!empty($modInvoice->peb_no)?$modInvoice->peb_no:"<span style='font-size:0.8rem'>(TBA)</span>");?>
									<?php echo (!empty($modInvoice->peb_tanggal)?" / ".strtoupper( app\components\DeltaFormatter::formatDateTimeEn($modInvoice->peb_tanggal) ):"");?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>KPBC</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo "<span style='font-size:0.8rem'>(TBA)</span>";?></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>PAYMENT TERM</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo "<span style='font-size:0.8rem'>(TBA)</span>";?></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>SQ/SC NUMBER</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo "<span style='font-size:0.8rem'>(TBA)</span>";?></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>EXPORT REFERENCE</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><b><?php echo (!empty($model->nomor)?$model->nomor:"<span style='font-size:0.8rem'>(TBA)</span>");?></b></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>SERVICE TYPE / MODE</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>FORWARDING AGENT REF</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>ALSO NOTIFY PARTY</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>B/L REQUIREMENT</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;">FULL-SET ORIGINAL AND COPIES NON-NEGOTIABLE</td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>SEAWAY BILL</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>SURRENDER / TLX.R</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"></td>
							</tr>
							<tr>
								<td style="vertical-align: top; font-size: 0.9rem;"><b>PLACE OF BL ISSUE</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->port_of_loading)?$model->port_of_loading:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%;" id="table-detail">
				<tr style="border-bottom: solid 1px #000; height: 0.5cm;">
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; width: 6cm;"><b><center>CONTAINER NO. / SEAL NO.</center></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; width: 1.8cm;"><b><center>QTY (PCS)</center></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; width: 1.8cm;"><b><center>QTY (<?= $satuanbesar2 ?>)</center></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; width: 1.8cm;"><b><center>GW (KGS)</center></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; width: 1.8cm;"><b><center>MSMT (CBM)</center></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; width: 1.8cm;"><b><center>NW (KGS)</center></b></td>
					<td style="padding: 5px; border-bottom: solid 1px transparent; vertical-align: middle;"><b><center></center></b></td>
				</tr>
				<?php 
				$total_pcs = 0; $total_gross_weight = 0; $total_nett_weight=0;
				$sql = "SELECT container_no, container_kode, seal_no, gross_weight, nett_weight, container_size, SUM(pcs) AS pcs, SUM( ROUND( volume::numeric, 4)) AS volume
						FROM t_packinglist_container 
						WHERE t_packinglist_container.packinglist_id = '{$model->packinglist_id}' 
						GROUP BY 1,2,3,4,5,6 ORDER BY container_no ASC";
				$modDetails = Yii::$app->db->createCommand($sql)->queryAll();
				foreach($modDetails as $i => $detail){
					$total_pcs += $detail['pcs'];
					$total_gross_weight += $detail['gross_weight'];
					$total_nett_weight += $detail['nett_weight'];
				}
				?>
				<tr style="font-weight: 600; height: 7cm;">
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle;">
						<?php foreach($modDetails as $i => $detail){
							echo "<center>".$detail['container_kode']." / ".$detail['seal_no']."</center>";
						} ?>
					</td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; text-align: right;">
						<?php foreach($modDetails as $i => $detail){
							echo (!empty($detail['pcs'])?\app\components\DeltaFormatter::formatNumberForUserFloat($detail['pcs']):"")."<br>";
						} ?>
					</td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; text-align: right;">
						<?php
						foreach($modDetails as $i => $detail){
							echo count(Yii::$app->db->createCommand("SELECT bundles_no, max(bundles_no) FROM t_packinglist_container WHERE packinglist_id = {$model->packinglist_id} AND container_no = '".$detail['container_no']."' GROUP BY 1 ORDER BY MAX(bundles_no) ASC")->queryAll())."<br>";
						}
						?>
					</td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; text-align: right;">
						<?php foreach($modDetails as $i => $detail){
							echo (!empty($detail['gross_weight'])?number_format($detail['gross_weight'],2):"")."<br>";
						} ?>
					</td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; text-align: right;">
						<?php foreach($modDetails as $i => $detail){
							echo (!empty($detail['volume'])? number_format($detail['volume'],4):"")."<br>";
						} ?>
					</td>
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: middle; text-align: right;">
						<?php foreach($modDetails as $i => $detail){
							echo (!empty($detail['nett_weight'])?number_format($detail['nett_weight'],2):"")."<br>";
						} ?>
					</td>
					<td style="padding: 5px; border-bottom: solid 1px transparent; vertical-align: top;">
						<table style="width: 100%; line-height: 1; font-weight: 500;">
							<tr>
								<td style="width: 60%;"><b>CONTAINER SIZE</b></td>
								<td style="">:</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-bottom: 8px;">&nbsp; 
									<?php
									$modContSize = Yii::$app->db->createCommand("SELECT container_size, COUNT(DISTINCT container_no) AS qty FROM t_packinglist_container WHERE t_packinglist_container.packinglist_id = {$model->packinglist_id} GROUP BY 1")->queryAll();
									if(count($modContSize)){
										foreach($modContSize as $i => $contsize){
											echo $contsize['container_size']."' HC x ".$contsize['qty']."<br>";
										}
									}
									?>
								</td>
							</tr>
							<tr>
								<td style=""><b>TOTAL QTY</b></td>
								<td style="">:</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-bottom: 8px;">&nbsp; <?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_pcs); ?></td>
							</tr>
							<tr>
								<td style=""><b>TOTAL <?= $satuanbesar ?></b></td>
								<td style="">:</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-bottom: 8px;">&nbsp; <?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bundles); ?></td>
							</tr>
							<tr>
								<td style=""><b>TOTAL GW (KGS)</b></td>
								<td style="">:</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-bottom: 8px;">&nbsp; <?= number_format($total_gross_weight,2); ?></td>
							</tr>
							<tr>
								<td style=""><b>TOTAL NW (KGS)</b></td>
								<td style="">:</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-bottom: 8px;">&nbsp; <?= number_format($total_nett_weight,2); ?></td>
							</tr>
							<tr>
								<td style=""><b>TOTAL MSMT (CBM)</b></td>
								<td style="">:</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-bottom: 8px;">&nbsp; <?= number_format($model->total_volume,4); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="border-top: solid 1px #000;  height: 0.5cm;">
					<td style="padding: 5px; border-right: solid 1px #000; text-align: right;"><b><center></center></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; text-align: right;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_pcs); ?></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; text-align: right;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_bundles); ?></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; text-align: right;"><b><?= number_format($total_gross_weight,2); ?></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; text-align: right;"><b><?= number_format($model->total_volume,4); ?></b></td>
					<td style="padding: 5px; border-right: solid 1px #000; text-align: right;"><b><?= number_format($total_nett_weight,2); ?></b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
                                    <tr style="height: 4cm; ">
					<td style="padding: 5px; border-right: solid 1px #000; vertical-align: top; line-height: 1.2; width: 15cm; text-align: center;">
						<br><b style="font-size: 1.4rem;">DESCRIPTION OF GOODS</b><br>
						<b style="font-size: 1.1rem;"><?= $si_gd_product ?></b><br>
						<b style="font-size: 1.1rem;"><?= str_replace("\n", "<br>", $si_gd_sizegrade); ?></b><br><br>
						<b style="font-size: 1.1rem;"><?= $si_gd_total; ?></b><br>
						<table style="width: 100%; text-align: left;  line-height: 1; margin-left: 5cm;" border="0">
							<?php if(count($si_gdrepeater)>0){
							foreach($si_gdrepeater as $label => $value){ ?>
							<tr>
								<td style="width: 3cm;"><b><?= $label ?></b></td>
								<td>: <?= $value; ?></td>
							</tr>
							<?php } } ?>
						</table>
					</td>
					<td style="padding: 3px; vertical-align: top;"><b>MARKS : <br>
							<center><?php echo (!empty($modInvoice->marks)?"<h4>".str_replace("\n", "<br>", $modInvoice->marks)."</h4>" :"<span style='font-size:0.8rem'>(TBA)</span>");?></b>
					</td>
				</tr>
				<tr style="">
					<td style="padding: 2px; border-right: solid 1px #000; vertical-align: bottom; width: 15cm; text-align: center; font-size: 0.85rem;">
						<span style=""><b><i><?= $si_gd_ket ?></i></b></span>
					</td>
					<td style="padding: 2px; vertical-align: bottom; font-size: 0.8rem;">
						PLEASE FAX BL DRAF TO : <b>024-6735588</b><br>
						PLEASE EMAIL BL DRAF TO : <b>info@ciptana.com</b><br>
					</td>
				</tr>
				<tr style="height:2.5cm; ">
					<td colspan="2" style="padding: 5px; border-top: solid 1px #000; vertical-align: top; line-height: 1.3; text-align: center;">
						<b style="font-size: 1.1rem;"><u>SPECIAL INSTRUCTION</u></b><br>
						<b style="font-size: 1.2rem; ">
							<span class="font-red-flamingo" style="margin-top: 10px;"><?= str_replace("\n", "<br>", $si_instruction); ?></span>
						</b>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: bottom; height: 2cm; border-top: solid 1px transparent;" colspan="2">
			<table style="width: 100%;">
				<tr>
					<td style="width:7cm;vertical-align: bottom; font-size: 0.9rem; padding:3px;">
						<?php
						echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
						echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
						?>
					</td>
					<td style="width:6cm;text-align: right; padding:3px;">
						<?php //echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 8cm; height:1.2cm;">'; ?>
						<?php echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/sertifikat_tanpa_CARB.png" alt="" class="logo-default" style="width: 8cm;">'; ?>
					</td>
                                        <td style="width:7cm"></td>
				</tr>
			</table>
		</td>
	</tr>        
</table>
<table style="width: 20cm; margin: 10px;" border:0;>
    <tr>
        <td style="font-size: 0.9rem; border: solid 1px transparent; vertical-align: top;text-align:right;">
                <span class="pull-right nomor-dokumen-qms" style="font-size: 0.8rem;">CWM-FK-PCH-02-0</span>
        </td>
    </tr>
</table>