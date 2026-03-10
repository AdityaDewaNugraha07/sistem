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
							<?= $form->field($model, 'nomor_produksi')->textInput()->label(Yii::t('app', 'No. Produksi')); ?>
							<?= $form->field($model, 'nomor_batang')->textInput()->label(Yii::t('app', 'No. Batang')); ?>
							<?= $form->field($model, 'kayu_id')->dropDownList(\app\models\MKayu::getOptionList(),['prompt'=>'All','class'=>'form-control select2'] )->label(Yii::t('app', 'Kayu')); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'volume_range')->dropDownList(\app\models\MDefaultValue::getOptionList('volume-range-log'),['prompt'=>'All'])->label(Yii::t('app', 'Diameter')); ?>
							<?= $form->field($model, 'is_freshcut')->dropDownList(['true'=>'Freshcut','false'=>'Non Freshcut'],['prompt'=>'All'])->label(Yii::t('app', 'Fresh Cut')); ?>
							<?= $form->field($model, 'pihak1_perusahaan')->dropDownList(\app\models\TLogKontrak::getOptionListPerusahaan(),['prompt'=>'All','class'=>'form-control select2', 'onchange'=>'setNoKontrak();'])->label(Yii::t('app', 'Nama Perusahaan')); ?>
							<?= $form->field($model, 'log_kontrak_id')->dropDownList([],['prompt'=>'All', 'class'=>'form-control select2'])->label(Yii::t('app', 'No. Kontrak')); ?>
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