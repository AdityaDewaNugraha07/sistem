<?php
app\assets\InputMaskAsset::register($this);

if(!empty($modPackinglist)){
	$modpartition = Yii::$app->db->createCommand("SELECT partition_kode, max(partition_kode) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = {$container_no} GROUP BY 1 ORDER BY MAX(partition_kode) ASC")->queryAll();
	$modthick = Yii::$app->db->createCommand("SELECT thick, max(thick) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = {$container_no} GROUP BY 1 ORDER BY MAX(thick) ASC")->queryAll();
	$modwidth = Yii::$app->db->createCommand("SELECT width, max(width) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = {$container_no} GROUP BY 1 ORDER BY MAX(width) ASC")->queryAll();
	$modlength = Yii::$app->db->createCommand("SELECT length, max(length) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = {$container_no} GROUP BY 1 ORDER BY MAX(length) ASC")->queryAll();
	$modunit = Yii::$app->db->createCommand("SELECT thick_unit,width_unit,length_unit FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = {$container_no} GROUP BY 1,2,3")->queryOne();
	$modbundles = Yii::$app->db->createCommand("SELECT bundles_no, max(bundles_no) FROM t_packinglist_container WHERE packinglist_id = {$modPackinglist->packinglist_id} AND container_no = {$container_no} GROUP BY 1 ORDER BY MAX(bundles_no) ASC")->queryAll();
	$total_bundles = count($modbundles);
	$total_partition = count($modpartition);
	
	$params_thick = [];
	foreach($modthick as $i => $asd){
		$params_thick[] = $asd['thick'];
	}
	$params_width = [];
	foreach($modwidth as $i => $asd){
		$params_width[] = $asd['width'];
	}
	$params_length = [];
	foreach($modlength as $i => $asd){
		$params_length[] = $asd['length'];
	}
	$params_thick_unit = $modunit['thick_unit'];
	$params_width_unit = $modunit['width_unit'];
	$params_length_unit = $modunit['length_unit'];
}else{
	$total_bundles = $form_params['total_bundles'];
	$total_partition = $form_params['total_partition'];
	$params_thick = $form_params['thick'];
	$params_width = $form_params['width'];
	$params_length = $form_params['length'];
	$params_thick_unit = $form_params['thick_unit'];
	$params_width_unit = $form_params['width_unit'];
	$params_length_unit = $form_params['length_unit'];
}
?>
<div class="row">
	<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
		<div class="table-scrollable">
			<table class="table table-striped table-bordered table-advance table-hover table-contrainer" style="width: 100%; border: 1px solid #A0A5A9;">
				<thead>
					<?php
					$column = ""; $thick_span='rowspan="2"';$width_span='rowspan="2"';$length_span='rowspan="2"';
					$total_thick = count($params_thick);
					$html_thick = "";
					if($total_thick>1){
						foreach($params_thick as $i => $thick){
							$html_thick .= '<th style="width: 80px; background-color: #E3E7EA" class="kolom-pcs font-purple">'.$thick.'</th>';
						}
						$thick_span = 'colspan="'.$total_thick.'"';
					}

					$total_width = count($params_width);
					$html_width = "";
					if($total_width>1){
						foreach($params_width as $i => $width){
							$html_width .= '<th style="width: 80px; background-color: #E3E7EA" class="kolom-pcs font-purple">'.$width.'</th>';
						}
						$width_span = 'colspan="'.$total_width.'"';
					}

					$total_length = count($params_length);
					$html_length = "";
					if($total_length>1){
						foreach($params_length as $i => $length){
							$html_length .= '<th style="width: 80px; background-color: #E3E7EA" class="kolom-pcs font-purple">'.$length.'</th>';
						}
						$length_span = 'colspan="'.$total_length.'"';
					}
					
					$colspan_header = 13 + $total_thick + $total_width + $total_length;
					$colspan_footer = 2 + $total_thick + $total_width + $total_length;
					if($jenis_produk == "Plywood" || $jenis_produk == "Lamineboard" || $jenis_produk == "Platform"){
						$column = '<th rowspan="2" style="width: 100px; background-color: #E3E7EA">Wood<br>Type</th>'
								. '<th rowspan="2" style="width: 120px; background-color: #E3E7EA">Glue</th>';
						$colspan_header += 1;
						$colspan_footer += 1;
					}else if($jenis_produk == "Sawntimber"){
						$column = '<th rowspan="2" style="width: 150px; background-color: #E3E7EA">Condition</th>';
					}else if($jenis_produk == "Moulding"){
						$column = '<th rowspan="2" style="width: 100px; background-color: #E3E7EA">Wood<br>Type</th>'
								. '<th rowspan="2" style="width: 175px; background-color: #E3E7EA">Profil</th>';
						$colspan_header += 1;
						$colspan_footer += 1;
					}
					if($total_partition > 1){
						$colspan_header += 1;
						$colspan_footer += 1;
					}
					?>
					
					<tr style="background-color: #E3E7EA">
						<td colspan="<?= $colspan_header ?>" style="vertical-align: middle;">
							<b>Container Seq. : <?= yii\bootstrap\Html::textInput("container_no",(!empty($container_no)?$container_no:""),['class'=>'form-control','disabled'=>'disabled','style'=>'width:40px; text-align:center; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Container No. : <?= yii\bootstrap\Html::textInput("container_kode",(!empty($container_kode)?$container_kode:""),['class'=>'form-control','onblur'=>'setContainerDetails();','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Seal No : <?= yii\bootstrap\Html::textInput("seal_no",(!empty($seal_no)?$seal_no:""),['class'=>'form-control','onblur'=>'setContainerDetails();','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Container Size : <?= yii\bootstrap\Html::textInput("container_size",(!empty($container_size)?$container_size:""),['class'=>'form-control float','onblur'=>'setContainerDetails();','style'=>'width:30px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> Feet</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
							<b>Lot Code : <?= yii\bootstrap\Html::textInput("lot_code",(!empty($lot_code)?$lot_code:""),['class'=>'form-control','onblur'=>'setContainerDetails();','style'=>'width:100px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?></b> &nbsp; &nbsp; &nbsp; &nbsp; 
							<span class="pull-right">
								<a class="btn btn-icon-only blue-steel tooltips btn-outline" onclick="hapuscontainer(this)" data-original-title="Hapus Container ini" style="width: 24px; height: 24px; padding-top: 1px; padding-bottom: 1px;"><i class="fa fa-trash-o"></i></a>
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
					if($total_bundles>0){
						$total_row = $total_bundles*$total_partition;
						$bundles_no = 0;
						for($i=0;$i<$total_row;$i++){
							$column = "";
							if(($i%$total_partition) == 0){
								$bundles_no = $bundles_no+1;
							}else{
								$bundles_no = $bundles_no;
							}
							$partition_kode = range('A', 'Z')[($i%$total_partition)];
							if($jenis_produk == "Plywood" || $jenis_produk == "Lamineboard" || $jenis_produk == "Platform"){
								$column = '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[".$i."]jenis_kayu", \app\models\MJenisKayu::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>'.
										  '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[".$i."]glue", \app\models\MGlue::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>';
							}else if($jenis_produk == "Sawntimber"){
								$column = '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[".$i."]kondisi_kayu", \app\models\MKondisiKayu::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>';
							}else if($jenis_produk == "Moulding"){
								$column = '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[".$i."]jenis_kayu", \app\models\MJenisKayu::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>'.
										  '<td style="padding: 2px;">'.yii\bootstrap\Html::activeDropDownList($model, "[".$i."]profil_kayu", \app\models\MProfilKayu::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']).'</td>';
							}
							?>
							<tr>
								<td style="padding: 2px;">
									<?= \yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:50px;']); ?>
									<?= \yii\bootstrap\Html::activeTextInput($model, '['.$i.']bundles_no',['class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center; height: 26px; padding:2px; font-size:1.2rem;','value'=>$bundles_no]) ?>
									<?= \yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']packinglist_container_id') ?>
									<?= \yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']container_no',['value'=>(!empty($container_no)?$container_no:"")]) ?>
									<?= \yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']container_kode',['value'=>(!empty($container_kode)?$container_kode:"")]) ?>
									<?= \yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']seal_no',['value'=>(!empty($seal_no)?$seal_no:"")]) ?>
									<?= \yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']container_size',['value'=>(!empty($container_size)?$container_size:"")]) ?>
									<?= \yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']lot_code',['value'=>(!empty($lot_code)?$lot_code:"")]) ?>
									<?= \yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']gross_weight',['class'=>'float','value'=>(!empty($gross_weight)?$gross_weight:"")]) ?>
									<?= \yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']nett_weight',['class'=>'float','value'=>(!empty($nett_weight)?$nett_weight:"")]) ?>
								</td>
								<?php if($total_partition > 1){ ?>
								<td style="padding: 2px;">
									<?= \yii\bootstrap\Html::activeTextInput($model, '['.$i.']partition_kode',['class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center; height: 26px; padding:2px; font-size:1.2rem;','value'=>$partition_kode]) ?>
								</td>
								<?php } ?>
								<td style="padding: 2px;">
									<?= yii\bootstrap\Html::activeDropDownList($model, '['.$i.']grade', \app\models\MGrade::getOptionListNama($jenis_produk),['class'=>'form-control','prompt'=>'','style'=>'height: 26px; padding:2px; font-size:1.2rem;']) ?>
								</td>
								<?= $column ?>
								<?php
								foreach($params_thick as $ii => $thick){
									if(count($params_thick)>1){
										echo '<td style="padding: 2px;">'. 
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']thick_satuan',['value'=>$params_thick_unit]) .
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']thick_rdm',['class'=>'ukuranrdm','value'=>$thick]) .
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']thick_m3['.$thick.']',['value'=>0]) .
												\yii\bootstrap\Html::activeTextInput($model, '['.$i.']thick['.$thick.']',['class'=>'form-control float qtyperuk','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','onblur'=>'totalRandom(this)','value'=>0]) 
											.'</td>';
									}else{
										echo '<td style="padding: 2px;">'. 
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']thick_satuan',['value'=>$params_thick_unit]) .
												\yii\bootstrap\Html::activeTextInput($model, '['.$i.']thick',['class'=>'form-control float font-purple ukuran-thick','style'=>'text-align:center; height: 26px; padding:2px; font-size:1.2rem; font-weight: 600;','value'=>$thick]) 
											.'</td>';
									}
								}
								foreach($params_width as $ii => $width){
									if(count($params_width)>1){
										echo '<td style="padding: 2px;">'. 
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']width_satuan',['value'=>$params_width_unit]) .
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']width_rdm',['class'=>'ukuranrdm','value'=>$width]) .
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']width_m3['.$width.']',['value'=>0]) .
												\yii\bootstrap\Html::activeTextInput($model, '['.$i.']width['.$width.']',['class'=>'form-control float qtyperuk','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','onblur'=>'totalRandom(this)','value'=>0]) 
											.'</td>';
									}else{
										echo '<td style="padding: 2px;">'. 
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']width_satuan',['value'=>$params_width_unit]) .
												\yii\bootstrap\Html::activeTextInput($model, '['.$i.']width',['class'=>'form-control float font-purple ukuran-width','style'=>'text-align:center; height: 26px; padding:2px; font-size:1.2rem; font-weight: 600;','value'=>$width]) 
											.'</td>';
									}
								}
								foreach($params_length as $ii => $length){
									if(count($params_length)>1){
										echo '<td style="padding: 2px;">'. 
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']length_satuan',['value'=>$params_length_unit]) .
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']length_rdm',['class'=>'ukuranrdm','value'=>$length]) .
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']length_m3['.$length.']',['value'=>0]) .
												\yii\bootstrap\Html::activeTextInput($model, '['.$i.']length['.$length.']',['class'=>'form-control float qtyperuk','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','onblur'=>'totalRandom(this)','value'=>0]) 
											.'</td>';
									}else{
										echo '<td style="padding: 2px;">'. 
												\yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']length_satuan',['value'=>$params_length_unit]) .
												\yii\bootstrap\Html::activeTextInput($model, '['.$i.']length',['class'=>'form-control float font-purple ukuran-length','style'=>'text-align:center; height: 26px; padding:2px; font-size:1.2rem; font-weight: 600;','value'=>$length]) 
											.'</td>';
									}
								}
								?>
								<td style="padding: 2px; width: 150px;">
									<?= \yii\bootstrap\Html::activeTextInput($model, '['.$i.']pcs',['class'=>'form-control float','disabled'=>'disabled','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','onblur'=>'setMeterKubik(this)','value'=>0]) ?>
								</td>
								<td style="padding: 2px; width: 200px;">
									<?= \yii\bootstrap\Html::activeTextInput($model, '['.$i.']volume',['class'=>'form-control float','disabled'=>'disabled','style'=>'text-align:right; height: 26px; padding:2px; font-size:1.2rem;','value'=>0]) ?>
								</td>
							</tr><?php
						}
					}
					?>
				</tbody>
				<tfoot style="background-color: #E3E7EA">
					<tr>
						<td style="vertical-align: middle; padding: 2px;"></td>
						<td colspan="<?= $colspan_footer ?>" style="text-align: left; vertical-align: middle; font-size: 1.2rem;" > 
							Gross Weight (kg) : <?= yii\bootstrap\Html::textInput("gross_weight",(!empty($gross_weight)?$gross_weight:0),['class'=>'form-control float','onblur'=>'total(); setContainerDetails();','style'=>'width:80px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> &nbsp; &nbsp; &nbsp;
							Nett Weight (kg) : <?= yii\bootstrap\Html::textInput("nett_weight",(!empty($nett_weight)?$nett_weight:0),['class'=>'form-control float','onblur'=>'total(); setContainerDetails();','style'=>'width:80px; text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?>
						</td>
						<td style="text-align: right; padding: 2px; vertical-align: middle;"> <?= yii\bootstrap\Html::textInput("tot_pcs",0,['class'=>'form-control float','disabled'=>'disabled','style'=>'text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> </td>
						<td style="text-align: right; padding: 2px; vertical-align: middle;"> <?= yii\bootstrap\Html::textInput("tot_vol",0,['class'=>'form-control float','disabled'=>'disabled','style'=>'text-align:right; font-weight:600; display:inline; padding:2px; height: 26px; color:#3E66A6']) ?> </td>
					</tr>
				</tfoot>
			</table>
		</div><br>
	</div>
</div>
<script>
function totalRandom(ele){
	var qty = 0; var m3 = 0; 
	$(ele).parents('table').find('tbody > tr').each(function(){
		var vari = []; var varinonrandom = []; var tr = $(this); var sub_qty = 0; var sub_m3 = 0;
		if( $(this).find('.ukuran-thick').length > 0 ){
			varinonrandom.push( [unformatNumber( $(this).find('.ukuran-thick').val() ), $(this).find('input[name*="[thick_satuan]"]').val()] );
		}
		if( $(this).find('.ukuran-width').length > 0 ){
			varinonrandom.push( [unformatNumber( $(this).find('.ukuran-width').val() ), $(this).find('input[name*="[width_satuan]"]').val()] );
		}
		if( $(this).find('.ukuran-length').length > 0 ){
			varinonrandom.push( [unformatNumber( $(this).find('.ukuran-length').val() ), $(this).find('input[name*="[length_satuan]"]').val()] );
		}
		
		$(this).find('.qtyperuk').each(function(){ // random
			var qty = ($(this).val())? unformatNumber( $(this).val()) :0;
			var size = unformatNumber($(this).parents('td').find('.ukuranrdm').val());
			var unit = $(this).parents('td').find('input[name*="[length_satuan]"]').val();
			var m3 = toM3(qty,varinonrandom[0][0],varinonrandom[1][0],size,varinonrandom[0][1],varinonrandom[1][1],unit);
			$(this).parents("td").find('input[name*="[length_m3]"]').val(m3);
			sub_qty += qty;
			sub_m3 += unformatNumber( m3 );
		});
		if($(this).find('.qtyperuk').length > 0){
			sub_m3 = (Math.round( sub_m3 * 10000 ) / 10000 ).toString();
			$(tr).find('input[name*="[pcs]"]').val( formatNumberForUser(sub_qty) );
			$(tr).find('input[name*="[volume]"]').val( formatNumberForUser(sub_m3) );
		}
	});
	total();
}

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