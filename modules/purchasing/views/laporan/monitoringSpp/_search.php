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
							<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode Permintaan','model' => $model,'form'=>$form]) ?>
							<?= $form->field($model, 'bhp_nm')->textInput()->label(Yii::t('app', 'Nama BHP')); ?>
							<?= $form->field($model, 'spp_kode')->textInput()->label(Yii::t('app', 'Nomor SPP')); ?>
							<?= $form->field($model, 'bhp_group')->dropDownList(\app\models\MDefaultValue::getOptionList('group-bahan-pembantu'),['prompt'=>'All'])->label(Yii::t('app', 'Kel. BHP')); ?>
						</div>
						<div class="col-md-5">
							<?php echo $form->field($model, 'status_closed')->dropDownList(['Closed'=>'Closed','Open'=>'Open'],['class'=>'form-control','prompt'=>'All'])->label(Yii::t('app', 'Status Permintaan Pembelian')); ?>
							<?= $form->field($model, 'status_pembelian')->dropDownList(\app\models\MDefaultValue::getOptionList('status-pembelianbhp'),['prompt'=>'All'])->label(Yii::t('app', 'Status Pembelian')); ?>
							<?php echo $form->field($model, 'status_pemenuhan')->dropDownList(['Complete'=>'Complete','Partial'=>'Partial','-'=>'-'],['class'=>'form-control','prompt'=>'All'])->label(Yii::t('app', 'Status Pemenuhan')); ?>
							<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionListBHP(),['prompt'=>'All','class'=>'form-control select2'])->label(Yii::t('app', 'Supplier')); ?>
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