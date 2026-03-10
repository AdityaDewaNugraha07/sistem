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
						<div class="col-md-5">
							<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
							<?= $form->field($model, 'peruntukan')->dropDownList(['Industri'=>'Industri', 'Trading'=>'Trading'],['prompt'=>'All'])->label('Peruntukan'); ?>
						</div>
						<div class="col-md-5">
							<?php // echo $form->field($model, 'no_barcode')->textInput()->label($label); ?>
							<?= $form->field($model, 'status_approve')->dropDownList(['Not Confirmed'=>'Not Confirmed', 'APPROVED'=>'APPROVED'],['prompt'=>'All'])->label('Status Approval'); ?>
							<div class="form-group">
								<div class="col-md-3">
									<?= \yii\helpers\Html::activeDropDownList($model,'label_no',['no_barcode' => 'No Barcode','no_lap' => 'No Lap',],['class' => 'form-control', 'onchange'=>'setPlaceholder();']) ?>
								</div>
								<div class="col-md-8">
									<?= \yii\helpers\Html::activeTextInput($model,'keyword',['class' => 'form-control','placeholder' => 'Masukkan nomor barcode']) ?>
								</div>
							</div>
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
<script>
	function setPlaceholder(){
		var label_no = $('#<?= yii\bootstrap\Html::getInputId($model, 'label_no');?>').val();
		if(label_no == 'no_barcode'){
			var ph = 'Masukkan nomor barcode';
		} else if (label_no == 'no_lap'){
			var ph = 'Masukkan nomor lapangan';
		}
		$('#<?= yii\bootstrap\Html::getInputId($model, 'keyword');?>').attr('placeholder', ph);
	}
</script>