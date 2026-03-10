<div class="modal fade" id="modal-info-tbp" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi TBP / LPB'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-7"><strong><?= $model->terimabhp_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-6"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tglterima) ?></strong></div>
                        </div>
						<?php if(!empty($model->cancel_transaksi_id)){ ?>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label"><?= Yii::t('app', 'Status'); ?></label>
							<div class="col-md-6"><strong><span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED ?></span></strong></div>
						</div>
						<?php } ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'No. Invoice'); ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->nofaktur)?$model->nofaktur:"<center>-</center>"; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Supplier'); ?></label>
                            <div class="col-md-7"><strong><?= (!empty($model->suplier)?$model->suplier->suplier_nm:""); ?></strong></div>
                        </div>
                    </div>
                </div>
				<!--asdasd-->
				<div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-laporan">
								<thead>
									<tr style="background-color: #F1F4F7; ">
										<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Items'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Harga'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Subtotal'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
										<th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$total = 0;
									$pph = 0;
									$pbbkb = !empty($model->total_pbbkb)?$model->total_pbbkb:0;
									foreach($modDetail as $i => $detail){ 
									$mark = '';
									if( \Yii::$app->controller->id == 'tracking' ){
										$mod = [];
										if($spo_id){
											$sql = "SELECT * FROM t_spo_detail WHERE spo_id = ".$spo_id." AND bhp_id = ".$detail->bhp_id;
											$mod = Yii::$app->db->createCommand($sql)->queryOne();
										}else if($spl_id){
											$sql = "SELECT * FROM t_spl_detail WHERE spl_id = ".$spl_id." AND bhp_id = ".$detail->bhp_id;
											$mod = Yii::$app->db->createCommand($sql)->queryOne();
										}else if($bhp_id == $detail->bhp_id){
											$mark = 'background-color:  #fceeb1;';
											$role = FALSE;
										}
										if(!empty($mod)){
											$mark = 'background-color:  #fceeb1;';
											$checked = '';
											$searchChecked = \app\models\MapTrackingpembelianChecklist::findOne(['reff_no'=>$model->terimabhp_kode,'bhp_id'=>$detail->bhp_id]);
											if(!empty($searchChecked)){
												if($searchChecked->checked === TRUE){
													$checked = 'checked';
													$mark = 'background-color:  #A6C054;';
												}
											}
											if( Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER ||
												Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_KADEP_FINNACC ||
												Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_STAFF_FINNACC ||
												Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_KADIV_FINNACC ){
												$role = TRUE;
											}else{
												$role = FALSE;
											}
										}
									}
									?>
									<tr style="<?= $mark; ?>">
										<td style="text-align: center;"><?= $i+1; ?></td>
										<td ><?= $detail->bhp->bhp_nm; ?></td>
										<td style="text-align: center;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_qty) ?></td>
										<td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga) ?></td>
										<td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->terimabhpd_harga * $detail->terimabhpd_qty) ?></td>
										<td style="font-size: 1.1rem"><?= $detail->terimabhpd_keterangan ?></td>
										<td style="">
											<?php if( (!empty($mark)) && $role ){ ?>
												<center><input type="checkbox" name="checklist_tracking" id="checklist_tracking" onclick="checklisttracking(this,'<?= $model->terimabhp_kode ?>',<?= $detail->bhp_id ?>)" <?= $checked ?>></center>
											<?php } ?>
										</td>
									</tr>
									<?php
									$total += $detail->terimabhpd_harga * $detail->terimabhpd_qty;
									$pph += $detail->pph_peritem;
									} ?>
									<tr style="font-weight: bold; text-align: right; ">
										<td colspan="4" style="padding: 3px;">Ppn &nbsp; </td>
										<td style="padding: 3px;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->ppn_nominal); ?></td>
									</tr>
									<tr style="font-weight: bold; text-align: right; ">
										<td colspan="4" style="padding: 3px;">Pph &nbsp; </td>
										<td style="padding: 3px;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($pph); ?></td>
									</tr>
									<?php if($pbbkb>0){ ?>
									<tr style="font-weight: bold; text-align: right; ">
										<td colspan="4" style="padding: 3px;">Pbbkb &nbsp; </td>
										<td style="padding: 3px;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($pbbkb); ?></td>
									</tr>
									<?php } ?>
									<?php if($model->total_biayatambahan>0){ ?>
									<tr style="text-align: right;">
                                        <td colspan="4" style="padding: 3px; font-size: 1rem;  line-height: 0.9;"><b>Biaya Tambahan</b> <br><?= $model->label_biayatambahan ?></td>
										<td style="padding: 3px; font-weight: bold;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->total_biayatambahan); ?></td>
									</tr>
									<?php } ?>
									<tr style="font-weight: bold; text-align: right;">
										<td colspan="4" style="padding: 3px;">Total &nbsp; </td>
										<td style="padding: 3px;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->totalbayar); ?></td> <!-- langsung ambil ke table induk t_terima_bhp 26/6/19 -->
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
            </div>
			<div class="modal-footer" style="text-align: center;">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printTbp('.$model->terima_bhp_id.')']); ?>
			</div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>\