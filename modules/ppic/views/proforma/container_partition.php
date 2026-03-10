<?php
app\assets\InputMaskAsset::register($this);
$sql = "SELECT container_no, container_kode, seal_no, container_size, lot_code, gross_weight, nett_weight, bundles_no, partition_kode, grade, jenis_kayu, glue, profil_kayu, kondisi_kayu
		FROM t_packinglist_container
		WHERE packinglist_id = ".$modPackinglist->packinglist_id." AND container_no = ".($container_no)."
		GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14
		ORDER BY 1,2,3, bundles_no ASC";
$modDetails = Yii::$app->db->createCommand($sql)->queryAll();

$modpartition = Yii::$app->db->createCommand("SELECT partition_kode, max(partition_kode) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = '".$container_no."' GROUP BY 1 ORDER BY MAX(partition_kode) ASC")->queryAll();
$modthick = Yii::$app->db->createCommand("SELECT thick, max(thick) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = '".$container_no."' GROUP BY 1 ORDER BY MAX(thick) ASC")->queryAll();
$modwidth = Yii::$app->db->createCommand("SELECT width, max(width) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = '".$container_no."' GROUP BY 1 ORDER BY MAX(width) ASC")->queryAll();
$modlength = Yii::$app->db->createCommand("SELECT length, max(length) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = '".$container_no."' GROUP BY 1 ORDER BY MAX(length) ASC")->queryAll();
$modunit = Yii::$app->db->createCommand("SELECT thick_unit,width_unit,length_unit FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = '".$container_no."' GROUP BY 1,2,3")->queryOne();
$modbundles = Yii::$app->db->createCommand("SELECT bundles_no, max(bundles_no) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = '".$container_no."' GROUP BY 1 ORDER BY MAX(bundles_no) ASC")->queryAll();
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
		$html_thick .= '<th style="width: 80px; " class="kolom-pcs font-purple">'.$thick.'</th>';
	}
	$thick_span = 'colspan="'.$total_thick.'"';
}
$total_width = count($params_width);
$html_width = "";
if($total_width>1){
	foreach($params_width as $n => $width){
		$html_width .= '<th style="width: 80px; " class="kolom-pcs font-purple">'.$width.'</th>';
	}
	$width_span = 'colspan="'.$total_width.'"';
}

$total_length = count($params_length);
$html_length = "";
if($total_length>1){
	foreach($params_length as $o => $length){
		$html_length .= '<th style="width: 80px; " class="kolom-pcs font-purple">'.$length.'</th>';
	}
	$length_span = 'colspan="'.$total_length.'"';
}

$colspan_header = 13 + $total_thick + $total_width + $total_length;
$colspan_footer = 5 + $total_thick + $total_width + $total_length;
if($modPackinglist->jenis_produk == "Plywood" || $modPackinglist->jenis_produk == "Lamineboard" || $modPackinglist->jenis_produk == "Platform"){
	$column = '<th rowspan="2" style="width: 100px; background-color: #E3E7EA;">Wood<br>Type</th>'
			. '<th rowspan="2" style="width: 120px; background-color: #E3E7EA;">Glue</th>';
	$colspan_header += 1;
	$colspan_footer += 1;
}else if($modPackinglist->jenis_produk == "Sawntimber"){
	$column = '<th rowspan="2" style="width: 150px; background-color: #E3E7EA;">Condition</th>';
}else if($modPackinglist->jenis_produk == "Moulding"){
	$column = '<th rowspan="2" style="width: 100px; background-color: #E3E7EA;">Wood<br>Type</th>'
			. '<th rowspan="2" style="width: 175px; background-color: #E3E7EA;">Profil</th>';
	$colspan_header += 1;
	$colspan_footer += 1;
}
if($total_partition > 1){
	$colspan_header += 1;
	$colspan_footer += 1;
}
?>
<div class="row">
	<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-advance table-hover table-contrainer" style="width: 100%; border: 1px solid #A0A5A9;">
				<thead>
					<tr style="background-color: #E3E7EA">
						<td colspan="<?= $colspan_header ?>" style="vertical-align: middle; font-size: 1.4rem;">
							<b>Container Seq : <span class="font-blue-steel">
								<?= yii\bootstrap\Html::textInput("container_no",(!empty($modDetails[0]['container_no'])?$modDetails[0]['container_no']:""),['class'=>'form-control','disabled'=>'disabled','style'=>'width:40px; text-align:center; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?>
							</span></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Container No : <span class="font-blue-steel"><?= !empty($modDetails[0]['container_kode'])?$modDetails[0]['container_kode']:"-" ?></span></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Seal No : <span class="font-blue-steel"><?= !empty($modDetails[0]['seal_no'])?$modDetails[0]['seal_no']:"-" ?></span></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Container Size : <span class="font-blue-steel"><?= !empty($modDetails[0]['container_size'])?$modDetails[0]['container_size']." Feet":"-" ?></span></b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Lot Code : <span class="font-blue-steel"><?= !empty($modDetails[0]['lot_code'])?$modDetails[0]['lot_code']:"-" ?></span></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<span class="pull-right">
								<a class="btn btn-icon-only blue-steel tooltips btn-outline" onclick="editcontainer(this)" data-original-title="Edit Container ini" style="width: 24px; height: 24px; padding-top: 1px; padding-bottom: 1px;"><i class="fa fa-edit"></i></a>
								<a class="btn btn-icon-only red-flamingo tooltips btn-outline" onclick="hapuscontainer(this)" data-original-title="Hapus Container ini" style="width: 24px; height: 24px; padding-top: 1px; padding-bottom: 1px;"><i class="fa fa-trash-o"></i></a>
							</span>
						</td>
					</tr>
					<tr>
						<th rowspan="2" style="width: 50px; background-color: #E3E7EA;" class="kolom-bundle">Bundle<br>No.</th>
						<?php if($total_partition > 1){ ?>
						<th rowspan="2" style="width: 50px; background-color: #E3E7EA;" class="kolom-bundle">Part</th>
						<?php } ?>
						<th rowspan="2" style="width: 100px; background-color: #E3E7EA" class="kolom-grade">Grade</th>
						<?= $column ?>
						<th <?= $thick_span ?> style="width: 80px; background-color: #E3E7EA; line-height: 1;" class="kolom-pcs">Thick<br>(<?= $params_thick_unit ?>)</th>
						<th  <?= $width_span ?> style="width: 80px; background-color: #E3E7EA; line-height: 1;" class="kolom-pcs">Width<br>(<?= $params_width_unit ?>)</th>
						<th  <?= $length_span ?> style="width: 80px; background-color: #E3E7EA; line-height: 1;" class="kolom-pcs">Length<br>(<?= $params_length_unit ?>)</th>
						<th colspan="2" style="background-color: #E3E7EA">Total</th>
					</tr>
					<tr>
						<?= $html_thick.$html_width.$html_length; ?>
						<th style="width: 80px; background-color: #E3E7EA" class="kolom-pcs">Pcs</th>
						<th style="width: 80px; background-color: #E3E7EA" class="kolom-volume">M<sup>3</sup></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$total_pcs=0;$total_volume=0;$subtotal_bundle=0;
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
                        $i = 0;
						foreach($modDetails as $v => $detail){
							$subtot_pcs = 0; $subtot_volume = 0; 
							if($modPackinglist->jenis_produk == "Plywood" || $modPackinglist->jenis_produk == "Lamineboard" || $modPackinglist->jenis_produk == "Platform"){
								$column = '<td style="padding: 2px; text-align:center;">'.$detail['jenis_kayu'].'</td>'.
										  '<td style="padding: 2px; text-align:center;">'.$detail['glue'].'</td>';
							}else if($modPackinglist->jenis_produk == "Sawntimber"){
								$column = '<td style="padding: 2px; text-align:center;">'.$detail['kondisi_kayu'].'</td>';
							}else if($modPackinglist->jenis_produk == "Moulding"){
								$column = '<td style="padding: 2px; text-align:center;">'.$detail['jenis_kayu'].'</td>'.
										  '<td style="padding: 2px; text-align:center;">'.$detail['profil_kayu'].'</td>';
							}
							echo "<tr>";
							echo		"<td style='text-align:center;'>".($detail['bundles_no'])."</td>";
							if($total_partition > 1){
								echo	"<td style='text-align:center;'>".$detail['partition_kode']."</td>";
							}
							echo		"<td style='text-align:center;'>".($detail['grade'])."</td>";
							echo		$column;
							foreach($params_thick as $vv => $thick){
								if(count($params_thick)>1){
									$sql = "SELECT pcs, ROUND( volume::numeric, 4) AS volume FROM t_packinglist_container
											WHERE packinglist_id = ".$modPackinglist->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
												".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
												".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
												".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
												".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
												AND thick = {$thick} AND width = {$params_width[0]} AND length = {$params_length[0]}";
									$hmm = Yii::$app->db->createCommand($sql)->queryOne();
									echo	"<td style='text-align:center;'>".$hmm['pcs']."</td>";
									$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
									$subtotal_dimensi[$thick] += $hmm['pcs'];
								}else{
									echo	"<td style='text-align:center;' class='font-purple'>".$thick."</td>";
								}
							}
							foreach($params_width as $vv => $width){
								if(count($params_width)>1){
									$sql = "SELECT pcs, ROUND( volume::numeric, 4) AS volume FROM t_packinglist_container
											WHERE packinglist_id = ".$modPackinglist->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
												".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
												".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
												".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
												".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
												AND thick = {$params_thick[0]} AND width = {$width} AND length = {$params_length[0]}";
									$hmm = Yii::$app->db->createCommand($sql)->queryOne();
									echo	"<td style='text-align:center;'>".$hmm['pcs']."</td>";
									$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
									$subtotal_dimensi[$width] += $hmm['pcs'];
								}else{
									echo	"<td style='text-align:center;' class='font-purple'>".$width."</td>";
								}
							}
							foreach($params_length as $vv => $length){
								if(count($params_length)>1){
									$sql = "SELECT pcs, ROUND( volume::numeric, 4) AS volume FROM t_packinglist_container
											WHERE packinglist_id = ".$modPackinglist->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
												".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
												".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
												".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
												".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
												AND thick = {$params_thick[0]} AND width = {$params_width[0]} AND length = {$length}";
									$hmm = Yii::$app->db->createCommand($sql)->queryOne();
									echo	"<td style='text-align:center;'>".$hmm['pcs']."</td>";
									$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
									$subtotal_dimensi[$length] += $hmm['pcs'];
								}else{
									echo	"<td style='text-align:center;' class='font-purple'>".$length."</td>";
								}
							}
							echo		"<td style='text-align:center;'>".($subtot_pcs)."</td>";
							echo		"<td style='text-align:center;'>".(number_format($subtot_volume,4))."</td>";
							echo "</tr>";
							$subtotal_bundle = $subtotal_bundle+1;
							$total_pcs += $subtot_pcs;  
							$total_volume += $subtot_volume;
                            $i++;
						}

                        if(isset($_POST['revisi'])){
                            $sql = "select * from t_packinglist_container where packinglist_id = ".$modPackinglist->packinglist_id." ";
                            $modmod = Yii::$app->db->createCommand($sql)->queryAll();
                            $i = 1;
                            foreach($modmod as $v => $detail){
                            ?>
                            <tr style="display: none;">
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][packinglist_id]",(!empty($detail['packinglist_id'])?$detail['packinglist_id']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][container_no]",(!empty($detail['container_no'])?$detail['container_no']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][bundles_no]",(!empty($detail['bundles_no'])?$detail['bundles_no']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][partition_kode]",(!empty($detail['partition_kode'])?$detail['partition_kode']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][grade]",(!empty($detail['grade'])?$detail['grade']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][jenis_kayu]",(!empty($detail['jenis_kayu'])?$detail['jenis_kayu']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][glue]",(!empty($detail['glue'])?$detail['glue']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][profil_kayu]",(!empty($detail['profil_kayu'])?$detail['profil_kayu']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][thick]",(!empty($detail['thick'])?$detail['thick']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][thick_unit]",(!empty($detail['thick_unit'])?$detail['thick_unit']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][width]",(!empty($detail['width'])?$detail['width']:"0"),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][width_unit]",(!empty($detail['width_unit'])?$detail['width_unit']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][length]",(!empty($detail['length'])?$detail['length']:"0"),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][length_unit]",(!empty($detail['length_unit'])?$detail['length_unit']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][pcs]",(!empty($detail['pcs'])?$detail['pcs']:"0"),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][volume]",(!empty($detail['volume'])?$detail['volume']:"0"),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][gross_weight]",(!empty($detail['gross_weight'])?$detail['gross_weight']:""),[]) ?></td>
                                <td><?= yii\bootstrap\Html::textInput("TPackinglistContainer[".$i."][nett_weight]",(!empty($detail['nett_weight'])?$detail['nett_weight']:""),[]) ?></td>
                            </tr>
                            <?php
                                $i++;
                            }
                        }
                    }
					?>
				</tbody>
				<tfoot style="background-color: #E3E7EA">
					<tr>
						<?php
						$subtotal_bundle = $subtotal_bundle / $total_partition;
						if($total_partition > 1){
							echo "<td style='border-right: solid 1px #A0A5A9; vertical-align: middle; padding: 2px; text-align:center;' colspan=2><b>".$subtotal_bundle." Bdl</b></td>";
						}else{
							echo "<td style='border-right: solid 1px #A0A5A9; vertical-align: middle; padding: 2px; text-align:center;' ><b>".$subtotal_bundle."</b></td>";
						}
						echo "<td style='border-right: solid 1px #A0A5A9; padding: 2px;' colspan=5></td>";
						if(count($subtotal_dimensi)>0){
							foreach($subtotal_dimensi as $x => $cvb){
								echo "<td style='border-right: solid 1px #A0A5A9; padding: 2px; text-align:center;'><b>".$cvb."</b></td>";
							}
						}
						?>
						<td style="text-align: center; padding: 2px; vertical-align: middle;"> 
							<?= $total_pcs ?> 
						</td>
						<td style="text-align: center; padding: 2px; vertical-align: middle;"> 
							<?= $total_volume ?> 
						</td>
					</tr>
					<tr>
						<td colspan="<?= $colspan_footer ?>" style="text-align: left; vertical-align: middle; font-size: 1.4rem;" > 
							Gross Weight (kg) : <?= (!empty($modDetails[0]['gross_weight'])? \app\components\DeltaFormatter::formatNumberForUserFloat($modDetails[0]['gross_weight']) :0) ?> &nbsp; &nbsp; &nbsp;
							Nett Weight (kg) : <?= (!empty($modDetails[0]['nett_weight'])? \app\components\DeltaFormatter::formatNumberForUserFloat($modDetails[0]['nett_weight']) :0) ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</div><br>
	</div>
</div>
<script>

function toM3(qty,t,l,p,sat_t,sat_l,sat_p){
	var sat_p_m = 0;
    var sat_l_m = 0;
    var sat_t_m = 0;
    var result = 0;
	
	if(sat_p == 'mm'){
		sat_p_m = p * 0.001;
	}else if(sat_p == 'cm'){
		sat_p_m = p * 0.01;
	}else if(sat_p == 'inch'){
		sat_p_m = p * 0.0254;
	}else if(sat_p == 'm'){
		sat_p_m = p;
	}else if(sat_p == 'feet'){
		sat_p_m = p * 0.3048;
	}
	if(sat_l == 'mm'){
		sat_l_m = l * 0.001;
	}else if(sat_l == 'cm'){
		sat_l_m = l * 0.01;
	}else if(sat_l == 'inch'){
		sat_l_m = l * 0.0254;
	}else if(sat_l == 'm'){
		sat_l_m = l;
	}else if(sat_l == 'feet'){
		sat_l_m = l * 0.3048;
	}
	if(sat_t == 'mm'){
		sat_t_m = t * 0.001;
	}else if(sat_t == 'cm'){
		sat_t_m = t * 0.01;
	}else if(sat_t == 'inch'){
		sat_t_m = t * 0.0254;
	}else if(sat_t == 'm'){
		sat_t_m = t;
	}else if(sat_t == 'feet'){
		sat_t_m = t * 0.3048;
	}
	result = sat_p_m * sat_l_m * sat_t_m * qty;
//    result = (Math.round( result * 10000 ) / 10000 ).toString();
	return result;
}
</script>