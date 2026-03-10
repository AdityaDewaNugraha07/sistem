<?php
$modelSpo = \app\models\TSpo::findOne(['spo_id'=>$model->spo_id]);
$modelApprove = \app\models\TApproval::findOne(['reff_no'=>$modelSpo->spo_kode]);
$modDetail = \app\models\TSpoDetail::find()->where(['spo_id'=>$modelSpo->spo_id])->orderBy(['spo_id'=>SORT_ASC])->all();
//$tanggal_berkas = $modelApprove->spo_kode;
//echo"<pre>";
//print_r($modelApprove->status);
//echo"</pre>";
//exit;
$hide = Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_LOGISTIC ? 'none':'' ; 
?>
<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				
                <h4 class="modal-title"><?= Yii::t('app', 'Approval SPO / PO Bahan Pembantu'); ?> <?php ?></h4>
            </div>
<div class="modal-body" >
	<div class="row">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode PO'); ?></label>
				<div class="col-md-7"><strong><?= $modelApprove->reff_no ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Terbit'); ?></label>
				<div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modelApprove->created_at); ?> WIB</strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Assigned To'); ?></label>
				<div class="col-md-7"><strong><?= $modelApprove->assignedTo->pegawai_nama; ?></strong></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Approved By'); ?></label>
				<div class="col-md-7"><strong><?= !empty($modelApprove->approved_by)?$modelApprove->approvedBy->pegawai_nama:"-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Approve'); ?></label>
				<div class="col-md-7"><strong><?= !empty($modelApprove->tanggal_approve)?app\components\DeltaFormatter::formatDateTimeForUser2($modelApprove->updated_at).' WIB':"-"; ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Status'); ?></label>
				<div class="col-md-7"><strong>
					<?php
					if($modelSpo->cancel_transaksi_id){
						echo '<span class="label label-danger">'.app\models\TCancelTransaksi::STATUS_ABORTED.'</span>';
					}else{
						if($modelApprove->status == \app\models\TApproval::STATUS_APPROVED){
							echo '<span class="label label-success">'.$modelApprove->status.' at: '.app\components\DeltaFormatter::formatDateTimeForUser2($modelApprove->updated_at).' WIB</span>';
						}else if($modelApprove->status == \app\models\TApproval::STATUS_NOT_CONFIRMATED){
							echo '<span class="label label-default">'.$modelApprove->status.'</span>';
						}else if($modelApprove->status == \app\models\TApproval::STATUS_REJECTED){
							echo '<span class="label label-danger">'.$modelApprove->status.' at: '.app\components\DeltaFormatter::formatDateTimeForUser2($modelApprove->updated_at).' WIB</span>';
						}
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
					<div class="caption"> <?= Yii::t('app', 'Show ').\app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($modelApprove->reff_no); ?> </div>
				</div>
				<div class="portlet-body" style="background-color: #d9e2f0" >
					<div class="row">
						<div class="col-md-6">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%"><?= Yii::t('app', 'Kode PO'); ?></td>
									<td style="font-weight: 800;">: <?= $modelSpo->spo_kode; ?></td>
								</tr>
								<tr>
									<td><?= Yii::t('app', 'PO Terbit'); ?></td>
									<td style="font-weight: 800;">: <?= !empty($modelSpo->created_at)?app\components\DeltaFormatter::formatDateTimeForUser2($modelSpo->created_at):"" ?> WIB</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table style="width: 100%; font-size: 1.1rem;">
								<tr>
									<td style="width: 30%"><?= Yii::t('app', 'Supplier'); ?></td>
									<td style="font-weight: 800;">: <?= $modelSpo->suplier->suplier_nm; ?></td>
								</tr>
								<tr>
									<td><?= Yii::t('app', 'Status Bayar'); ?></td>
									<td style="font-weight: 800;">: <?= $modelSpo->spo_status_bayar ?></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="padding-right: 2px; padding-left: 2px;">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
									<thead>
										<tr>
											<th style="width: 30px;">No.</th>
											<th style="width: 35%;"><?= Yii::t('app', 'Nama Item'); ?></th>
											<th style="width: 80px; text-align: center"><?= Yii::t('app', 'Qty<br>Order'); ?></th>
											<th style="width: 80px; text-align: center"><?= Yii::t('app', 'Available<br>Stock'); ?></th>
                                                                                        <th style="display:<?php echo $hide ;?>"><?= Yii::t('app', 'Harga'); ?> <span style="font-size: 1.2rem;">(<?= $modelSpo->defaultValue->name_en ?>)</span></th>
											<th style="display:<?php echo $hide ;?>"><?= Yii::t('app', 'Subtotal'); ?> <span style="font-size: 1.2rem;">(<?= $modelSpo->defaultValue->name_en ?>)</span></th>
											<th><?= Yii::t('app', 'Keterangan'); ?></th>
                                                                                        <th style="display:<?php echo $hide ;?>"><?= Yii::t('app', 'Penawaran'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
                                                                                 
										if(count($modDetail)>0){
											$total = 0;
											foreach($modDetail as $i => $detail){
										?>
											<tr>
												<td style="padding-top: 2px; padding-bottom: 2px;"><?= $i+1 ?></td>
												<td style="padding-top: 2px; padding-bottom: 2px;"><?= $detail->bhp->bhp_nm; ?></td>
												<td style="padding-top: 2px; padding-bottom: 2px;"><center><?= $detail->spod_qty." (".$detail->bhp->bhp_satuan.")"; ?></center></td>
												<td style="padding-top: 2px; padding-bottom: 2px;"><center><?= $detail->bhp->current_stock." (".$detail->bhp->bhp_satuan.")"; ?></center></td>
												<td class="text-align-right" style="padding-top: 2px; padding-bottom: 2px;display:<?php echo $hide ;?>"><?= app\components\DeltaFormatter::formatNumberForUser($detail->spod_harga); ?></td>
												<td class="text-align-right" style="padding-top: 2px; padding-bottom: 2px;display:<?php echo $hide ;?>"><?= app\components\DeltaFormatter::formatNumberForUser($detail->spod_qty * $detail->spod_harga); ?></td>
												<td style="padding-top: 2px; padding-bottom: 2px; font-size: 1.1rem;"><?= $detail->spod_keterangan ?></td>
												<td style="padding-top: 2px; padding-bottom: 2px; font-size: 1rem; text-align: center; line-height: 1;display:<?php echo $hide ;?>"><a onclick="penawaranTerpilih('<?= $detail->spod_id ?>')"><i class="fa fa-info-circle"></i> Lihat<br>Penawaran</a></td>
											</tr>
										<?php
											$total += $detail->spod_qty * $detail->spod_harga;
											}
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5" class="text-align-right" style="padding-top: 3px; padding-bottom: 3px;display:<?php echo $hide ;?>">Total</td>
											<td class="text-align-right" style="padding-top: 3px; padding-bottom: 3px; padding-right: 8px;display:<?php echo $hide ;?>"> <?= app\components\DeltaFormatter::formatNumberForUser($total); ?></td>
										</tr>
										<tr>
											<td colspan="5" class="text-align-right" style="padding-top: 3px; padding-bottom: 3px;display:<?php echo $hide ;?>">Pph</td>
											<td class="text-align-right" style="padding-top: 3px; padding-bottom: 3px; padding-right: 8px;display:<?php echo $hide ;?>"> <?= app\components\DeltaFormatter::formatNumberForAllUser($modelSpo->spo_pph_nominal); ?></td>
										</tr>
										<tr>
											<td colspan="5" class="text-align-right" style="padding-top: 3px; padding-bottom: 3px;display:<?php echo $hide ;?>">Ppn</td>
											<td class="text-align-right" style="padding-top: 3px; padding-bottom: 3px; padding-right: 8px;display:<?php echo $hide ;?>"> <?= app\components\DeltaFormatter::formatNumberForUser($modelSpo->spo_ppn_nominal); ?></td>
										</tr>
										<tr>
											<td colspan="5" class="text-align-right" style="padding-top: 3px; padding-bottom: 3px;display:<?php echo $hide ;?>">Grand Total</td>
											<td class="text-align-right" style="padding-top: 3px; padding-bottom: 3px; padding-right: 8px;display:<?php echo $hide ;?>"> <?= app\components\DeltaFormatter::formatNumberForUser($total + $modelSpo->spo_pph_nominal + $modelSpo->spo_ppn_nominal); ?></td>
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
        </div>
    </div>
</div>
<div class="modal-footer" style="text-align: center;">
	<?php 
//	if(!$modelSpo->cancel_transaksi_id){
//		if( (empty($modelApprove->approved_by)) && (empty($modelApprove->tanggal_approve)) && ($accessible_level) ){
//			echo yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"approve(".$modelApprove->approval_id.")"]);
//			echo yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"reject(".$modelApprove->approval_id.")"]);
//		}
//	}else{
//		echo (!empty($modelSpo->cancel_transaksi_id)?"<b>Cancel Reason : </b><i>".$modelSpo->cancelTransaksi->cancel_reason."</i>":"");
//	}
	?>
</div>
