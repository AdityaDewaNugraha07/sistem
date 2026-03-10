<?php
$modelSpo = \app\models\TSpo::findOne(['spo_id' =>$spo_id]);
$modDetail = \app\models\TSpoDetail::findAll(['spo_id' =>$spo_id, 'bhp_id'=>$bhp_id, 'spod_id'=>$id]);
if(!empty($model->terima_bhpd_id)){
	$modTerimaDetail = \app\models\TTerimaBhpDetail::findOne(['terima_bhpd_id' =>$model->terima_bhpd_id]);
	$modelTrima =  \app\models\TTerimaBhp::findOne(['terima_bhp_id' =>$modTerimaDetail->terima_bhp_id]);
}
?>
<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Informasi Pembelian Detail Purchase Order');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                            <div class="col-md-6"><strong><?= $modelSpo->spo_kode; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Tanggal'); ?></label>
                            <div class="col-md-6"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($modelSpo->spo_tanggal) ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">						
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Created By'); ?></label>
                            <div class="col-md-6"><strong><?= (!empty($modelSpo->created_by)? $modelSpo->spoCreatedBy->pegawai->pegawai_nama:""); ?></strong></div>
                        </div>
						<div class="form-group col-md-12">
                            <label class="col-md-6 control-label"><?= Yii::t('app', 'Penerimaan'); ?></label>
                            <div class="col-md-6">
								<strong>
									<?= (!empty($model->terima_bhpd_id) ? $modelTrima->terimabhp_kode : "-");?>
								</strong>
							</div>
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
										<th style="text-align: center;"><?= Yii::t('app', 'Harga'); ?> <span style="font-size: 1.2rem;" class="place-mata-uang">(<?= $modelSpo->defaultValue->name_en ?>)</span></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Status<br>Garansi'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Suplier'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Keterangan'); ?></th>
										<th style="text-align: center; width: 60px;"><?= Yii::t('app', 'Checked'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($modDetail as $i => $detail){ 
										$mark = '';
										$role = false;
										if($bhp_id == $detail->bhp_id){
                                            $mark = 'background-color:  #fceeb1;';
                                            $role = FALSE;
                                        }
										?>
									<tr style="<?= $mark; ?>">
										<td style="text-align: center;"><?= $i+1; ?></td>
										<td><?= $detail->bhp->bhp_nm; ?></td>
										<td style="text-align: center;"><?= !empty($detail->spod_qty)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->spod_qty):"<center>-</center>"; ?> <?= $detail->bhp->bhp_satuan ?></td>
										<td style="text-align: center;"><?= !empty($detail->spod_harga)?app\components\DeltaFormatter::formatNumberForUserFloat($detail->spod_harga):"<center>-</center>"; ?></td>
										<td><?= ($detail->spod_garansi == 't')?  '<center>Bergaransi</center>' : '<center></center>' ?></td>
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