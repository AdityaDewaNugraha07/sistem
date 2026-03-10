<style>
table{
	font-size: 1.4rem;
}
</style>
<div class="modal fade" id="modal-setor" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= $paramprint['judul']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label bold">Kode</label>
							<div class="col-md-7"> :
								<?= $model->kode; ?>
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label bold">Reff. No BCA</label>
							<div class="col-md-7"> :
								<?= $model->reff_no_bank; ?>
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label bold">No. Dok Angkut</label>
							<div class="col-md-7"> :
								<?= $model->reff_no_dokangkut; ?>
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label bold">Tanggal Setoran</label>
							<div class="col-md-7"> :
								<?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?>
							</div>
						</div>
                    </div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label bold">Nominal Setor</label>
							<div class="col-md-7"> :
								<?= app\components\DeltaFormatter::formatNumberForUserFloat($model->nominal); ?>
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-5 control-label bold">Deskripsi</label>
							<div class="col-md-7"> :
								<?= $model->deskripsi; ?>
							</div>
						</div>
					</div>
                </div>
            </div>
            <div class="modal-footer text-align-center" style="padding-top: 10px;">
                <?php // echo yii\helpers\Html::button(Yii::t('app', 'Print'),['class'=>'btn blue-steel ciptana-spin-btn btn-outline','onclick'=>'printSetorbank('.$model->kas_besar_setor_id.')']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<script>
function printSetorbank(){
	
}
</script>