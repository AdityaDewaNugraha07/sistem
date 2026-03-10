<div class="modal fade" id="modal-info-spo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Purchase Order');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-6"><strong><?= $model->spo_kode; ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-6"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->spo_tanggal) ?></strong></div>
                        </div>
                    </div>
                </div>
				<div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-laporan">
								<thead>
									<tr style="background-color: #F1F4F7; ">
										<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Items'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Qty'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Harga'); ?> <span style="font-size: 1.2rem;" class="place-mata-uang">(<?= $model->defaultValue->name_en ?>)</span></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Suplier'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
										<th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($modDetail as $i => $detail){ 
										$mark = '';
										$role = false;
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
												$searchChecked = \app\models\MapTrackingpembelianChecklist::findOne(['reff_no'=>$model->spl_kode,'bhp_id'=>$detail->bhp_id]);
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
										<td><?= $detail->bhp->bhp_nm; ?></td>
										<td style="text-align: center;"><?= !empty($detail->spod_qty)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->spod_qty):"<center>-</center>"; ?> <?= $detail->bhp->bhp_satuan ?></td>
										<td style="text-align: center;"><?= !empty($detail->spod_harga)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->spod_harga):"<center>-</center>"; ?></td>
										<td><?= !empty($detail->spo->suplier_id)?$detail->spo->suplier->suplier_nm:'<center>-</center>' ?></td>
										<td style="font-size: 1.1rem"><?= $detail->spod_keterangan ?></td>
										<td style="">
											<?php if( (!empty($mark)) && $role ){ ?>
												<center><input type="checkbox" name="checklist_tracking" id="checklist_tracking" onclick="checklisttracking(this,'<?= $model->spo_kode ?>',<?= $detail->bhp_id ?>)" <?= $checked ?>></center>
											<?php } ?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>