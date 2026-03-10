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
							<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode Input','model' => $model,'form'=>$form]) ?>
							<?php echo $form->field($model, 'suplier_nm')->textInput()->label(Yii::t('app', 'Nama Suplier')); ?>
                            <?= $form->field($model, 'type')->dropDownList(\app\models\MSuplier::getOptionListType(),['prompt'=>'','class'=>'form-control select2'])->label(Yii::t('app', 'Jenis Suplier')); ?>							
						</div>
						<div class="col-md-5">
							<?php echo $form->field($model, 'suplier_nm_company')->textInput()->label(Yii::t('app', 'Nama Perusahaan')); ?>
							<?php echo $form->field($model, 'suplier_almt')->textarea()->label(Yii::t('app', 'Alamat Suplier')); ?>
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