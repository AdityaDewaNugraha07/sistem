<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-spp',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); ?>
<div class="row">
	<div class="col-md-12">
		<?php 
		echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold'])->label(Yii::t('app', 'Kode Adjustment'));
		echo $form->field($model, 'tanggal')->textInput(['disabled'=>'disabled']);
		echo $form->field($model, 'bhp_nm')->textInput(['disabled'=>'disabled'])->label('Item Barang');
		?>
		<div class="form-group">
			<label class="col-md-4 control-label"><?= Yii::t('app', 'Qty in'); ?></label>
			<div class="col-md-8" style="padding-bottom: 5px;">
				<span class="input-group-btn" style="width: 90%">
					<?= \yii\bootstrap\Html::activeTextInput($model, 'qty_in', ['class'=>'form-control','style'=>'width:100%']) ?>
				</span>
			</div>
		</div>
	</div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>