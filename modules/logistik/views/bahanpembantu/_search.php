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
						'template' => '{label}<div class="col-md-7">{input} {error}</div>',
						'labelOptions'=>['class'=>'col-md-4 control-label'],
					],
					'enableClientValidation'=>false
				]); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<?= $form->field($model, 'bhp_nm')->textInput()->label(Yii::t('app', 'Kode / Nama BHP')); ?>
							<?= $form->field($model, 'bhp_group')->dropDownList(app\models\MDefaultValue::getOptionList("group-bahan-pembantu"),['calss'=>'form-control','prompt'=>'All'])->label("Kel. BHP"); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'active')->dropDownList(['active'=>'Active','nonactive'=>'Non-Active'],['calss'=>'form-control','prompt'=>'All'])->label("Status"); ?>
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