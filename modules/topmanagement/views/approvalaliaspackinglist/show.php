<?php
$model = \app\models\TApproval::findOne($approval_id);
$modPackinglist = \app\models\TPackinglist::findOne(['kode'=>$model->reff_no]);
$modPackinglistDetail = \app\models\TPackinglistContainer::findOne(['packinglist_id'=>$modPackinglist->packinglist_id]);
?>
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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Packinglist'); ?></label>
                <div class="col-md-7"><strong><?= $modPackinglist->kode; ?><br><?= $modPackinglist->nomor; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Packinglist'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modPackinglist->tanggal); ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Buyer'); ?></label>
				<div class="col-md-7"><strong><?= $modPackinglist->cust->cust_an_nama; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Contract No.'); ?></label>
				<div class="col-md-7"><strong><?= $modPackinglist->opExport->nomor_kontrak; ?></strong></div>
			</div>
            <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Container'); ?></label>
				<div class="col-md-7"><strong><b><?= $modPackinglist->total_container ?> Container's / <?= $modPackinglistDetail->container_size; ?> Feet</b></strong></div>
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
                    <?php
                    $modGrade = \app\models\TAlias::find()->where("reff_no = '{$model->reff_no}' AND alias_name = 'grade'")->all();
                    $modJenisKayu = \app\models\TAlias::find()->where("reff_no = '{$model->reff_no}' AND alias_name = 'jenis_kayu'")->all();
                    $modGlue = \app\models\TAlias::find()->where("reff_no = '{$model->reff_no}' AND alias_name = 'glue'")->all();
                    $modProfil = \app\models\TAlias::find()->where("reff_no = '{$model->reff_no}' AND alias_name = 'profil_kayu'")->all();
                    ?>
					<div class="row"  style="margin-top: -15px;">
						<div class="col-md-12">
                            <div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										<tr>
                                            <th style="width: 50%">Grade Original Name</th>
                                            <th style="width: 50%">Grade Alias Name</th>
                                        </tr>
									</thead>
									<tbody>
										<?php if(count($modGrade)>0){ foreach($modGrade as $i => $grade){ ?>
                                        <tr>
                                            <td style="padding: 10px; text-align: center;"><?= $grade['value_original'] ?></td>
                                            <td style="padding: 10px; text-align: center;"><?= $grade['value_alias'] ?></td>
                                        </tr>
                                        <?php } } ?>
									</tbody>
									<thead>
										<tr>
                                            <th style="width: 50%">Wood Type Original Name</th>
                                            <th style="width: 50%">Wood Type Alias Name</th>
                                        </tr>
									</thead>
									<tbody>
										<?php if(count($modJenisKayu)>0){ foreach($modJenisKayu as $i => $jeniskayu){ ?>
                                        <tr>
                                            <td style="padding: 10px; text-align: center;"><?= $jeniskayu['value_original'] ?></td>
                                            <td style="padding: 10px; text-align: center;"><?= $jeniskayu['value_alias'] ?></td>
                                        </tr>
                                        <?php } } ?>
									</tbody>
                                    <?php if($modPackinglist->jenis_produk == "Plywood" || $modPackinglist->jenis_produk == "Lamineboard" || $modPackinglist->jenis_produk == "Platform"){ ?>
									<thead>
										<tr>
                                            <th style="width: 50%">Glue Original Name</th>
                                            <th style="width: 50%">Glue Alias Name</th>
                                        </tr>
									</thead>
									<tbody>
										<?php if(count($modGlue)>0){ foreach($modGlue as $i => $glue){ ?>
                                        <tr>
                                            <td style="padding: 10px; text-align: center;"><?= $glue['value_original'] ?></td>
                                            <td style="padding: 10px; text-align: center;"><?= $glue['value_alias'] ?></td>
                                        </tr>
                                        <?php } } ?>
									</tbody>
                                    <?php }else{ ?>
                                    <thead>
										<tr>
                                            <th style="width: 50%">Profil Original Name</th>
                                            <th style="width: 50%">Profil Alias Name</th>
                                        </tr>
									</thead>
									<tbody>
										<?php if(count($modProfil)>0){ foreach($modProfil as $i => $profil){ ?>
                                        <tr>
                                            <td style="padding: 10px; text-align: center;"><?= $profil['value_original'] ?></td>
                                            <td style="padding: 10px; text-align: center;"><?= $profil['value_alias'] ?></td>
                                        </tr>
                                        <?php } } ?>
									</tbody>
                                    <?php } ?>
								</table>
							</div>
                            <table class="table table-striped table-bordered table-advance table-hover" style="width: 100%;">
                                
                            </table>
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