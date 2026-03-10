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
							<?= $form->field($model, 'terimabhp_kode')->textInput()->label(Yii::t('app', 'Kode TBP')); ?>
							<?= $form->field($model, 'kode_voucher')->textInput()->label(Yii::t('app', 'Kode Voucher')); ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'nofaktur')->textInput()->label(Yii::t('app', 'No. Invoice/Nota')); ?>
							<?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionList(),['prompt'=>'All'])->label(Yii::t('app', 'Supplier')); ?>
							<?= $form->field($model, 'bhp_id')->dropDownList([],['prompt'=>'All'])->label(Yii::t('app', 'Nama Items')); ?>
							<?php // echo $form->field($model, 'payment_status')->dropDownList(['PAID'=>'PAID','UNPAID'=>'UNPAID'],['prompt'=>'All'])->label(Yii::t('app', 'Status')); ?>
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