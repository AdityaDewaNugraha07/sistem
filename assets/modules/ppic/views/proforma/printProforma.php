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
<?php
if($model->bundle_partition == true){
	$tablewidth = "28";
}else{
	$tablewidth = "20";
}
$modContainer = Yii::$app->db->createCommand("SELECT container_no FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}'
												GROUP BY container_no ORDER BY container_no ASC")->queryAll();
foreach($modContainer as $ii => $container){
$modDetail = \app\models\TPackinglistContainer::find()->where(['packinglist_id'=>$model->packinglist_id,'container_no'=>$container['container_no']])->orderBy("packinglist_container_id ASC")->all();
?>
<table style="width: <?= $tablewidth ?>cm; margin: 10px; height: 10cm;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="text-align: left; vertical-align: middle; padding: 0px; width: 5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
						<span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
						<?= $model->jenis_produk ?>
					</td>
					<td style="width: 5cm; height: 1cm; vertical-align: top; padding: 10px;">
						<table>
							<tr>
								<td style="width:1.5cm;"><b>Kode</b></td>
								<td>: &nbsp; <?= $kode; ?></td>
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
	<tr>
		<td colspan="3" style="padding: 8px; background-color: #F1F4F7;">
			<table>
				<tr>
					<td style="width: 50%; vertical-align: top; padding-left: 10px;">
						<table>
							<tr>
								<td style="width: 2cm; vertical-align: top;"><b>Customer</b></td>
								<td style="width: 0.5cm; vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?php
										echo $model->cust->cust_an_nama." <br>";
										echo $model->cust->cust_an_alamat;
									?>
								</td>
							</tr>
							
						</table>
					</td>
					<td style="width: 10%;">&nbsp;</td>
					<td style="width: 40%; vertical-align: top; padding-left: 10px;">
						<table>
							<tr>
								<td style="width: 2.5cm; vertical-align: top;"><b>Contract No.</b></td>
								<td style="width: 0.5cm; vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?= $modOpEx->nomor_kontrak; ?></td>
							</tr>
								<td style="vertical-align: top;"><b>Container No.</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;">
									<?= $modDetail[0]->container_no ?> of <?= $model->total_container ?> Cont(s) 
									<?= !empty($modDetail[0]->lot_code)?" / <b>".$modDetail[0]->lot_code."<b/>":""; ?>
								</td>
							<tr>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Container Size</b></td>
								<td style="vertical-align: top;"><b>:</b></td>
								<td style="vertical-align: top;"><?= $modDetail[0]->container_size ?></td>
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
				<?php 
					$totalpcs = 0; $totalvolume = 0;
					if($model->bundle_partition == true){
						$sql = "SELECT container_no, bundles_no, partition_kode, grade, jenis_kayu, glue, profil_kayu, kondisi_kayu
								FROM t_packinglist_container
								WHERE packinglist_id = ".$model->packinglist_id." AND container_no = ".$container['container_no']."
								GROUP BY 1,2,3,4,5,6,7,8
								ORDER BY 1,2,3 ASC";
						$modDetails = Yii::$app->db->createCommand($sql)->queryAll();

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
							<td rowspan="3" style="width: 1cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Bundles</center></b></td>
							<?php if($total_partition > 1){ ?>
							<th rowspan="3" style="width: 1cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><center>Part</center></th>
							<?php } ?>
							<td rowspan="3" style="padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Produk</center></b></td>
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
						$subtotal_bundle=0; $subtotal_dimensi=[]; $subtotal_pcs=0; $subtotal_volume=0;
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
							foreach($modDetails as $v => $detail){
								$subtot_pcs = 0; $subtot_volume = 0;
								$produkorder =""; $garing="";
								if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
									$produkorder = $model->jenis_produk;
									$garing = "/";
								}
								if(!empty($detail['jenis_kayu'])){
									$produkorder .= $garing.$detail['jenis_kayu'];
								}
								if(!empty($detail['grade'])){
									$produkorder .= "/".$detail['grade'];
								}
								if(!empty($detail['glue'])){
									$produkorder .= "/".$detail['glue'];
								}
								if(!empty($detail['profil_kayu'])){
									$produkorder .= "/".$detail['profil_kayu'];
								}
								if(!empty($detail['kondisi_kayu'])){
									$produkorder .= "/".$detail['kondisi_kayu'];
								}
								echo '<tr style="border: solid 1px #000; border-right: solid 1px transparent;">';
								echo		"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".($detail['bundles_no'])."</td>";
								if($total_partition > 1){
									echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".$detail['partition_kode']."</td>";
								}
								echo		"<td style='border-right: solid 1px #000; padding: 3px;'>".$produkorder."</td>";
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
										$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
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
										$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
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
										echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".(($hmm['pcs'])?$hmm['pcs']:'-')."</td>";
										$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
										$subtotal_dimensi[$length] += $hmm['pcs'];
									}else{
										echo	"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".$length."</td>";
									}
								}
								echo		"<td style='border-right: solid 1px #000; text-align: center; padding: 3px;'>".($subtot_pcs)."</td>";
								echo		"<td style='text-align: center; padding: 3px;'>".(number_format($subtot_volume,4))."</td>";
								echo "</tr>";
								$subtotal_bundle = $subtotal_bundle+1;
								$subtotal_pcs += $subtot_pcs; $subtotal_volume += number_format($subtot_volume,4);
							}
							
							$subtotal_bundle = $subtotal_bundle / $total_partition;
							echo '<tr style="border: solid 1px #000; border-right: solid 1px transparent; border-bottom: solid 1px transparent; font-weight:600" class="text-align-center">';
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
									echo "<td style='border-right: solid 1px #000; text-align:center; padding: 2px;'><b>".$subtotal_pcs."</b></td>";
									echo "<td style='text-align:center; padding: 2px; '><b>".number_format($subtotal_volume,4)."</b></td>";
							echo '</tr>';
						}
					}else{?>
						<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
							<td rowspan="2" style="width: 1cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Bundles</center></b></td>
							<td rowspan="2" style="padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle;"><b><center>Produk</center></b></td>
							<td colspan="3" style="border-right: solid 1px #000;"><b><center>Size</center></b></td>
							<td colspan="3" style=""><b><center>Total</center></b></td>
						</tr>
						<tr style="border-bottom: solid 1px #000;">
							<td style="width: 2cm; border-right: solid 1px #000;"><center><b>Thick</b> (<?= $modDetail[0]->thick_unit ?>)</center></td>
							<td style="width: 2cm; border-right: solid 1px #000;"><center><b>Width</b> (<?= $modDetail[0]->width_unit ?>)</center></td>
							<td style="width: 2cm; border-right: solid 1px #000;"><center><b>Length</b> (<?= $modDetail[0]->length_unit ?>)</center></td>
							<td style="width: 2cm; border-right: solid 1px #000;"><center><b>Pcs</b></center></td>
							<td style="width: 2cm; "><center><b>M<sup>3</sup></b></center></td>
						</tr>
				<?php
						foreach($modDetail as $i => $detail){
							$totalpcs += $detail->pcs;
							$totalvolume += number_format($detail->volume,4);
				?>
							<tr style="border: solid 1px #000; border-right: solid 1px transparent;">
								<td style="border-right: solid 1px #000; text-align: center; padding: 3px;"><?= $detail['bundles_no'] ?></td>
								<td style="border-right: solid 1px #000; padding: 3px;">
									<?php

									$produkorder =""; $garing="";
									if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
										$produkorder = $model->jenis_produk;
										$garing = "/";
									}
									if(!empty($detail['jenis_kayu'])){
										$produkorder .= $garing.$detail['jenis_kayu'];
									}
									if(!empty($detail['grade'])){
										$produkorder .= "/".$detail['grade'];
									}
									if(!empty($detail['glue'])){
										$produkorder .= "/".$detail['glue'];
									}
									if(!empty($detail['profil_kayu'])){
										$produkorder .= "/".$detail['profil_kayu'];
									}
									if(!empty($detail['kondisi_kayu'])){
										$produkorder .= "/".$detail['kondisi_kayu'];
									}
									echo $produkorder;
									?>
								</td>
								<td style="border-right: solid 1px #000; text-align: center; padding: 3px;"><?= $detail->thick ?></td>
								<td style="border-right: solid 1px #000; text-align: center; padding: 3px;"><?= $detail->width ?></td>
								<td style="border-right: solid 1px #000; text-align: center; padding: 3px;"><?= $detail->length ?></td>
								<td style="border-right: solid 1px #000; text-align: center; padding: 3px;"><?= $detail->pcs ?></td>
								<td style="text-align: center; padding: 3px;"><?= ($_GET['caraprint'] == "EXCEL")? app\components\DeltaFormatter::formatNumberForUserFloat($detail->volume) : number_format($detail->volume,4); ?></td>
							</tr>
				<?php } ?>
					<tr style="border-top: solid 1px #000; background-color: #F1F4F7;">
						<td colspan="5" class="text-align-right" style="padding: 5px; border-right: solid 1px #000;"><b>Total</b> &nbsp;</td>
						<td class="text-align-center" style="padding: 5px; border-right: solid 1px #000;"><b><?= $totalpcs ?></b></td>
						<td class="text-align-center" style="padding: 5px;"><b><?= ($_GET['caraprint'] == "EXCEL")? app\components\DeltaFormatter::formatNumberForUserFloat($totalvolume) : number_format($totalvolume,4); ?></b></td>
					</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<?php if( count($modContainer) == ($ii+1) ){ ?>		
	<tr style="border: solid 1px #000; border-top: solid 1px #000;">
		<td colspan="3" style="">
			<table style="width: 100%; font-size: 1.1rem; text-align: center;">
				<tr style="height: 0.4cm;  border-bottom: solid 1px #000;">
					<td rowspan="3" style="width: 8cm; font-size: 0.9rem; text-align: left; vertical-align: bottom; border-right: solid 1px #000;">
						<?php
						echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
						echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
						?>
					</td>
					<td style="vertical-align: middle; width: 4cm; border-right: solid 1px #000;">Dibuat Oleh,</td>
					<td style="vertical-align: middle; width: 4cm; border-right: solid 1px #000;">Disetujui Oleh,</td>
					<td style="vertical-align: middle; width: 4cm; ">Diketahui Oleh,</td>
				</tr>
				<tr>
					<td style="height: 55px; vertical-align: middle; padding-left: 5px; text-align: center; border-right: solid 1px #000;"></td>
					<td style="height: 55px; vertical-align: middle; padding-left: 5px; text-align: center; border-right: solid 1px #000;">
						<?php
						$modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->disetujui]);
						echo (!empty($modApproval->status== app\models\TApproval::STATUS_APPROVED)?'<h4 class="font-green-jungle"><b>'.$modApproval->status.'</b></h4>':'');
						?>
					</td>
					<td style="height: 55px; vertical-align: middle; padding-left: 5px; text-align: center;">
						<?php
						$modApproval = app\models\TApproval::findOne(['reff_no'=>$model->kode,'assigned_to'=>$model->mengetahui]);
						echo (!empty($modApproval->status== app\models\TApproval::STATUS_APPROVED)?'<h4 class="font-green-jungle"><b>'.$modApproval->status.'</b></h4>':'');
						?>
					</td>
				</tr>
				<tr>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px #000; border-top: solid 1px #000;  line-height: 1; padding-bottom: 2px;">
						<?php
						echo "<span style='font-size:0.9rem'><b><u> ". app\models\MPegawai::findOne( app\models\MUser::findOne($model->created_by)->pegawai_id )->pegawai_nama." </u></b></span><br>";
						echo "<span style='font-size:0.8rem'>PPIC ".$model->jenis_produk."</span>";
						?>
					</td>
					<td style="height: 20px; vertical-align: middle; border-right: solid 1px #000; border-top: solid 1px #000; line-height: 1; padding-bottom: 2px;">
						<?php
						if(!empty($model->disetujui)){
							echo "<span style='font-size:0.9rem'><b><u> ". $model->disetujui0->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Kadept. PPIC </span>";
						}
						?>
					</td>
					<td style="height: 20px; vertical-align: middle; border-top: solid 1px #000; line-height: 1; padding-bottom: 2px;">
						<?php
						if(!empty($model->mengetahui)){
							echo "<span style='font-size:0.9rem'><b><u> ". $model->mengetahui0->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Kadiv. Operasional </span>";
						}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php } ?>
	
</table>
<p style="page-break-after: always;">&nbsp;</p>
<?php } ?>