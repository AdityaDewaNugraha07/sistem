<?php
\app\assets\Select2Asset::register($this);
app\assets\DatepickerAsset::register($this);
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
                    'method' => 'get',
					'fieldConfig' => [
						'template' => '{label}<div class="col-md-8">{input} {error}</div>',
						'labelOptions'=>['class'=>'col-md-4 control-label'],
					],
					'enableClientValidation'=>false
				]); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-5">
							<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form, 'sizelabel'=>"4",'sizeelement'=>"8"]) ?>
					</div>
				</div>
				<?php echo $this->render('@views/apps/form/tombolSearch') ?>
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
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $('.select2').select2({
        allowClear: !0,
        placeholder: 'Pilih Data',
        width: null 
	});
	formconfig();
", yii\web\View::POS_READY); ?>