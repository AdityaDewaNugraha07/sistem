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
							<?= $form->field($model, 'biaya_grader_kode')->textInput()->label(Yii::t('app', 'Kode')); ?>
							<?= $form->field($model, 'graderlog_id')->dropDownList( \app\models\MGraderlog::getOptionList(),['prompt'=>'All','class'=>'form-control select2'] )->label(Yii::t('app', 'Grader')); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'tipe_dinas')->dropDownList(\app\models\MDefaultValue::getOptionList('tipe-dinas-grader'),['prompt'=>'All'])->label(Yii::t('app', 'Tipe Dinas')); ?>
							<?= $form->field($model, 'wilayah_dinas_id')->dropDownList(\app\models\MWilayahDinas::getOptionList(),['prompt'=>'All'])->label(Yii::t('app', 'Wilayah')); ?>
							<?= $form->field($model, 'status')->dropDownList(\app\models\MDefaultValue::getOptionList('status-bayar'),['prompt'=>'All'])->label(Yii::t('app', 'Status')); ?>
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