<tr>
	<td style="padding:3px;">
		<?= yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']uangtunai_id') ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '['.$i.']tanggal',['value'=>$tgl]) ?>
		<?= yii\bootstrap\Html::activeTextInput($model, '['.$i.']nominal',['class'=>'form-control text-align-right money-format','style'=>'width:120px;','readonly'=>'readonly']) ?>
	</td>
	<td style="padding:3px;"><?= yii\bootstrap\Html::activeTextInput($model, '['.$i.']qty',['class'=>'form-control text-align-right float','style'=>'width:75px;','onblur'=>'setSubtotal(this)']) ?></td>
	<td style="padding:3px;"><?= yii\bootstrap\Html::activeTextInput($model, '['.$i.']subtotal',['class'=>'form-control text-align-right money-format','style'=>'width:150px;','readonly'=>'readonly']) ?></td>
</tr>