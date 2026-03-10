<?php app\assets\DatepickerAsset::register($this); ?>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-cancel',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-7">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<div class="row">
					<div class="col-md-9">
						<h4 class="modal-title"><?= Yii::t('app', $pesan); ?></h4>
					</div>
					<div class="col-md-2 pull-right">
						<h4 class="modal-title"><strong style="background-color:#FBE88C"><?= ($model->tipe=='Kas Besar'?"Kas Besar":"Kas Kecil"); ?></strong></h4>
					</div>
				</div>
            </div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-4 control-label">Kode BKK :</label>
							<div class="col-md-7" style="margin-top: -5px;">
								<h4><?= $modVoucher->kode; ?></h4>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Nominal Bayar :</label>
							<div class="col-md-7" style="margin-top: -5px;">
								<h4><?= app\components\DeltaFormatter::formatNumberForUser($modVoucher->total_nominal); ?></h4>
							</div>
						</div>
						<?php
						if($modVoucher->cara_bayar == "Cek"){
						?>
						<div class="form-group">
							<label class="col-md-4 control-label">No Cek :</label>
							<div class="col-md-7" style="margin-top: -5px;">
								<h4><?= $modVoucher->cara_bayar_reff; ?></h4>
							</div>
						</div>
						<?php } ?>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-4 control-label">Tanggal Bayar :</label>
							<div class="col-md-7" style="margin-top: -5px;">
								<h4><?= app\components\DeltaFormatter::formatDateTimeForUser2($modVoucher->tanggal_bayar); ?></h4>
							</div>
						</div>
						<?= $form->field($modKasKecil, 'tanggal',[
                            'template'=>'{label}<div class="col-md-4"><div class="input-group input-medium date date-picker bs-datetime" style="width:180px !important;">{input} <span class="input-group-addon">
								<button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
								{error}</div>'])->textInput(['readonly'=>'readonly'])->label("Tanggal Terima :"); ?>
					</div>
				</div>
			</div>
            <div class="modal-footer">
				<?= \yii\bootstrap\Html::activeHiddenInput($model, 'ppk_id') ?>
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Ya'),['class'=>'btn hijau btn-outline ciptana-spin-btn',
                    'onclick'=>'submitformajax(this,"$(\'#close-btn-modal\').removeAttr(\'disabled\'); $(\'#close-btn-modal\').trigger(\'click\'); $(\'#table-ppk\').dataTable().fnClearTable();  ")'
                    ]);
				?>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
", yii\web\View::POS_READY); ?>
<script>

</script>