<style>
.form-group {
    margin-bottom: 0 !important;
}
</style>
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Pengajuan'); ?></label>
				<div class="col-md-7">
                    <strong><?= $model->reff_no ?> 
                    <a class="btn btn-xs btn-outline blue-hoki" id="btn-info" onclick="infoFormPengajuanDinas(<?= $modReff->ajuandinas_grader_id ?>);"><i class="fa fa-info-circle"></i></a>
                    </strong>
                </div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Pengajuan'); ?></label>
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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Grader'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->graderlog->graderlog_nm ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Dinas'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->dkg->kode ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Wilayah Dinas'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->wilayahDinas->wilayah_dinas_nama ?> (<?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->wilayah_dinas_plafon) ?>)</strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Saldo Terakhir'); ?></label>
                <div class="col-md-7"><strong><?= number_format($modReff->saldo_sebelumnya) ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Ajuan'); ?></label>
				<div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_ajuan) ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Dibutuhkan'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal_dibutuhkan); ?></strong></div>
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
					<div class="caption"> <?= Yii::t('app', 'Show Realisasi Kas Grader'); ?> </div>
				</div>
				<div class="portlet-body" style="background-color: #d9e2f0" >
					<div class="row">
						<div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
									<thead>
										<tr>
											<th style="width: 30px;">No.</th>
											<th style="text-align: center; width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
											<th style=""><?= Yii::t('app', 'Periode'); ?></th>
											<th style="width: 100px;"><?= Yii::t('app', 'Total Realisasi'); ?></th>
											<th style="width: 50px;">Detail</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if(count($modDetail)>0){
											foreach($modDetail as $i => $detail){
										?>
											<tr>
												<td style=""><?= $i+1 ?></td>
												<td style=""><?= $detail->kode; ?></td>
												<td style="">
													<?=
														\app\components\DeltaFormatter::formatDateTimeForUser($detail->periode_awal)." sd ".
														\app\components\DeltaFormatter::formatDateTimeForUser($detail->periode_akhir);
													?>
												</td>
												<td style=""><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->total_realisasi) ?></td>
												<td>
													<a class="btn btn-xs btn-outline blue-hoki" id="btn-info" onclick="detailRealisasiDinas(<?= $detail->realisasidinas_grader_id ?>);"><i class="fa fa-info-circle"></i></a>
												</td>
											</tr>
										<?php
											}
										}
										?>
									</tbody>
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
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"approve(".$model->approval_id.")"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"reject(".$model->approval_id.")"]); ?>
	<?php } ?>
</div>
<script>
function infoFormPengajuanDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailAjuanDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-ajuandinas';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function detailRealisasiDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailRealisasiDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasidinas';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
</script>