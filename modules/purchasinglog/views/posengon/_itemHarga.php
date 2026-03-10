<tr style="">
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?php echo \yii\helpers\Html::activeHiddenInput($model, "[ii]panjang") ?>
		<?php echo \yii\helpers\Html::activeHiddenInput($model, "[ii]wilayah") ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <span class="input-group-btn" style="width: 45%">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]diameter_awal',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','onblur'=>'getItemsHarga()']); ?>
        </span>
        <span class="input-group-addon textarea-addon" style="width: 5%; background-color: #fff; border: 0;">
            sd
        </span>
        <span class="input-group-btn" style="width: 45%">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]diameter_akhir',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','onblur'=>'getItemsHarga()']); ?>
        </span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]harga',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','onblur'=>'getItemsHarga()']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red tooltips" data-original-title="Remove" style="padding: 2px;" onclick="cancelItem(this,'getItemsHarga()');"><i class="fa fa-remove"></i></a>
	</td>
</tr>
