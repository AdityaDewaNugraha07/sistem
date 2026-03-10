<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TOpKo::findOne(['kode'=>$model->reff_no]);
$modDetail = \app\models\TOpKoDetail::find()->where(['op_ko_id'=>$modReff->op_ko_id])->orderBy(['op_ko_detail_id'=>SORT_DESC])->all();
$modTempo = \app\models\TTempobayarKo::findOne(['op_ko_id'=>$modReff->op_ko_id]);
$modCustTop = \app\models\MCustTop::findOne(['cust_id'=>$modReff->cust_id,'custtop_jns'=>$modReff->jenis_produk,'active'=>true]);
?>
<style>
.form-group {
    margin-bottom: 0 !important;
}
</style>
<div class="modal-body" >
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-md-6">
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode OP'); ?></label>
				<div class="col-md-7"><strong><?= $model->reff_no ?></strong></div>
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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->jenis_produk ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Customer'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->cust->cust_an_nama ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Status OP'); ?></label>
				<div class="col-md-7"><strong class="font-yellow-gold"><?= $modReff->status ?></strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sistem Bayar'); ?></label>
				<div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;"><strong>
					<?php
					if($modReff->sistem_bayar == "Tempo"){
						echo $modReff->sistem_bayar." - ".$modTempo->top_hari." Hari<br>";
						if(!empty($modCustTop)){
							if($modTempo->top_hari > $modCustTop->custtop_top){
								echo " &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- Max Tempo : ".$modCustTop->custtop_top." Hari</i></span>";
							}
						}
					}else{
						echo "-";
					}
					?>
					</strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Harga'); ?></label>
				<div class="col-md-7" style="line-height: 0.8; margin-bottom: 10px;"><strong>
					<?php
					if($modReff->sistem_bayar == "Tempo"){
						$total = 0;
						$grandtotal = 0;
						if(count($modDetail)>0){
							foreach($modDetail as $i => $detail){
								if($modReff->jenis_produk == "Plywood" || $modReff->jenis_produk == "Lamineboard" || $modReff->jenis_produk == "Platform"){
									$subtotal = $detail->harga_jual * $detail->qty_kecil;
								}else{
									$subtotal = $detail->harga_jual * $detail->kubikasi;
								}
								$total += $subtotal;
							}
						}
						echo \app\components\DeltaFormatter::formatNumberForUserFloat($total);
						$grandtotal = $total + $modTempo->op_aktif + $modTempo->sisa_piutang;
						if($grandtotal > $modTempo->maks_plafon){
							echo "<br> &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- Max Plafon : ".\app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->maks_plafon)."</i></span>";
							if($modTempo->sisa_piutang > 0){
								echo "<br> &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- Piutang Aktif: ".\app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->sisa_piutang)."</i></span>";
							}
							if($modTempo->op_aktif > 0){
								echo "<br> &nbsp;&nbsp; <span style='font-size:1rem;' class='font-red-flamingo'><i>- OP Aktif: ".\app\components\DeltaFormatter::formatNumberForUserFloat($modTempo->op_aktif)."</i></span>";
							}
						}
					}else{
						echo "-";
					}
					?>
					</strong></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Sales'); ?></label>
				<div class="col-md-7"><strong><?= $modReff->sales->sales_nm ?></strong></div>
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
								<table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
									<thead>
										<tr>
											<th style="width: 30px;">No.</th>
											<th style="text-align: center;"><?= Yii::t('app', 'Nama Produk'); ?></th>
											<th style="width: 50px;"><?= Yii::t('app', 'Palet'); ?></th>
											<th style=""><?= Yii::t('app', 'Qty'); ?></th>
											<th style=""><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
											<th style=""><?= Yii::t('app', 'Harga Jual'); ?></th>
											<th style=""><?= Yii::t('app', 'Subtotal'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if(count($modDetail)>0){
											foreach($modDetail as $i => $detail){
												if($modReff->jenis_produk == "Plywood" || $modReff->jenis_produk == "Lamineboard" || $modReff->jenis_produk == "Platform"){
													$subtotal = $detail->harga_jual * $detail->qty_kecil;
												}else{
													$subtotal = $detail->harga_jual * $detail->kubikasi;
												}
										?>
												<tr>
													<td style="text-align: center;"><?= $i+1 ?></td>
													<td style=""><?= $detail->produk->produk_nama; ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar); ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil)." (".$detail->satuan_kecil.")"; ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->kubikasi); ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail->harga_jual); ?></td>
													<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal); ?></td>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="6" style="text-align: right;">TOTAL &nbsp; </td>
											<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total) ?></td>
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
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"approve(".$model->approval_id.")"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"reject(".$model->approval_id.")"]); ?>
	<?php } ?>
</div>
<script>

</script>