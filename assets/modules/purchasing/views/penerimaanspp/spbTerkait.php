<div class="modal fade" id="modal-spb-terkait" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'SPB yang terkait pada SPP : ').$model->spp_kode; ?></h4>
            </div>
            <div class="modal-body">
                <div class="portlet light custom-bordered">
					<div class="portlet-title">
						<div class="caption"><?= Yii::t('app', 'SPB Terkait'); ?></div>
						<div class="pull-right">
							<!--<a class="btn btn-icon-only btn-default tooltips" onclick="javascript:void(0);" data-original-title="Print Out"><i class="fa fa-print"></i></a>-->
						</div>
					</div>
					<div class="portlet-body" id="showSPB">
						<div class="table-scrollable">
							<table class="table tracking table-striped table-bordered table-hover">
								<thead>
									<tr style="background-color: #F1F4F7; ">
										<th style="text-align: center;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Kode SPB'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Origin'); ?></th>
										<th style="text-align: center;"><?= Yii::t('app', 'Status'); ?></th>
										<th style="text-align: center;"></th> 
									</tr> 
								</thead> 
								<tbody> 
									<?php 
									foreach($spbs as $i => $spb){ 
										$modSpb = app\models\TSpb::findOne($spb['spb_id']); 
										$highlight = ''; 
										if( Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_SUPER_USER || 
											Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_KADEP_FINNACC || 
											Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_STAFF_FINNACC || 
											Yii::$app->user->identity->user_group_id == app\components\Params::USER_GROUP_ID_KADIV_FINNACC ){
											if(\app\models\MapTrackingpembelianChecklist::checkTrack($modSpb->spb_kode)){
												$highlight = 'background-color:  #F5FCC9;';
											}
										}
									?>
									<tr style="<?= $highlight; ?>">
										<td style="text-align: center;"><?= $i+1; ?></td>
										<td><?= $modSpb->spb_kode.(!empty($modSpb->spb_nomor)?" / ".$modSpb->spb_nomor:""); ?></td>
										<td style="text-align: center;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($modSpb->spb_tanggal) ?></td>
										<td style="text-align: center;"><?= !empty($modSpb->departement_id)?$modSpb->departement->departement_nama:" - "; ?></td>
										<td style="text-align: center;"><?= $modSpb->spb_status ?></td>
										<td style="text-align: center;">
											<a class="btn btn-xs blue-hoki btn-outline" href="javascript:void(0)" onclick="infoSPB(<?= $modSpb->spb_id ?>)">
											<i class="fa fa-info-circle"></i>
											</a>
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