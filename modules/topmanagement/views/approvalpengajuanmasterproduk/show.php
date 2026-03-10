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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keperluan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->keperluan ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Status Pengajuan'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->status_pengajuan ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
				<div class="col-md-7"><strong><?= ($modReff->keterangan)?$modReff->keterangan:'-' ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
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
					<div class="row">
						<div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px; padding: 5px;">No.</th>
                                            <th style="padding: 5px;">Jenis Produk</th>
                                            <th style="padding: 5px;">Jenis Kayu</th>
											<th style="padding: 5px;">Profile</th>
											<th style="padding: 5px;">Grade</th>
											<!-- <th style="padding: 5px;">Warna Kayu</th> -->
											<!-- <th style="padding: 5px;">Glue</th> -->
											<!-- <th style="padding: 5px;">Kondisi Kayu</th> -->
											<th style="width: 120px; padding: 5px;">T</th>
											<th style="width: 120px; padding: 5px;">L</th>
											<th style="width: 120px; padding: 5px;">P</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										if(count($modDetail)>0){
											foreach($modDetail as $i => $detail){
										?>
                                            <tr>
                                                <td style="text-align: center;"><?= $i+1 ?></td>
                                                <td style="text-align: left;"><?php echo $detail->produk_group; ?></td>
												<td style="text-align: left;"><?php echo $detail->jenis_kayu; ?></td>
                                                <td style="text-align: center;"><?php echo ($detail->profil_kayu)?$detail->profil_kayu:'-'; ?></td>
												<td style="text-align: center;"><?php echo ($detail->grade)?$detail->grade:'-'; ?></td>
												<!-- <td style="text-align: center;"><?php echo ($detail->warna_kayu)?$detail->warna_kayu:'-'; ?></td>
												<td style="text-align: center;"><?php echo ($detail->glue)?$detail->glue:'-'; ?></td>
												<td style="text-align: center;"><?php echo ($detail->kondisi_kayu)?$detail->kondisi_kayu:'-'; ?></td> -->
                                                <td style="text-align: center;"><?php echo number_format($detail->produk_t) .' '. $detail->produk_t_satuan; ?></td>
                                                <td style="text-align: center;"><?php echo number_format($detail->produk_l) .' '. $detail->produk_l_satuan; ?></td>
                                                <td style="text-align: center;"><?php echo number_format($detail->produk_p) .' '. $detail->produk_p_satuan; ?></td>
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
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"confirm(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"confirm(".$model->approval_id.",'reject')"]); ?>
	<?php } ?>
</div>
<script>

</script>