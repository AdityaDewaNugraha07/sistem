<div class="modal fade" id="modal-tanggalselisihclosing" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Listing Selising Antara Tanggal Laporan Dan Tanggal Closing'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-list" style="width: 100%;">
								<thead>
									<tr>
										<th style="text-align: center; width: 10px;"><?= Yii::t('app', 'No.'); ?></th>
										<th style="text-align: center; width: 25px;"><?= Yii::t('app', 'Tanggal Laporan'); ?></th>
										<th style="text-align: center; width: 25px;"><?= Yii::t('app', 'Tanggal Clsoing'); ?></th>
										<th style="text-align: center; width: 30px;"><?= Yii::t('app', 'Selisih'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php if(count($models)>0){ ?>
									<?php foreach($models as $i => $res){ ?>
										<tr>
											<td><?= ($i+1); ?></td>
											<td class="text-align-center"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($res['tgl_laporan']) ?></td>
											<td class="text-align-center"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($res['tgl_closing']) ?></td>
											<td class="text-align-right"><?= $res['selisih_hari']." Hari"; ?></td>
										</tr>
									<?php } ?>
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