<div class="modal fade" id="modal-info-spp" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi SPP'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-6"><strong><?= $model->spp_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-6"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->spp_tanggal) ?></strong></div>
                        </div>
						<div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Dept. Pemesan'); ?></label>
                            <div class="col-md-6"><strong><?= $model->departement->departement_nama; ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Status'); ?></label>
                            <div class="col-md-6"><strong><?= $model->spp_status; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12" style="margin-top: -10px;">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Catatan'); ?></label>
                            <div class="col-md-6"><strong><?= !empty($model->spp_catatan)?$model->spp_catatan:"-"; ?></strong></div>
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
											$searchChecked = \app\models\MapTrackingpembelianChecklist::findOne(['reff_no'=>$model->spp_kode,'bhp_id'=>$detail->bhp_id]);
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
									<tr style="<?= $mark; ?>;">
										<td style="text-align: center;"><?= $i+1; ?></td>
										<td ><?= $detail->bhp->bhp_nm; ?></td>
										<td style="text-align: center;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($detail->sppd_qty) ?></td>
										<td style="font-size: 1.1rem"><?= $detail->sppd_ket ?></td>
										<td style="">
											<?php if( (!empty($mark)) && $role ){ ?>
												<center><input type="checkbox" name="checklist_tracking" id="checklist_tracking" onclick="checklisttracking(this,'<?= $model->spp_kode ?>',<?= $detail->bhp_id ?>)" <?= $checked ?>></center>
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
			<div class="modal-footer" style="text-align: center;">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printSpp('.$model->spp_id.')']); ?>
			</div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>\