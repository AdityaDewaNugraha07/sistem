<div class="modal fade" id="modal-input-keterangan" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Keterangan Berkas');?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-10">
						<center>
							<?= yii\helpers\Html::activeTextarea($model, "keterangan_berkas",['class'=>'form-control','placeholder'=>'Inputkan keterangan berkas disini','value'=>$value]) ?>
						</center>
					</div>
					<div class="col-md-1"></div>
                </div>
				<br><br>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>