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
//	$satuanbesar = "Crate";
//	$satuanbesar2 = "Crates";
	$satuanbesar = "Bdl";
	$satuanbesar2 = "Bundles";
}else{
	$satuanbesar = "Bdl";
	$satuanbesar2 = "Bundles";
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
if($model->bundle_partition == true){
	$tablewidth = "20";
}else{
	$tablewidth = "20";
}
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
$modContainer = Yii::$app->db->createCommand("SELECT container_no FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}'
												GROUP BY container_no ORDER BY container_no ASC")->queryAll();
$total_all_pcs = 0; $total_all_volume = 0; $total_all_gross=0; $total_all_nett=0;
foreach($modContainer as $ii => $container){
$modDetail = \app\models\TPackinglistContainer::find()->where(['packinglist_id'=>$model->packinglist_id,'container_no'=>$container['container_no']])->orderBy("packinglist_container_id ASC")->all();
$blankspace = 26;
?>
<table style="width: <?= $tablewidth ?>cm; margin: 10px;" border="1">
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
					<td style="text-align: center; vertical-align: top; padding: 5px; line-height: 1;">
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
								<td style="width: 2.8cm; vertical-align: top;"><b>Shipper</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?php echo !empty($model->shipper)?str_replace("\n", "<br>", $model->shipper):"";?>
								</td>
							</tr>
							<?php if(!empty($model->notify_party)){ ?>
							<tr>
								<td style="vertical-align: top;"><b>Applicant</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo strtoupper( $model->cust->cust_an_nama )."<br>".strtoupper( $model->cust->cust_an_alamat );?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Notify Party</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo strtoupper( $model->notifyParty->cust_an_nama )."<br>".strtoupper( $model->notifyParty->cust_an_alamat );?></td>
							</tr>
							<?php }else{ ?>
							<tr>
								<td style="vertical-align: top;"><b>Shipment To</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo strtoupper( $model->cust->cust_an_nama )."<br>".strtoupper( $model->cust->cust_an_alamat );?></td>
							</tr>
							<?php } ?>
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>Port of Loading</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->port_of_loading)?$model->port_of_loading:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>Final Destination</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->final_destination)?$model->final_destination:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>Vessel</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->vessel)?$model->vessel:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<?php if(!empty($model->mother_vessel)){ ?>
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>Mother Vessel</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( $model->mother_vessel );?></td>
							</tr>
							<?php } ?>
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>ETD</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->etd)?app\components\DeltaFormatter::formatDateTimeEn($model->etd):"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>ETA</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->eta)?app\components\DeltaFormatter::formatDateTimeEn($model->eta):"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>Hs Code</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo (!empty($model->hs_code)?$model->hs_code:"<span style='font-size:0.8rem'>(TBA)</span>");?></td>
							</tr>
<!--							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>Harvesting Area</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php // echo (!empty($model->harvesting_area)?$model->harvesting_area:"<span style='font-size:0.8rem'>(TBA)</span>");?></td>
							</tr>-->
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>Origin</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->origin)?$model->origin:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
						</table>
					</td>
					<td style="width: 50%; vertical-align: top; padding-left: 15px;">
						<table style="width: 100%;">
							<tr>
								<td style="width: 3cm; vertical-align: top;"><b>Packing list No.</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><b><?php echo (!empty($model->nomor)?$model->nomor:"<span style='font-size:0.8rem'>(TBA)</span>");?></b></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Packing list Date</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( app\components\DeltaFormatter::formatDateTimeEn($model->tanggal_packinglistexim) );?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Contract No.</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><b><?php echo $modOpEx->nomor_kontrak;?></b></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>SVLK No.</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo (!empty($model->svlk_no)?$model->svlk_no:"<span style='font-size:0.8rem'>(TBA)</span>");?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>V-Legal No.</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo (!empty($model->vlegal_no)?$model->vlegal_no:"<span style='font-size:0.8rem'>(TBA)</span>");?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Statistic Code</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->static_product_code)?$model->static_product_code:"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Goods Description</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;"><?php echo strtoupper( (!empty($model->goods_description)?str_replace("\n", "<br>", $model->goods_description):"<span style='font-size:0.8rem'>(TBA)</span>") );?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Cont Seq. / Size</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;">
									<b><?= $modDetail[0]->container_no ?> of <?= $model->total_container ?> Container's / <?= (!empty($modDetail[0]->container_size)?$modDetail[0]->container_size." Feet":"<span style='font-size:0.8rem'>(TBA)</span>"); ?></b>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Cont No. / Seal</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?= (!empty($modDetail[0]->container_kode)?$modDetail[0]->container_kode:"<span style='font-size:0.8rem'>(TBA)</span>") ?>&nbsp;/&nbsp; 
									<?= (!empty($modDetail[0]->seal_no)?$modDetail[0]->seal_no:"<span style='font-size:0.8rem'>(TBA)</span>") ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Lot Code</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?= (!empty($modDetail[0]->lot_code)?"<b>".$modDetail[0]->lot_code."</b>":" - ") ?> 
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Gross / Nett Weight</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?= (!empty($modDetail[0]->gross_weight)?app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[0]->gross_weight)." Kg(s)":"<span style='font-size:0.8rem'>(TBA)</span>") ?> / 
									<?= (!empty($modDetail[0]->nett_weight)?app\components\DeltaFormatter::formatNumberForUserFloat($modDetail[0]->nett_weight)." Kg(s)":"<span style='font-size:0.8rem'>(TBA)</span>") ?> 
								</td>
							</tr>
							<?php if(!empty($model->notes)){ ?>
							<tr>
								<td style="vertical-align: top;"><b>Notes</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
									<td style="vertical-align: top; line-height: 1.2"><?= !empty($model->notes)?str_replace("\n", "<br>", $model->notes):""; ?></td>
							</tr>
							<?php } ?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<?php
					if($model->bundle_partition == true){
						$modpartition = Yii::$app->db->createCommand("SELECT partition_kode, max(partition_kode) FROM t_packinglist_container WHERE packinglist_id = {$model->packinglist_id} AND container_no = '".$container['container_no']."' GROUP BY 1 ORDER BY MAX(partition_kode) ASC")->queryAll();
						$modthick = Yii::$app->db->createCommand("SELECT thick, max(thick) FROM t_packinglist_container WHERE packinglist_id = {$model->packinglist_id} AND container_no = '".$container['container_no']."' GROUP BY 1 ORDER BY MAX(thick) ASC")->queryAll();
						$modwidth = Yii::$app->db->createCommand("SELECT width, max(width) FROM t_packinglist_container WHERE packinglist_id = {$model->packinglist_id} AND container_no = '".$container['container_no']."' GROUP BY 1 ORDER BY MAX(width) ASC")->queryAll();
						$modlength = Yii::$app->db->createCommand("SELECT length, max(length) FROM t_packinglist_container WHERE packinglist_id = {$model->packinglist_id} AND container_no = '".$container['container_no']."' GROUP BY 1 ORDER BY MAX(length) ASC")->queryAll();
						$modunit = Yii::$app->db->createCommand("SELECT thick_unit,width_unit,length_unit FROM t_packinglist_container WHERE packinglist_id = {$model->packinglist_id} AND container_no = '".$container['container_no']."' GROUP BY 1,2,3")->queryOne();
						$modbundles = Yii::$app->db->createCommand("SELECT bundles_no, max(bundles_no) FROM t_packinglist_container WHERE packinglist_id = {$model->packinglist_id} AND container_no = '".$container['container_no']."' GROUP BY 1 ORDER BY MAX(bundles_no) ASC")->queryAll();
						$total_bundles = count($modbundles);
						$total_partition = count($modpartition);
						$params_thick = [];
						foreach($modthick as $j => $asd){
							$params_thick[] = $asd['thick'];
						}
						$params_width = [];
						foreach($modwidth as $k => $asd){
							$params_width[] = $asd['width'];
						}
						$params_length = [];
						foreach($modlength as $l => $asd){
							$params_length[] = $asd['length'];
						}
						$params_thick_unit = $modunit['thick_unit'];
						$params_width_unit = $modunit['width_unit'];
						$params_length_unit = $modunit['length_unit'];

						$column = ""; $thick_span='rowspan="2"';$width_span='rowspan="2"';$length_span='rowspan="2"';
						$total_thick = count($params_thick);
						$html_thick = "";
						if($total_thick>1){
							foreach($params_thick as $m => $thick){
								$html_thick .= '<th style="width: 1cm; border-right: solid 1px #000;" class="kolom-pcs font-purple"><center>'.$thick.'</center></th>';
							}
							$thick_span = 'colspan="'.$total_thick.'"';
						}
						$total_width = count($params_width);
						$html_width = "";
						if($total_width>1){
							foreach($params_width as $n => $width){
								$html_width .= '<th style="width: 1cm; border-right: solid 1px #000;" class="kolom-pcs font-purple"><center>'.$width.'</center></th>';
							}
							$width_span = 'colspan="'.$total_width.'"';
						}
						$total_length = count($params_length);
						$html_length = "";
						if($total_length>1){
							foreach($params_length as $o => $length){
								$html_length .= '<th style="width: 1cm; border-right: solid 1px #000;" class="kolom-pcs font-purple"><center>'.$length.'</center></th>';
							}
							$length_span = 'colspan="'.$total_length.'"';
						}
						$colspan_footer = 1 + $total_thick + $total_width + $total_length;
						if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
							$column = '<th rowspan="2" style="width: 100px; ">Wood<br>Type</th>'
									. '<th rowspan="2" style="width: 120px; ">Glue</th>';
							$colspan_footer += 1;
						}else if($model->jenis_produk == "Sawntimber"){
							$column = '<th rowspan="2" style="width: 150px; ">Condition</th>';
						}else if($model->jenis_produk == "Moulding"){
							$column = '<th rowspan="2" style="width: 100px; ">Wood<br>Type</th>'
									. '<th rowspan="2" style="width: 175px; ">Profil</th>';
							$colspan_footer += 1;
						}
						if($total_partition > 1){
							$colspan_footer += 1;
						}
				?>
						<tr style="border-bottom: solid 1px #000;">
							<td rowspan="3" style="width:  0.5cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center><?= $satuanbesar ?></center></b></td>
							<?php if($total_partition > 1){ ?>
							<th rowspan="3" style="width: 0.5cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><center>Part</center></th>
							<?php } ?>
							<td rowspan="3" style="padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Grade</center></b></td>
							<td colspan="<?= $total_thick + $total_width + $total_length ?>" style="border-right: solid 1px #000;"><b><center>Size</center></b></td>
							<td rowspan="2" colspan="3" style="vertical-align: middle;"><b><center>Total</center></b></td>
						</tr>
						<tr style="border-bottom: solid 1px #000;">
							<th <?= $thick_span ?> style="width: 1cm; border-right: solid 1px #000;" class="kolom-pcs"><center>Thick (<?= $params_thick_unit ?>)</center></th>
							<th  <?= $width_span ?> style="width: 1cm; border-right: solid 1px #000;" class="kolom-pcs"><center>Width (<?= $params_width_unit ?>)</center></th>
							<th  <?= $length_span ?> style="width: 1cm; border-right: solid 1px #000;" class="kolom-pcs"><center>Length (<?= $params_length_unit ?>)</center></th>
						</tr>
						<tr>
							<?= $html_thick.$html_width.$html_length; ?>
							<td style="width: 1.5cm; border-right: solid 1px #000;"><center><b>Pcs</b></center></td>
							<td style="width: 1.5cm; "><center><b>M<sup>3</sup></b></center></td>
						</tr>
						<?php
						$total_pcs=0;$total_volume=0;
						$modAlias = Yii::$app->db->createCommand("SELECT * FROM t_alias WHERE reff_no = '{$model->kode}'")->queryAll();
						if(!empty($modAlias)){
							$groupings = $modAlias;
						}else{
							if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
								$selekgroup = "glue";
							}else{
								$selekgroup = "profil_kayu";
							}
							$groupings = Yii::$app->db->createCommand("SELECT $selekgroup AS value_original FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}' GROUP BY 1")->queryAll();
							foreach($groupings as $z => $ert){
								$groupings[$z]['alias_name'] = $selekgroup;
							}
						}
						foreach($groupings as $igroup => $grouping){
							$subtotal_bundle=0; $subtotal_dimensi=[]; $subtotal_pcs=0; $subtotal_volume=0;
							$sql = "SELECT container_no, bundles_no, partition_kode, grade, jenis_kayu, glue, profil_kayu, kondisi_kayu
									FROM t_packinglist_container
									WHERE packinglist_id = ".$model->packinglist_id." AND container_no = ".$container['container_no']." AND {$grouping['alias_name']} = '{$grouping['value_original']}'
									GROUP BY 1,2,3,4,5,6,7,8
									ORDER BY 1,2,3 ASC";
							$modDetails = Yii::$app->db->createCommand($sql)->queryAll();
							$blankspace = $blankspace - count($modDetails);
							if(count($params_thick)>1){
								foreach($params_thick as $vvv => $thick){
									$subtotal_dimensi[$thick] = 0;
								}
							}
							if(count($params_width)>1){
								foreach($params_width as $vvv => $width){
									$subtotal_dimensi[$width] = 0;
								}
							}
							if(count($params_length)>1){
								foreach($params_length as $vvv => $length){
									$subtotal_dimensi[$length] = 0;
								}
							}
							if(count($modDetails)>0){
								if(!empty($modAlias)){
									echo '<tr style="border: solid 1px #000; border-right: solid 1px transparent; font-weight:600" class="text-align-center">';
									echo	'<td colspan='.($colspan_footer+2).'>'.$grouping['value_alias'].'</td>';
									echo '</tr>';
								}
								foreach($modDetails as $v => $detail){
									$sub_pcs = 0; $sub_volume = 0;
									$produkorder =""; $garing="";
									if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
	//									$produkorder = $model->jenis_produk;
	//									$garing = "/";
									}
									if(!empty($detail['jenis_kayu'])){
	//									$produkorder .= $garing.$detail['jenis_kayu'];
									}
									if(!empty($detail['grade'])){
	//									$produkorder .= "/".$detail['grade'];
										$produkorder .= $detail['grade'];
									}
									if(!empty($detail['glue'])){
	//									$produkorder .= "/".$detail['glue'];
									}
									if(!empty($detail['profil_kayu'])){
	//									$produkorder .= "/".$detail['profil_kayu'];
									}
									if(!empty($detail['kondisi_kayu'])){
	//									$produkorder .= "/".$detail['kondisi_kayu'];
									}
									echo '<tr style="border: solid 1px #000; border-right: solid 1px transparent;">';
									echo		"<td style='border-right: solid 1px #000; text-align: center; padding: 3px; vertical-align:middle'>".($detail['bundles_no'])."</td>";
									if($total_partition > 1){
										echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px; vertical-align:middle'>".$detail['partition_kode']."</td>";
									}
									echo		"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>". (($colspan_footer>12)?$detail['grade']:$produkorder)."</td>";
									foreach($params_thick as $vv => $thick){
										if(count($params_thick)>1){
											$sql = "SELECT pcs, ROUND( volume::numeric, 4) AS volume FROM t_packinglist_container
													WHERE packinglist_id = ".$model->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
														".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
														".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
														".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
														".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
														AND thick = {$thick} AND width = {$params_width[0]} AND length = {$params_length[0]}";
											$hmm = Yii::$app->db->createCommand($sql)->queryOne();
											echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".$hmm['pcs']."</td>";
											$sub_pcs += $hmm['pcs']; $sub_volume += $hmm['volume'];
											$subtotal_dimensi[$thick] += $hmm['pcs'];
										}else{
											echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;' class='font-purple'><b>".$thick."</b></td>";
										}
									}
									foreach($params_width as $vv => $width){
										if(count($params_width)>1){
											$sql = "SELECT pcs, ROUND( volume::numeric, 4) AS volume FROM t_packinglist_container
													WHERE packinglist_id = ".$model->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
														".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
														".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
														".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
														".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
														AND thick = {$params_thick[0]} AND width = {$width} AND length = {$params_length[0]}";
											$hmm = Yii::$app->db->createCommand($sql)->queryOne();
											echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".$hmm['pcs']."</td>";
											$sub_pcs += $hmm['pcs']; $sub_volume += $hmm['volume'];
											$subtotal_dimensi[$width] += $hmm['pcs'];
										}else{
											echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;' class='font-purple'><b>".$width."</b></td>";
										}
									}
									foreach($params_length as $vv => $length){
										if(count($params_length)>1){
											$sql = "SELECT pcs, ROUND( volume::numeric, 4) AS volume FROM t_packinglist_container
													WHERE packinglist_id = ".$model->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
														".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
														".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
														".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
														".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
														AND thick = {$params_thick[0]} AND width = {$params_width[0]} AND length = {$length}";
											$hmm = Yii::$app->db->createCommand($sql)->queryOne();
											echo "<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".($hmm['pcs'])."</td>";
											$sub_pcs += $hmm['pcs']; $sub_volume += $hmm['volume'];
											$subtotal_dimensi[$length] += $hmm['pcs'];
										}else{
											echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".$length."</td>";
										}
									}
									echo		"<td style='border-right: solid 1px #000; text-align: right; padding: 3px;'>".($sub_pcs)."</td>";
									echo		"<td style='text-align: right; padding: 3px;'>".(number_format($sub_volume,4))."</td>";
									echo "</tr>";
									$subtotal_bundle = $subtotal_bundle+1;
									$subtotal_pcs += $sub_pcs; $subtotal_volume += number_format($sub_volume,4);
									$total_pcs += $sub_pcs;  $total_volume += number_format($sub_volume,4);
								}
//								if(!empty($modAlias)){
								$subtotal_bundle = $subtotal_bundle / $total_partition;
								echo '<tr style="border: solid 1px #000; border-right: solid 1px transparent; font-weight:600" class="text-align-center">';
									if($total_partition > 1){
										echo "<td style='border-right: solid 1px #000; padding: 2px;' colspan=2><b>".$subtotal_bundle." Bdl</b></td>";
									}else{
										echo "<td style='border-right: solid 1px #000; padding: 2px;' ><b>".$subtotal_bundle."</b></td>";
									}
										echo "<td style='border-right: solid 1px #000; padding: 2px;' colspan=3></td>";
										if(count($subtotal_dimensi)>0){
											foreach($subtotal_dimensi as $x => $cvb){
												echo "<td style='border-right: solid 1px #000; padding: 2px;'><b>".$cvb."</b></td>";
											}
										}
										echo "<td style='border-right: solid 1px #000; text-align:right; padding: 2px;'><b>".$subtotal_pcs."</b></td>";
										echo "<td style='text-align:right; padding: 2px;'><b>".number_format($subtotal_volume,4)."</b></td>";
								echo '</tr>';
//								}
							}
						}
						?>
								<tr style="">
									<td style="border-right: solid 1px #000; text-align: right; padding: 3px;" colspan="<?= $colspan_footer ?>"><b>Total</b></td>
									<td style="border-right: solid 1px #000; text-align: right; padding: 3px;"><b><?= $total_pcs ?></b></td>
									<td style="text-align: right; padding: 3px;"><b><?= number_format($total_volume,4) ?></b></td>
								</tr>
						<?php }else{ ?>
						<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
							<td rowspan="2" style="width: 0.5cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center><?= $satuanbesar ?></center></b></td>
							<td rowspan="2" style="padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Grade</center></b></td>
							<?php if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){ ?>
							<td rowspan="2" style="padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Glue</center></b></td>
							<?php } ?>
							<td colspan="3" style="border-right: solid 1px #000;"><b><center>Size</center></b></td>
							<td colspan="3" style=""><b><center>Total</center></b></td>
						</tr>
						<tr style="border-bottom: solid 1px #000;">
							<td style="width: 2.5cm; border-right: solid 1px #000;"><center><b>Thickness</b> (<?= $modDetail[0]->thick_unit ?>)</center></td>
							<td style="width: 2.5cm; border-right: solid 1px #000;"><center><b>Width</b> (<?= $modDetail[0]->width_unit ?>)</center></td>
							<td style="width: 2.5cm; border-right: solid 1px #000;"><center><b>Length</b> (<?= $modDetail[0]->length_unit ?>)</center></td>
							<td style="width: 2.5cm; border-right: solid 1px #000;"><center><b>Pcs</b></center></td>
							<td style="width: 2.5cm; "><center><b>M<sup>3</sup></b></center></td>
						</tr>
						<?php
                        $approval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'parameter1'=>'ALIAS PACKINGLIST']);
						$total_pcs = 0; $total_volume = 0; $total_bundles = 0;
						$blankspace = $blankspace - count($modDetail);
						$modAlias = Yii::$app->db->createCommand("SELECT * FROM t_alias WHERE reff_no = '{$model->kode}' ORDER BY alias_id ASC")->queryAll();
						if(!empty($modAlias) && ($model->jenis_produk == "Moulding")){
                            $modAlias2 = Yii::$app->db->createCommand("SELECT * FROM t_alias WHERE reff_no = '{$model->kode}' AND alias_name='profil_kayu' ORDER BY alias_id ASC")->queryAll();
                            $groupings = $modAlias2;
						}else{
//                            if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
//								$selekgroup = "glue";
//							}else{
//								$selekgroup = "profil_kayu";
//							}
//							$groupings = Yii::$app->db->createCommand("SELECT $selekgroup AS value_original FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}' GROUP BY 1 ORDER BY 1 DESC")->queryAll();
//							foreach($groupings as $z => $ert){
//								$groupings[$z]['alias_name'] = $selekgroup;
//							}
//                            echo "<pre>";
//                            print_r("SELECT $selekgroup AS value_original FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}' GROUP BY 1 ORDER BY 1 DESC");
//                            exit;
                            // PL 175-2019 tidak urut 
//                            if($model->packinglist_id == 237){
                                $groupings = Yii::$app->db->createCommand("SELECT container_no FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}' AND container_no='{$container['container_no']}' GROUP BY 1 ORDER BY 1 DESC")->queryAll();
//                            }
						}
						foreach($groupings as $igroup => $grouping){
                            if(!empty($modAlias) && ($model->jenis_produk == "Moulding")){
                                $modDetails = \app\models\TPackinglistContainer::find()
                                            ->where(['packinglist_id'=>$model->packinglist_id,'container_no'=>$container['container_no'],$grouping['alias_name']=>$grouping['value_original']])
                                            ->orderBy("packinglist_container_id ASC")->all();
                            }else{
                                $modDetails = \app\models\TPackinglistContainer::find()
                                                ->where(['packinglist_id'=>$model->packinglist_id,'container_no'=>$container['container_no']])
                                                ->orderBy("packinglist_container_id ASC")->all();
                            }
							if(count($modDetails)>0){
								if(!empty($modAlias) && $model->jenis_produk == "Moulding"){
									echo '<tr style="border: solid 1px #000; border-right: solid 1px transparent; font-weight:600" class="text-align-center">';
									echo	'<td colspan=6>'.(($approval->status == \app\models\TApproval::STATUS_APPROVED)?$grouping['value_alias']:$grouping['value_original']).'</td>';
									echo '</tr>';
								}
								$subtotal_bundles = 0; $subtotal_pcs = 0; $subtotal_volume=0;
                                $grade = ""; $glue = "";
								foreach($modDetails as $i => $detail){
									if(($i+1) != $subtotal_bundles){
										$subtotal_bundles += 1;
									}
									$subtotal_pcs += $detail->pcs;
									$subtotal_volume += number_format($detail->volume,4);
									$total_pcs += $detail->pcs;
									$total_volume += number_format($detail->volume,4);
									$total_bundles = $detail->bundles_no;
                                    
                                    $garing=""; $colspan = "4";
                                    if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
                                        $colspan = "5";
                                    }
                                    if(!empty($detail['jenis_kayu'])){

                                    }
                                    if(!empty($detail['grade'])){
                                        $aliasgrade = app\models\TAlias::findOne(['reff_no'=>$model->kode,'alias_name'=>'grade','value_original'=>$detail['grade']]);
                                        if(!empty($aliasgrade)&&($approval->status == \app\models\TApproval::STATUS_APPROVED)){
                                            $grade = $aliasgrade->value_alias;
                                        }else{
                                            $grade = $detail['grade'];
                                        }
                                    }
                                    if(!empty($detail['glue'])){
                                        $aliasglue = app\models\TAlias::findOne(['reff_no'=>$model->kode,'alias_name'=>'glue','value_original'=>$detail['glue']]);
                                        if(!empty($aliasglue)&&($approval->status == \app\models\TApproval::STATUS_APPROVED)){
                                            $glue = $aliasglue->value_alias;
                                        }else{
                                            $glue = $detail['glue'];
                                        }
                                    }
                                    if(!empty($detail['profil_kayu'])){

                                    }
                                    if(!empty($detail['kondisi_kayu'])){

                                    }
								?>
								<tr style="border: solid 1px #000; border-right: solid 1px transparent;">
									<td style="border-right: solid 1px #000; text-align: center; padding: 2px;"><?= $detail->bundles_no ?></td>
									<td style="border-right: solid 1px #000; text-align: center; padding: 2px;"><?= $grade ?></td>
									<?php if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){ ?>
									<td style="border-right: solid 1px #000; text-align: center; padding: 2px;"><?= $glue ?> </td>
									<?php } ?>
									<td style="border-right: solid 1px #000; text-align: center; padding: 2px;"><?= $detail->thick ?></td>
									<td style="border-right: solid 1px #000; text-align: center; padding: 2px;"><?= $detail->width ?></td>
									<td style="border-right: solid 1px #000; text-align: center; padding: 2px;"><?= $detail->length ?></td>
									<td style="border-right: solid 1px #000; text-align: right; padding: 2px;"><?= $detail->pcs ?></td>
									<td style="text-align: right; padding: 2px;"><?= ($_GET['caraprint'] == "EXCEL")? app\components\DeltaFormatter::formatNumberForUserFloat($detail->volume) : number_format($detail->volume,4); ?></td>
								</tr>
								<?php if((($i+1) == count($modDetails)) && (!empty($modAlias))){ ?>
									<tr style="border-top: solid 1px #000; background-color: #F1F4F7;">
										<td class="text-align-center" style="padding: 1px; border-right: solid 1px #000;"><b><!--<?= $subtotal_bundles ?>--></b></td>
										<td colspan="<?= $colspan ?>" class="text-align-right" style="padding: 1px; border-right: solid 1px #000;"><b>Subtotal </b>&nbsp;</td>
										<td class="text-align-center" style="padding: 1px; border-right: solid 1px #000; text-align: right;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_pcs) ?></b></td>
										<td class="text-align-center" style="padding: 1px; text-align: right;"><b><?= ($_GET['caraprint'] == "EXCEL")? app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_volume) : number_format($subtotal_volume,4); ?></b></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					<tr style="border-top: solid 1px #000; background-color: #F1F4F7;">
						<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000;"><b><?= $total_bundles ?></b></td>
						<td colspan="<?= $colspan ?>" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total </b>&nbsp;</td>
						<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000; text-align: right;"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_pcs) ?></b></td>
						<td class="text-align-center" style="padding: 5px;  text-align: right;"><b><?= ($_GET['caraprint'] == "EXCEL")? app\components\DeltaFormatter::formatNumberForUserFloat($total_volume) : number_format($total_volume,4); ?></b></td>
					</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<?php
	$total_all_pcs += $total_pcs;
	$total_all_volume += number_format($total_volume,4);
	$total_all_gross += $modDetail[0]->gross_weight;
	$total_all_nett += $modDetail[0]->nett_weight;
	?>
		
	<tr style="border: solid 1px #000; border-top: solid 1px #000; width:<?= $tablewidth ?>cm; bottom: 10px;">
		<td colspan="3" style="">
			<table style="width: 100%; font-size: 1.1rem;" border="0">
				<?php 
					if( count($modContainer) == ($ii+1) ){ 
					$blankspace = $blankspace - 4;
				?>	
				<tr style="height: 0.7cm;  ">
					<td style="vertical-align: bottom; border-bottom: solid 1px transparent; text-align: left;">
						<b style="font-size: 1.3rem;">&nbsp; Summary :</b>
					</td>
					<td style="vertical-align: bottom; width: 4cm; text-align: center;"><?= $modCompany->name ?></td>
				</tr>
				<tr>
					<td style=" height: 2cm; padding-left: 15px;">
						<table style="width: 100%;" border="0">
							<tr>
								<td style="width:2.75cm">Total PCS</td>
								<td style="width:0.5cm">:</td>
								<td style="width:1.25cm; text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_all_pcs) ?></td>
								<td style="text-align: left;">&nbsp; &nbsp;Pcs(s)</td>
							</tr>
							<tr>
								<td>Total Volume</td>
								<td>:</td>
								<td style="text-align: right;"><?= number_format($total_all_volume,4) ?></td>
								<td style="text-align: left;">&nbsp; &nbsp;M<sup>3</sup></td>
							</tr>
							<tr>
								<td>Total Gross Weight</td>
								<td>:</td>
								<td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_all_gross) ?></td>
								<td style="text-align: left;">&nbsp; &nbsp;Kg(s)</td>
							</tr>
							<tr>
								<td>Total Nett Weight</td>
								<td>:</td>
								<td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($total_all_nett) ?></td>
								<td style="text-align: left;">&nbsp; &nbsp;Kg(s)</td>
							</tr>
							<tr>
								<td>Total <?= $satuanbesar2 ?></td>
								<td>:</td>
								<td style="text-align: right;"><?= $model->total_bundles ?></td>
								<td style="text-align: left;">&nbsp; &nbsp;<?= $satuanbesar2 ?></td>
							</tr>
						</table>
					</td>
					<td style="vertical-align: bottom; line-height: 1;  text-align: center;">
						<?php
						if(!empty($model->disetujui_finance)){
							echo "<span style='font-size:0.9rem'><b><u> ". $model->disetujuiFinance0->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Head of Finance Accounting </span>";
						}else{
							echo "<span style='font-size:0.9rem'><b><u> <i>Unknown</i> </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Head of Finance Accounting </span>";
						}
						?>
					</td>
				</tr>
				<?php } ?>
				<tr><td style="height: <?= (0.5*$blankspace) ?>cm">&nbsp;</td></tr>
				<tr>
					<td style="vertical-align: bottom; height: 1.5cm;" colspan="2">
						<table style="width: 100%;">
							<tr>
								<td style="vertical-align: bottom; font-size: 0.9rem; padding:3px;">
									<?php
									echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
									echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
									?>
								</td>
								<td style="text-align: right; padding:3px;">
									<?php echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 8cm;">'; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php if(count($modContainer)>1){ ?>
<p style="page-break-after: always;">&nbsp;</p>
<?php } ?>
<?php } ?>