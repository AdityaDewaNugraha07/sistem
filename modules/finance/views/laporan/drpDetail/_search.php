<?php
\app\assets\Select2Asset::register($this);
\app\assets\DatepickerAsset::register($this);
?>
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
							<?= $form->field($model, 'kode')->dropDownList(\app\models\TPengajuanDrp::getOptionList(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])->label(Yii::t('app', 'Kode DRP'));?>
							<?= $form->field($model, 'status_bayar')->dropDownList(["PAID"=>"PAID","UNPAID"=>"UNPAID"],['prompt'=>'All'])->label(Yii::t('app', 'Status Bayar')); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'kategori')->dropDownList(['DRP Operational'=>'DRP Operational', 'DRP Log Sengon'=>'DRP Log Sengon', 'DRP Log Alam'=>'DRP Log Alam'],['prompt'=>'All'])->label(Yii::t('app', 'Kategori')); ?>
							<?php echo $form->field($model, 'status_approve')->dropDownList([""=>"All", "APPROVED"=>"APPROVED","Not Confirmed"=>"NOT CONFIRMED", 'REJECTED'=>'REJECTED', 'ABORTED'=>'ABORTED'])->label(Yii::t('app', 'Status ')); ?> <!-- ,['prompt'=>'APPROVED'] -->
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
<?php 
$this->registerJs("
    $('.select2').select2({
        // allowClear: !0,
        placeholder: 'Pilih Kode DRP',
        width: null,
		minimumInputLength: 1,
	});
", yii\web\View::POS_READY); ?>