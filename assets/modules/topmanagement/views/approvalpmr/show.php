<style>
.form-group {
    margin-bottom: 0 !important;
}
table.table-striped thead tr th{
	padding : 3px !important;
}
.table-striped, 
.table-striped > tbody > tr > td, 
.table-striped > tbody > tr > th, 
.table-striped > tfoot > tr > td, 
.table-striped > tfoot > tr > th, 
.table-striped > thead > tr > td, 
.table-striped > thead > tr > th {
    border: 1px solid #A0A5A9;
	line-height: 0.9 !important;
	font-size: 1.2rem;
}
</style>
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->kode ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal) ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Log'); ?></label>
                <div class="col-md-7"><strong><?= ($modReff->jenis_log == "LA")?"Log Alam":"Log Sengon" ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kebutuhan Untuk'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->tujuan ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Dibutuhkan'); ?></label>
				<div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser($modReff->tanggal_dibutuhkan_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser($modReff->tanggal_dibutuhkan_akhir) ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Dibuat Oleh'); ?></label>
                <div class="col-md-7"><strong><?= $modReff->dibuatOleh->pegawai_nama ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= $modReff->attributeLabels()['keterangan'] ?></label>
				<div class="col-md-7"><strong><?= $modReff->keterangan; ?></strong></div>
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
				<label class="col-md-5 control-label"><?= $model->attributeLabels()['level'] ?></label>
				<div class="col-md-7"><strong><?= $model->level; ?></strong></div>
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
					<div class="row"  style="margin-top: -15px;">
						<?php
						$approver_1 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->approver_1)->queryOne();
						$approver_2 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->approver_2)->queryOne();
                        if(!empty($modReff->approver_3)){
                            $approver_3 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->approver_3)->queryOne();
                        }
                        if(!empty($modReff->approver_4)){
                            $approver_4 = Yii::$app->db->createCommand("SELECT * FROM t_approval WHERE reff_no = '{$model->reff_no}' AND assigned_to = ".$modReff->approver_4)->queryOne();
                        }
						$reasons = \yii\helpers\Json::decode($model->keterangan);
						?>
						<br>
						<div class="col-md-4">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= ($modReff->tujuan == "INDUSTRI")?"Kadiv Opr":"Kadiv Mkt" ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->approver_1)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($approver_1['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver_1['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($approver_1['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_1['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver_1['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}?>
										</span>
									</td>
								</tr>
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= ($modReff->tujuan == "INDUSTRI")?"GM Opr":"Dirut" ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->approver_2)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($approver_2['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_2['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver_2['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($approver_2['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_2['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver_2['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											}?>
										</span>
									</td>
								</tr>
							</table>
						</div>
                        <div class="col-md-4">
                            <?php if(!empty($modReff->approver_3)){ ?>
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= ($modReff->tujuan == "INDUSTRI")?"Dirut":"Owner" ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->approver_3)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($approver_3['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_3['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver_3['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($approver_3['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_3['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver_3['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											} ?>
										</span>
									</td>
								</tr>
							</table>
                            <?php } ?>
                            <?php if(!empty($modReff->approver_4)){ ?>
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%; vertical-align: top; padding: 3px;"><?= Yii::t('app', 'Owner'); ?></td>
									<td style="font-weight: 800; line-height: 0.9; padding: 5px;">: 
										<?= \app\models\MPegawai::findOne($modReff->approver_4)->pegawai_nama; ?>
										<span style="font-weight: 500; font-size: 1rem;">
											<?php if($approver_4['status']==\app\models\TApproval::STATUS_APPROVED){
												echo " <br>&nbsp; <span class='font-green-seagreen'> ".\app\models\TApproval::STATUS_APPROVED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_4['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver_4['assigned_to']){
															echo " <br>&nbsp; <span class='font-green-seagreen'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else if($approver_4['status']==\app\models\TApproval::STATUS_REJECTED){
												echo " <br>&nbsp; <span class='font-red-flamingo'> ".\app\models\TApproval::STATUS_REJECTED." at ".
														\app\components\DeltaFormatter::formatDateTimeForUser2($approver_4['updated_at'])."</span>";
												if(count($reasons)>0){
													foreach($reasons as $i => $reason){
														if($reason['by']==$approver_4['assigned_to']){
															echo " <br>&nbsp; <span class='font-red-flamingo'> Reason : <i>".$reason['reason']."</i></span>";
														}
													}
												}
											}else {
												echo "<br>&nbsp; <i>(Not Confirm)</i>";
											} ?>
										</span>
									</td>
								</tr>
							</table>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail-industri">
									<thead>
                                        <?php $ukuranganrange = ($modReff->jenis_log=="LA")?\app\models\MDefaultValue::getOptionList('volume-range-log'):\app\models\MDefaultValue::getOptionList('log-sengon-panjang'); ?>
                                        <tr>
                                            <th style="width: 30px;" rowspan="2" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                            <th style="width: 240px;" rowspan="2"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                            <th colspan="<?= count($ukuranganrange)+1 ?>">Qty M<sup>3</sup> By <?= ($modReff->jenis_log=="LA")?"Diameter Range":"Panjang Log" ?></th>
                                            <th style="" rowspan="2"><?= Yii::t('app', 'Keterangan'); ?></th>
                                        </tr>
                                        <tr>
                                            <?php foreach($ukuranganrange as $i => $range){ ?>
                                            <th style="width: 110px;"><?= $range ?> cm</th>
                                            <?php } ?>
                                            <th style="width: 120px;">Total M<sup>3</sup></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$total_m3 = 0;
										foreach($ukuranganrange as $i => $range){
											$total_ver_m3[$range] = 0;
										}
                                        if($modReff->jenis_log=="LA"){
                                            $column_name = "diameter_range";
                                        }else{
                                            $column_name = "panjang";
                                        }
                                        $modDetail = \app\models\TPmrDetail::find()
                                                            ->select("pmr_id, kayu_id, keterangan")
                                                            ->groupBy("pmr_id, kayu_id, keterangan")
                                                            ->where(['pmr_id'=>$modReff->pmr_id])->all();
										foreach($modDetail as $i => $detail){
											echo "<tr>";
											echo	"<td>".($i+1)."</td>";
											echo	"<td>".$detail->kayu->group_kayu." - ".$detail->kayu->kayu_nama."</td>";
												$subtotal_btg = 0; $subtotal_m3 = 0; $subtotal_harga=0;
												foreach($ukuranganrange as $i => $range){
													$sql = "SELECT SUM(qty_m3) AS qty_m3 FROM t_pmr_detail 
															WHERE pmr_id = {$modReff->pmr_id} AND kayu_id = {$detail->kayu_id} AND {$column_name} = '{$range}'";
													$modQty = Yii::$app->db->createCommand($sql)->queryOne();
													echo "<td class='text-align-right'>". number_format($modQty['qty_m3'])." M<sup>3</sup></td>";
													$subtotal_m3 += $modQty['qty_m3'];
													$total_ver_m3[$range] += $modQty['qty_m3'];
													
												}
												$total_m3 += $subtotal_m3;
                                            echo    "<td class='text-align-right'>". number_format($subtotal_m3)." M<sup>3</sup></td>";
                                            echo    "<td class='text-align-center'>".$detail['keterangan']."</td>";
											echo "</tr>";
										}
										?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="text-align: right;">&nbsp; </td>
                                            <?php 
                                            foreach($ukuranganrange as $i => $range ){
                                                echo "<td class='text-align-right'>".number_format($total_ver_m3[$range])." M<sup>3</sup></td>";
                                            }
                                            echo "<td class='text-align-right'>".number_format($total_m3)." M<sup>3</sup></td>";
                                            ?>
                                        </tr>
                                    </tfoot>
								</table>
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