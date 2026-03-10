<div data-repeater-list="<?= \yii\helpers\StringHelper::basename(get_class($modDetail));  ?>">
	<?php foreach( yii\helpers\Json::decode($modPO->diameter_harga) as $i => $po ){ ?>
	<div data-repeater-item style="display: block; width: 160px">
		<span class="input-group-btn" style="width: 70px;">
			<?php echo \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]diameter_range', \app\models\MDefaultValue::getOptionList('diameter-range-sengon'),['class'=>'form-control','value'=>$i]); ?>
		</span>
		<span class="input-group-btn" style="width: 60px;">
			<?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]diameter_value',['class'=>'form-control float','placeholder'=>'m3','onblur'=>'total(this)','value'=>0]) ?>
		</span>
		<span class="input-group-btn" style="width: 30px;" id="remove-btn">
			<a href="javascript:;" data-repeater-delete class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></a>
		</span>
	</div>
	<?php } ?>
</div>
<a href="javascript:;" data-repeater-create class="btn btn-xs btn-info mt-repeater-add" style="margin-top: 5px; margin-bottom: 10px;">
	<i class="fa fa-plus"></i> <?= Yii::t('app', 'add'); ?>
</a>