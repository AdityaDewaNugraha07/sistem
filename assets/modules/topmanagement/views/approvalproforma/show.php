<?php 
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TPackinglist::findOne(['kode'=>$model->reff_no]);
?>
<style>
.form-group {
    margin-bottom: 0 !important;
}
table.table-contrainer thead tr th{
	padding : 1px !important;
}
.table-contrainer, 
.table-contrainer > tbody > tr > td, 
.table-contrainer > tbody > tr > th, 
.table-contrainer > tfoot > tr > td, 
.table-contrainer > tfoot > tr > th, 
.table-contrainer > thead > tr > td, 
.table-contrainer > thead > tr > th {
    border: 1px solid #A0A5A9;
	line-height: 0.9 !important;
	font-size: 1.2rem;
}
</style>
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Proforma'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Nomor Kontrak'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->opExport->nomor_kontrak ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Berkas'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_berkas); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['assigned_to'] ?></label>
				<div class="col-md-7"><strong><?= $model->assignedTo->pegawai_nama; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['approved_by'] ?></label>
				<div class="col-md-7"><strong><?= !empty($model->approved_by)?$model->approvedBy->pegawai_nama:"-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['tanggal_approve'] ?></label>
				<div class="col-md-7"><strong><?= !empty($model->tanggal_approve)?app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_approve):"-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['status'] ?></label>
				<div class="col-md-7"><strong>
					<?php
					if($model->status == \app\models\TApproval::STATUS_APPROVED){
						echo '<span class="label label-success">'.$model->status.'</span>';
					}else if($model->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
						echo '<span class="label label-default">'.$model->status.'</span>';
					}else if($model->status == \app\models\TApproval::STATUS_REJECTED){
						echo '<span class="label label-danger">'.$model->status.'</span>';
					}
					?>
				</strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-4 control-label"><?= Yii::t('app', 'Applicant'); ?></label>
				<div class="col-md-8"><strong><?= $modReff->cust->cust_an_nama ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-4 control-label"><?= Yii::t('app', 'Alamat'); ?></label>
				<div class="col-md-8"><strong><?= $modReff->cust->cust_an_alamat ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-4 control-label"><?= Yii::t('app', 'Revisi Ke'); ?></label>
				<div class="col-md-8"><strong><?= $modReff->revisi_ke ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-4 control-label"><?= Yii::t('app', 'Total Container'); ?></label>
				<div class="col-md-8"><strong><?= $modReff->total_container ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-4 control-label"><?= Yii::t('app', 'Total M<sup>3</sup>'); ?></label>
				<div class="col-md-8"><strong><?= $modReff->total_volume ?></strong></div>
			</div>
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-hoki bordered">
				<div class="portlet-title">
					<div class="tools" style="float: left;">
						<a href="javascript:;" class="collapse" data-original-title="" title=""> </a> &nbsp; 
					</div>
					<div class="caption"> <?= Yii::t('app', 'Show Detail'); ?> </div>
				</div>
				<div class="portlet-body" style="background-color: #d9e2f0" >
					<div class="row">
						<div class="col-md-12">
							<div class="table-scrollable">
								<?php 
								for($i=0;$i<($modReff->total_container);$i++){
									if($modReff->bundle_partition==true){ ?>
										<h4>Container No. <?= ($i+1); ?></h4>
										<?php
										$sql = "SELECT container_no, bundles_no, partition_kode, grade, jenis_kayu, glue, profil_kayu, kondisi_kayu
												FROM t_packinglist_container
												WHERE packinglist_id = ".$modReff->packinglist_id." AND container_no = ".($i+1)."
												GROUP BY 1,2,3,4,5,6,7,8
												ORDER BY 1,2,3 ASC";
										$modDetails = Yii::$app->db->createCommand($sql)->queryAll();

										$modpartition = Yii::$app->db->createCommand("SELECT partition_kode, max(partition_kode) FROM t_packinglist_container WHERE packinglist_id = {$modReff->packinglist_id} AND container_no = '".($i+1)."' GROUP BY 1 ORDER BY MAX(partition_kode) ASC")->queryAll();
										$modthick = Yii::$app->db->createCommand("SELECT thick, max(thick) FROM t_packinglist_container WHERE packinglist_id = {$modReff->packinglist_id} AND container_no = '".($i+1)."' GROUP BY 1 ORDER BY MAX(thick) ASC")->queryAll();
										$modwidth = Yii::$app->db->createCommand("SELECT width, max(width) FROM t_packinglist_container WHERE packinglist_id = {$modReff->packinglist_id} AND container_no = '".($i+1)."' GROUP BY 1 ORDER BY MAX(width) ASC")->queryAll();
										$modlength = Yii::$app->db->createCommand("SELECT length, max(length) FROM t_packinglist_container WHERE packinglist_id = {$modReff->packinglist_id} AND container_no = '".($i+1)."' GROUP BY 1 ORDER BY MAX(length) ASC")->queryAll();
										$modunit = Yii::$app->db->createCommand("SELECT thick_unit,width_unit,length_unit FROM t_packinglist_container WHERE packinglist_id = {$modReff->packinglist_id} AND container_no = '".($i+1)."' GROUP BY 1,2,3")->queryOne();
										$modbundles = Yii::$app->db->createCommand("SELECT bundles_no, max(bundles_no) FROM t_packinglist_container WHERE packinglist_id = {$modReff->packinglist_id} AND container_no = '".($i+1)."' GROUP BY 1 ORDER BY MAX(bundles_no) ASC")->queryAll();
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
										$colspan_footer = 3 + $total_thick + $total_width + $total_length;
										if($modReff->jenis_produk == "Plywood" || $modReff->jenis_produk == "Lamineboard" || $modReff->jenis_produk == "Platform"){
											$column = '<th rowspan="2" style="width: 100px; ">Wood<br>Type</th>'
													. '<th rowspan="2" style="width: 120px; ">Glue</th>';
											$colspan_header += 1;
											$colspan_footer += 1;
										}else if($modReff->jenis_produk == "Sawntimber"){
											$column = '<th rowspan="2" style="width: 150px; ">Condition</th>';
										}else if($modReff->jenis_produk == "Moulding"){
											$column = '<th rowspan="2" style="width: 100px; ">Wood<br>Type</th>'
													. '<th rowspan="2" style="width: 175px; ">Profil</th>';
											$colspan_header += 1;
											$colspan_footer += 1;
										}
										if($total_partition > 1){
											$colspan_header += 1;
											$colspan_footer += 1;
										}
										?>
										<table class="table table-striped table-bordered table-advance table-hover table-contrainer" id="table-detail">
											<thead>
												<tr>
													<th rowspan="2" style="width: 30px; line-height:1;">Bundle<br>No.</th>
													<?php if($total_partition > 1){ ?>
													<th rowspan="2" style="width: 50px;" class="kolom-bundle">Part</th>
													<?php } ?>
													<th rowspan="2" style="">Grade</th>
													<?= $column ?>
													<th <?= $thick_span ?> style="width: 80px; line-height: 1;" class="kolom-pcs">Thick<br>(<?= $params_thick_unit ?>)</th>
													<th  <?= $width_span ?> style="width: 80px; line-height: 1;" class="kolom-pcs">Width<br>(<?= $params_width_unit ?>)</th>
													<th  <?= $length_span ?> style="width: 80px; line-height: 1;" class="kolom-pcs">Length<br>(<?= $params_length_unit ?>)</th>
													<th colspan="2" >Total</th>
												</tr>
												<tr>
													<?= $html_thick.$html_width.$html_length; ?>
													<th style=""><?= Yii::t('app', 'Pcs'); ?></th>
													<th style=""><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$total_pcs=0;$total_volume=0;
												if(count($modDetails)>0){
													foreach($modDetails as $v => $detail){
														$subtot_pcs = 0; $subtot_volume = 0; 
														if($modReff->jenis_produk == "Plywood" || $modReff->jenis_produk == "Lamineboard" || $modReff->jenis_produk == "Platform"){
															$column = '<td style="padding: 2px; text-align:center;">'.$detail['jenis_kayu'].'</td>'.
																	  '<td style="padding: 2px; text-align:center;">'.$detail['glue'].'</td>';
														}else if($modReff->jenis_produk == "Sawntimber"){
															$column = '<td style="padding: 2px; text-align:center;">'.$detail['kondisi_kayu'].'</td>';
														}else if($modReff->jenis_produk == "Moulding"){
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
																$sql = "SELECT pcs,volume FROM t_packinglist_container
																		WHERE packinglist_id = ".$modReff->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
																			".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
																			".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
																			".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
																			".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
																			AND thick = {$thick} AND width = {$params_width[0]} AND length = {$params_length[0]}";
																$hmm = Yii::$app->db->createCommand($sql)->queryOne();
																echo	"<td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($hmm['pcs'])."</td>";
																$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
															}else{
																echo	"<td style='text-align:right;' class='font-purple'>".\app\components\DeltaFormatter::formatNumberForUserFloat($thick)."</td>";
															}
														}
														foreach($params_width as $vv => $width){
															if(count($params_width)>1){
																$sql = "SELECT pcs,volume FROM t_packinglist_container
																		WHERE packinglist_id = ".$modReff->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
																			".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
																			".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
																			".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
																			".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
																			AND thick = {$params_thick[0]} AND width = {$width} AND length = {$params_length[0]}";
																$hmm = Yii::$app->db->createCommand($sql)->queryOne();
																echo	"<td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($hmm['pcs'])."</td>";
																$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
															}else{
																echo	"<td style='text-align:right;' class='font-purple'>".\app\components\DeltaFormatter::formatNumberForUserFloat($width)."</td>";
															}
														}
														foreach($params_length as $vv => $length){
															if(count($params_length)>1){
																$sql = "SELECT pcs,volume FROM t_packinglist_container
																		WHERE packinglist_id = ".$modReff->packinglist_id." AND container_no = ".$detail['container_no']." AND bundles_no=".$detail['bundles_no']." AND partition_kode='".$detail['partition_kode']."' AND grade = '".$detail['grade']."' 
																			".( !empty($detail['jenis_kayu'])?"AND jenis_kayu = '{$detail['jenis_kayu']}'":"" )." 
																			".( !empty($detail['glue'])?"AND glue = '{$detail['glue']}'":"" )." 
																			".( !empty($detail['profil_kayu'])?"AND profil_kayu = '{$detail['profil_kayu']}'":"" )." 
																			".( !empty($detail['kondisi_kayu'])?"AND kondisi_kayu = '{$detail['kondisi_kayu']}'":"" )." 
																			AND thick = {$params_thick[0]} AND width = {$params_width[0]} AND length = {$length}";
																$hmm = Yii::$app->db->createCommand($sql)->queryOne();
																echo	"<td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($hmm['pcs'])."</td>";
																$subtot_pcs += $hmm['pcs']; $subtot_volume += $hmm['volume'];
															}else{
																echo	"<td style='text-align:right;' class='font-purple'>".\app\components\DeltaFormatter::formatNumberForUserFloat($length)."</td>";
															}
														}
														echo		"<td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($subtot_pcs)."</td>";
														echo		"<td style='text-align:right;'>".(number_format($subtot_volume,4))."</td>";
														echo "</tr>";
														$total_pcs += $subtot_pcs;  $total_volume += $subtot_volume;
													}
												}
												?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="<?= $colspan_footer ?>" style="text-align: right;"></td>
													<td style="text-align: right;"><?= $total_pcs ?></td>
													<td style="text-align: right;"><?= number_format($total_volume,4) ?></td>
												</tr>
											</tfoot>
										</table><br>
									<?php }else{ ?>
										<h4>Container No. <?= ($i+1); ?></h4>
										<?php
										$sql = "SELECT * FROM t_packinglist_container WHERE packinglist_id = '".$modReff->packinglist_id."' AND container_no = ".($i+1);
										$modDetail = Yii::$app->db->createCommand($sql)->queryAll();
										?>
										<table class="table table-striped table-bordered table-advance table-hover table-contrainer" id="table-detail">
											<thead>
												<tr>
													<th style="width: 30px; line-height:1;">Bundle<br>No.</th>
													<?php
													if(!empty($modDetail[0]['grade'])){
														echo '<th style="">'.Yii::t('app', 'Grade').'</th>';
													}
													if(!empty($modDetail[0]['jenis_kayu'])){
														echo '<th style="line-height:1;">'.Yii::t('app', 'Jenis<br>Kayu').'</th>';
													}
													if(!empty($modDetail[0]['glue'])){
														echo '<th style="">'.Yii::t('app', 'Glue').'</th>';
													}
													if(!empty($modDetail[0]['profil_kayu'])){
														echo '<th style="line-height:1;">'.Yii::t('app', 'Profil<br>Kayu').'</th>';
													}
													if(!empty($modDetail[0]['kondisi_kayu'])){
														echo '<th style="line-height:1;">'.Yii::t('app', 'Kondisi<br>Kayu').'</th>';
													}
													?>
													<th style="">Thick<br>(<?= $modDetail[0]['thick_unit'] ?>)</th>
													<th style="">Width<br>(<?= $modDetail[0]['width_unit'] ?>)</th>
													<th style="">Length<br>(<?= $modDetail[0]['length_unit'] ?>)</th>
													<th style=""><?= Yii::t('app', 'Pcs'); ?></th>
													<th style=""><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
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
															echo "<td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail['thick'])."</td>";
															echo "<td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail['width'])."</td>";
															echo "<td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail['length'])."</td>";
															echo "<td style='text-align:right;'>".\app\components\DeltaFormatter::formatNumberForUserFloat($detail['pcs'])."</td>";
															echo "<td style='text-align:right;'>".(number_format($detail['volume'],4))."</td>";
															echo "</tr>";
															$tot_pcs += $detail['pcs'];
															$tot_volume += $detail['volume'];
														}
													}
												?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="7" style="text-align: right;">TOTAL &nbsp; </td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($tot_pcs) ?></td>
													<td style="text-align: right;"><?= number_format($tot_volume,4) ?></td>
												</tr>
											</tfoot>
										</table><br>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer" style="text-align: center;">
	<?php if( (empty($model->approved_by)) && (empty($model->tanggal_approve)) ){ ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
	<?php } ?>
</div>
<script>

</script>