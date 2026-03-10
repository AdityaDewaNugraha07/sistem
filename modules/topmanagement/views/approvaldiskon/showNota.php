<?php
$model = \app\models\TApproval::findOne($approval_id);
$modReff = \app\models\TNotaPenjualan::findOne(['kode'=>$model->reff_no]);
$modDetail = \app\models\TNotaPenjualanDetail::find()->where(['nota_penjualan_id'=>$modReff->nota_penjualan_id])->orderBy(['nota_penjualan_detail_id'=>SORT_DESC])->all();
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
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Kode Nota'); ?></label>
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
				<div class="col-md-7"><strong><?= $modReff->cust->cust_an_nama ?></strong> <a class="btn btn-xs blue-steel btn-outline tooltips" title="Info Lengkap Customer" onclick="infoCustomer('<?= $modReff->cust_id ?>');"><i class="fa fa-info-circle"></i></a></div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Harga'); ?></label>
				<div class="col-md-7">
					<strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_harga) ?></strong>
				</div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Potongan'); ?></label>
				<div class="col-md-7 font-red-flamingo">
					<strong><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_potongan) ?></strong>
				</div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Total Bayar'); ?></label>
				<div class="col-md-7">
					<strong>
						<?php
						echo \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_bayar);
						?>
					</strong>
				</div>
			</div>
			<div class="form-group col-md-12">
				<label class="col-md-5 control-label"><?= Yii::t('app', 'Keterangan'); ?></label>
				<div class="col-md-7" style="font-size: 1.1rem;">
					<?php echo (!empty($modReff->keterangan_potongan)?'<i>'.$modReff->keterangan_potongan.'</i>':"-"); ?>
				</div>
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
					<div class="caption"> <?= Yii::t('app', 'Show Detail Nota'); ?> </div>
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
                                                
                                                if($modReff->jenis_produk == "JasaKD" || $modReff->jenis_produk == "JasaGesek" || $modReff->jenis_produk == "JasaMoulding"){
                                                    $product_name = $detail->produkJasa->nama;
                                                }else if($modReff->jenis_produk == "Limbah"){
                                                    $product_name = $detail->limbah->limbah_nama;
                                                }else if($modReff->jenis_produk == "Log"){
                                                    $product_name = $detail->log->log_nama;
                                                }else{
                                                    $product_name = $detail->produk->produk_nama;
                                                }
										?>
												<tr>
													<td style="text-align: center;"><?= $i+1 ?></td>
													<td style=""><?= $product_name; ?></td>
													<td style="text-align: right;"><?= ($modReff->jenis_produk == "Log")?"":\app\components\DeltaFormatter::formatNumberForUserFloat($detail->qty_besar); ?></td>
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
											<td colspan="6" style="text-align: right;">TOTAL HARGA &nbsp; </td>
											<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_harga) ?></td>
										</tr>
										<tr>
											<td colspan="6" style="text-align: right;">POTONGAN &nbsp; </td>
											<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_potongan) ?></td>
										</tr>
										<tr>
											<td colspan="6" style="text-align: right;">TOTAL BAYAR&nbsp; </td>
											<td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($modReff->total_bayar) ?></td>
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
	<?= yii\helpers\Html::button(Yii::t('app', 'Approve'),['class'=>'btn hijau btn-outline','onclick'=>"checkApproval(".$model->approval_id.",'approve')"]); ?>
	<?= yii\helpers\Html::button(Yii::t('app', 'Reject'),['class'=>'btn red btn-outline','onclick'=>"checkApproval(".$model->approval_id.",'reject')"]); ?>
	<?php } ?>
</div>
<script>
function infoCustomer(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/marketing/customer/info','id'=>'']) ?>'+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-customer-info").modal('show');
		$("#modal-customer-info").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
</script>