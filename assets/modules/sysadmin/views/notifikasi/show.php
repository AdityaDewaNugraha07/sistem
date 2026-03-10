<div class="modal fade" id="modal-notif" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Notifikasi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-master">
								<thead>
									<tr>
										<th><?= Yii::t('app', 'ID Pegawai') ?></th>
										<th><?= Yii::t('app', 'Nama Pegawai') ?></th>
										<th><?= Yii::t('app', 'Jenis Kalamin') ?></th>
										<th><?= Yii::t('app', 'Departement') ?></th>
										<th><?= Yii::t('app', 'Jabatan') ?></th>
										<th><?= Yii::t('app', 'Status') ?></th>
										<th style="width: 50px;"></th>
									</tr>
								</thead>
							</table>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php // echo yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/departement/delete','id'=>$model->departement_id,'tableid'=>'table-master'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>