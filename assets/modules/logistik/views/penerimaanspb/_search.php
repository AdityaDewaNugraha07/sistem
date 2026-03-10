<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered form-search">
			<div class="portlet-title">
				<div class="tools panel-cari">
					<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
					<span style=""> <?= Yii::t('app', '&nbsp;Advance Search'); ?></span>
				</div>
			</div>
			<div class="portlet-body" style="">
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
							<?= $form->field($model, 'spb_kode')->textInput()->label(Yii::t('app', 'Kode SPB')); ?>
							<?= $form->field($model, 'departement_id')->dropDownList(\app\models\MDepartement::getOptionList(),['prompt'=>'All','onchange'=>'setDropdownPegawai()'])->label(Yii::t('app', 'Departement')); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'spb_status')->dropDownList(\app\models\MDefaultValue::getOptionList('spb-status'),['prompt'=>'All'])->label(Yii::t('app', 'Status SPB')); ?>
							<?= $form->field($model, 'approve_status')->dropDownList(\app\models\MDefaultValue::getOptionList('approve_status'),['prompt'=>'All'])->label(Yii::t('app', 'Approve Status')); ?>
							<?= $form->field($model, 'spb_diminta')->dropDownList( [],['class'=>'form-control select2','prompt'=>'All'] )->label(Yii::t('app', 'Pegawai Pemesan')); ?>
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