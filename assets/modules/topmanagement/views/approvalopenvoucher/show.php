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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tipe'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->tipe ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($modReff->tanggal) ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Departement'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->departement_nama ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Reff No'); ?></label>
				<div class="col-md-7"><strong><?= !empty($modReff->reff_no)?$modReff->reff_no:"-" ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Penerima Pembayaran'); ?></label>
                <div class="col-md-7"><strong><?php
                if($modReff->tipe == "REGULER"){
                    $modPenerimaVoucher = \app\models\MPenerimaVoucher::findOne($modReff->penerima_voucher_id);
                    echo $modPenerimaVoucher->nama_penerima." - ".$modPenerimaVoucher->nama_perusahaan;
                }else if($modReff->tipe == "PEMBAYARAN LOG ALAM"){
                    $modSuplier = \app\models\MSuplier::findOne($modReff->penerima_reff_id);
                    echo $modSuplier->suplier_nm." - ".$modSuplier->suplier_nm_company;
                }else if($modReff->tipe == "DEPOSIT SUPPLIER LOG"){
                    $modSuplier = \app\models\MSuplier::findOne($modReff->penerima_reff_id);
                    echo $modSuplier->suplier_nm." - ".$modSuplier->suplier_nm_company;
                }else if($modReff->tipe == "DP LOG SENGON" || $modReff->tipe == "PELUNASAN LOG SENGON"){
                    $modSuplier = \app\models\MSuplier::findOne($modReff->penerima_reff_id);
                    echo $modSuplier->suplier_nm." - ".$modSuplier->suplier_nm_company;
                    echo "<br>";
                    echo '<a id="btn-reff-1" class="btn btn-outline btn-xs purple" onclick="detailPoByKode(\''.$modReff->reff_no.'\')"><i class="icon-tag"></i> Lihat PO</a>';
                }
                ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Pembayaran'); ?></label>
                <div class="col-md-7"><strong><?= number_format($modReff->total_pembayaran) ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
            <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Cara Bayar'); ?></label>
                <div class="col-md-7"><strong><?= $modReff->cara_bayar ?></strong></div>
			</div>
            <div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
                <div class="col-md-7"><strong><?= $modReff->keterangan ?></strong></div>
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
					<div class="row">
						<div class="col-md-12">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px; padding: 5px;">No.</th>
                                            <th style="padding: 5px;">Deskripsi</th>
                                            <th style="width: 100px; padding: 5px;">Nominal</th>
                                            <th style="width: 100px; padding: 5px;">PPn</th>
                                            <th style="width: 100px; padding: 5px;">Pph</th>
                                            <th style="width: 120px; padding: 5px;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										if(count($modDetail)>0){
											foreach($modDetail as $i => $detail){
										?>
                                            <tr>
                                                
                                                <td style="text-align: center;"><?= $i+1 ?></td>
                                                <td style="text-align: left;"><?= $detail->deskripsi; ?></td>
                                                <td style="text-align: right;"><?= number_format($detail->nominal); ?></td>
                                                <td style="text-align: right;"><?= number_format($detail->ppn); ?></td>
                                                <td style="text-align: right;"><?= number_format($detail->pph); ?></td>
                                                <td style="text-align: right;"><?= number_format($detail->subtotal); ?></td>
                                                
                                            </tr>
                                        <?php
											}
										}
										?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" style="vertical-align: middle; text-align: right;">Total DPP </td>
                                            <td style="vertical-align: middle; text-align: right;"><?= number_format($modReff->total_dpp) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="vertical-align: middle; text-align: right;">Total PPn </td>
                                            <td style="vertical-align: middle; text-align: right;"><?= number_format($modReff->total_ppn) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="vertical-align: middle; text-align: right;">Total Pph </td>
                                            <td style="vertical-align: middle; text-align: right;"><?= number_format($modReff->total_pph) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="vertical-align: middle; text-align: right;">Potongan </td>
                                            <td style="vertical-align: middle; text-align: right;"><?= number_format($modReff->total_potongan) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="vertical-align: middle; text-align: right;">Biaya Tambahan </td>
                                            <td style="vertical-align: middle; text-align: right;"><?= number_format($modReff->biaya_tambahan) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="vertical-align: middle; text-align: right;" class="font-red-flamingo">TOTAL PEMBAYARAN</td>
                                            <td style="vertical-align: middle; text-align: right;" class="font-red-flamingo"><?= number_format($modReff->total_pembayaran) ?></td>
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