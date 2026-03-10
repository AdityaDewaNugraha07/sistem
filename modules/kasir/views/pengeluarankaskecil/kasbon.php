<div class="modal fade" id="modal-kasbon" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-align-center">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Kasbon Gantung Per ').app\components\DeltaFormatter::formatDateTimeForUser2( $tgl ) ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 100%;">
								<thead>
									<tr>
										<th style="text-align: center; width: 35px;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="text-align: center; width: 75px;"><?= Yii::t('app', 'Kode'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Tanggal'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Penerima'); ?></th>
										<th style="text-align: center; "><?= Yii::t('app', 'Deskripsi'); ?></th>
										<th style="text-align: center; width: 100px;"><?= Yii::t('app', 'Nominal'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$total = 0;
									foreach($models as $i => $model){
										?>
										<tr style="">
											<td class="td-kecil text-align-center"><?= $i+1; ?></td>
											<td class="td-kecil" style="font-weight: bold; text-align: center;"><?= $model->kode; ?></td>
											<td class="td-kecil text-align-center"><?= !empty($model->tanggal_kasbon)?app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_kasbon):app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></td>
											<td class="td-kecil"><?= $model->penerima ?></td>
											<td class="td-kecil" style="font-size: 1.2rem;"><?= $model->deskripsi ?></td>
											<td class="td-kecil text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal); ?></td>
										</tr>
									<?php $total += $model->nominal; } ?>
										<tr style="background-color: #e2e3e5;">
											<td class="td-kecil" colspan="5" style="font-size: 1.2rem; font-weight: bold; text-align: right;">TOTAL</td>
											<td class="td-kecil text-align-right" style="font-weight: bold; "><?= app\components\DeltaFormatter::formatNumberForUserFloat( $total ); ?></td>
										</tr>
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
			<div class="modal-footer text-align-center" style="padding-top: 10px;">
				<?= yii\helpers\Html::button(Yii::t('app', 'Print'),['class'=>'btn blue-steel ciptana-spin-btn btn-outline','onclick'=>'printKasbon("PRINT","'.$models[0]->tanggal.'")']); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Excel'),['class'=>'btn green-seagreen ciptana-spin-btn btn-outline','onclick'=>'printKasbon("EXCEL","'.$models[0]->tanggal.'")']); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'PDF'),['class'=>'btn red-flamingo ciptana-spin-btn btn-outline','onclick'=>'printKasbon("PDF","'.$models[0]->tanggal.'")']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>