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
							<?= $form->field($model, 'kode')->textInput()->label(Yii::t('app', 'Kode')); ?>
							<?= $form->field($model, 'nama_tongkang')->textInput()->label(Yii::t('app', 'Nama Tongkang')); ?>
						</div>
						<div class="col-md-6">
                            <?= $form->field($model, 'jenis')->dropDownList(\app\models\MDefaultValue::getOptionList('shipping_tracking'),['prompt'=>'All','class'=>'form-control select2'] )->label(Yii::t('app', 'Jenis')); ?>
                            <?= $form->field($model, 'lokasi')->textInput()->label(Yii::t('app', 'Lokasi')); ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="tspkshippingtracking-nama_tongkang"></label>
                                <div class="col-md-8"><div style="padding-top: 10px; padding-right: 15px;"><br><br><?php echo $this->render('@views/apps/form/tombolSearch') ?></div></div>
                            </div>
						</div>
					</div>
				</div>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
				<?php \yii\bootstrap\ActiveForm::end(); ?>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>