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
							<div class="form-group">
								<label class="col-md-3 control-label">Per Tanggal</label>
								<div class="col-md-8">
									<span class="input-group-btn" style="width: 50%">
										<?= $form->field($model, 'per_tanggal',[
													'template'=>'<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime">{input} <span class="input-group-addon">
																 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
																 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
									</span>
								<span class="help-block"></span>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionListBHP(),['prompt'=>'All'])->label(Yii::t('app', 'Supplier')); ?>
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