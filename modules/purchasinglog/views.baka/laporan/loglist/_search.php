<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered form-search">
			<div class="portlet-title">
				<div class="tools panel-cari">
					<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
					<span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
				</div>
			</div>
			<div class="portlet-body">
				<?php $form = \yii\bootstrap\ActiveForm::begin([
					'id' => 'form-search-laporan',
					'fieldConfig' => [
						'template' => '{label}<div class="col-md-8">{input} {error}</div>',
						'labelOptions'=>['class'=>'col-md-3 control-label'],
					],
					'enableClientValidation'=>false
				]); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
							<?= $form->field($model, 'loglist_kode')->textInput()->label(Yii::t('app', 'Kode')); ?>
							<?= $form->field($model, 'nomor')->textInput()->label(Yii::t('app', 'No. Kontrak')); ?>
						</div>
						<div class="col-md-6">
                        <?= $form->field($model, 'lokasi_muat')->textInput()->label(Yii::t('app', 'Lokasi Muat')); ?>
                        <?php /*<?= $form->field($model, 'model_ukuran_loglist')->inline(true)->radioList(['2 Diameter'=>'2 Diameter','4 Diameter'=>'4 Diameter'],['onchange'=>'setDiameter()'])->label('Ukuran'); ?> */?>
                        <?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionList("LA"),['prompt'=>'All','class'=>'form-control select2'] )->label(Yii::t('app', 'Supplier')); ?>
                        <?= $form->field($model, 'area_pembelian')->inline(true)->radioList(['Jawa'=>'Jawa','Luar Jawa'=>'Luar Jawa'],['onchange'=>'setDiameter()'])->label('Area');?>
						</div>
					</div>
					<?php echo $this->render('@views/apps/form/tombolSearch') ?>
				</div>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
				<?php \yii\bootstrap\ActiveForm::end(); ?>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>