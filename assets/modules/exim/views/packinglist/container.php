<?php
if($jenis_produk == "Plywood" || $jenis_produk == "Lamineboard" || $jenis_produk == "Platform"){
//	$satuanbesar = "Crate";
//	$satuanbesar2 = "Crate";
	$satuanbesar = "Bdl";
	$satuanbesar2 = "Bundle";
}else{
	$satuanbesar = "Bdl";
	$satuanbesar2 = "Bundle";
}
?>
<div class="row">
	<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-advance table-hover table-contrainer" style="width: 100%; border: 1px solid #A0A5A9;">
				<thead>
					<?php 
					$column = "";
					$colspan_header = 8;
					$colspan_footer = 3;
					if($jenis_produk == "Plywood" || $jenis_produk == "Lamineboard" || $jenis_produk == "Platform"){
						$column = '<th rowspan="2" style="width: 100px; background-color: #E3E7EA">Wood<br>Type</th>'
								. '<th rowspan="2" style="width: 120px; background-color: #E3E7EA">Glue</th>';
						$colspan_header = 10;
						$colspan_footer = 5;
					}else if($jenis_produk == "Sawntimber"){
						$column = '<th rowspan="2" style="width: 150px; background-color: #E3E7EA">Condition</th>';
						$colspan_header = 9;
						$colspan_footer = 4;
					}else if($jenis_produk == "Moulding"){
						$column = '<th rowspan="2" style="width: 100px; background-color: #E3E7EA">Wood<br>Type</th>'
								. '<th rowspan="2" style="width: 175px; background-color: #E3E7EA">Profil</th>';
						$colspan_header = 10;
						$colspan_footer = 5;
					}
					$sql = "SELECT * FROM t_packinglist_container WHERE packinglist_id=".$modPackinglist->packinglist_id." AND container_no=".$container_no." ORDER BY bundles_no, partition_kode";
					$modDetail = Yii::$app->db->createCommand($sql)->queryAll(); 
					?>
					
					<tr style="background-color: #E3E7EA">
						<td colspan="<?= $colspan_header ?>" style="vertical-align: middle;">
							<b>Container Seq : <?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$model->container_no."][container_no]",$model->container_no,['class'=>'form-control','disabled'=>'disabled','style'=>'width:40px; text-align:center; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Container No : <?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$model->container_no."][container_kode]",$model->container_kode,['class'=>'form-control','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Seal No : <?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$model->container_no."][seal_no]",$model->seal_no,['class'=>'form-control','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Container Size : <?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$model->container_no."][container_size]",$model->container_size,['class'=>'form-control float','disabled'=>'disabled','style'=>'width:30px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> Feet</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
							<b>Lot Code : <?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$model->container_no."][lot_code]",$model->lot_code,['class'=>'form-control','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<span class="pull-right">
								
							</span>
						</td>
					</tr>
					<tr>
						<th rowspan="2" style="width: 50px; background-color: #E3E7EA;" class="kolom-bundle"><?= $satuanbesar2 ?><br>No.</th>
						<th rowspan="2" style="width: 100px; background-color: #E3E7EA" class="kolom-grade">Grade</th>
						<?= $column ?>
						<th rowspan="2" style="width: 120px; background-color: #E3E7EA" class="kolom-thick">Thick<br>(<?= $modDetail[0]['thick_unit'] ?>)</th>
						<th rowspan="2" style="width: 120px; background-color: #E3E7EA" class="kolom-width">Width<br>(<?= $modDetail[0]['width_unit'] ?>)</th>
						<th rowspan="2" style="width: 120px; background-color: #E3E7EA" class="kolom-length">Length<br>(<?= $modDetail[0]['length_unit'] ?>)</th>
						<th colspan="2" style="background-color: #E3E7EA">Qty</th>
					</tr>
					<tr>
						<th style="width: 80px; background-color: #E3E7EA" class="kolom-pcs">Pcs</th>
						<th style="width: 80px; background-color: #E3E7EA" class="kolom-volume">M<sup>3</sup></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($modPackinglist)){
						$table_id = "";
						if(count($modDetail)>0){
							$tot_pcs = 0; $tot_volume = 0;
							foreach($modDetail as $ii => $detail){ 
								echo "<tr>";
								echo	"<td style='text-align:center;'>".($detail['bundles_no'])."</td>";
								if(!empty($detail['grade'])){
									echo "<td style='text-align:center;'>".($detail['grade'])."</td>";
								}
								if(!empty($detail['jenis_kayu'])){
									echo "<td style='text-align:center;'>".($detail['jenis_kayu'])."</td>";
								}
								if(!empty($detail['glue'])){
									echo "<td style='text-align:center;'>".($detail['glue'])."</td>";
								}
								if(!empty($detail['profil_kayu'])){
									echo "<td style='text-align:center;'>".($detail['profil_kayu'])."</td>";
								}
								if(!empty($detail['kondisi_kayu'])){
									echo "<td style='text-align:center;'>".($detail['kondisi_kayu'])."</td>";
								}
								echo "<td style='text-align:center;'>".($detail['thick'])."</td>";
								echo "<td style='text-align:center;'>".($detail['width'])."</td>";
								echo "<td style='text-align:center;'>".($detail['length'])."</td>";
								echo "<td style='text-align:center;'>".($detail['pcs'])."</td>";
								echo "<td style='text-align:center;'>".(number_format($detail['volume'],4))."</td>";
								echo "</tr>";
								$tot_pcs += $detail['pcs'];
//								$tot_volume += $detail['volume'];
								$tot_volume += (number_format($detail['volume'],4));
							}
						}
						?>
					<?php }else{ ?>
						<tr id="place-emptytr" class="uncount-tr"><td colspan="<?= $colspan_header ?>" style="text-align: center; font-size: 1.2rem;">Data Tidak Ditemukan</td></tr>
					<?php } ?>
				</tbody>
				<tfoot style="background-color: #E3E7EA">
					<tr>
						<td style="vertical-align: middle; padding: 2px;"></td>
						<td colspan="<?= $colspan_footer ?>" style="text-align: right; vertical-align: middle; font-size: 1.2rem;" > 
							Gross Weight (kg) : <?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$model->container_no."][gross_weight]",$model->gross_weight,['class'=>'form-control float','onblur'=>'totalWeight(); setContainerDetails();','style'=>'width:80px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> &nbsp; &nbsp; &nbsp;
							Nett Weight (kg) : <?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$model->container_no."][nett_weight]",$model->nett_weight,['class'=>'form-control float','onblur'=>'totalWeight(); setContainerDetails();','style'=>'width:80px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?>
						</td>
						
						<td style="text-align: right; vertical-align: middle;"></td>
						<td style="text-align: center; padding: 2px; vertical-align: middle;"> <?= $tot_pcs ?> </td>
						<td style="text-align: center; padding: 2px; vertical-align: middle;"> <?= $tot_volume ?> </td>
					</tr>
				</tfoot>
			</table>
		</div><br>
	</div>
</div>