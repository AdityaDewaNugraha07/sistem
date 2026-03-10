<tr>
    <td style="vertical-align: top; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: top;" class="td-kecil">
		<?= yii\bootstrap\Html::activeTextarea($model, '[ii]detail_description',['class'=>'form-control','style'=>'padding:3px; height:45px; font-size:1.2rem;']) ?>
    </td>
    <td style="vertical-align: top;" class="td-kecil">
		<?= yii\bootstrap\Html::activeTextarea($model, '[ii]detail_size',['class'=>'form-control','style'=>'padding:3px; height:45px; font-size:1.2rem;']) ?>
    </td>
	<td style="vertical-align: top;" class="td-kecil">
		<?= yii\bootstrap\Html::activeTextInput($model, '[ii]detail_volume',['class'=>'form-control float','style'=>'padding:3px; font-size:1.2rem;','onblur'=>'total();']) ?>
    </td>
	<td style="vertical-align: top;" class="td-kecil">
		<?= yii\bootstrap\Html::activeTextInput($model, '[ii]detail_price',['class'=>'form-control float','style'=>'padding:3px; font-size:1.2rem;','onblur'=>'total();']) ?>
    </td>
	<td style="vertical-align: top;" class="td-kecil">
		<?= yii\bootstrap\Html::activeTextInput($model, '[ii]detail_subtotal',['class'=>'form-control float','style'=>'padding:3px; font-size:1.2rem;','disabled'=>'disabled']) ?>
    </td>
	<td style="vertical-align: top;" class="td-kecil">
		<?= yii\bootstrap\Html::activeTextInput($model, '[ii]detail_lot_code',['class'=>'form-control','style'=>'padding:3px; font-size:1.2rem; font-weight:600; text-align:center;']) ?>
    </td>
	<td style="vertical-align: top;" class="td-kecil">
		<?= yii\bootstrap\Html::activeTextarea($model, '[ii]shipment_time',['class'=>'form-control','style'=>'padding:3px; font-size:1.1rem; height:45px;']) ?>
    </td>
	<td style="vertical-align: top;" class="td-kecil">
		<a class="btn btn-xs red-flamingo" onclick="cancelItem(this,'total();')"><i class="fa fa-trash-o"></i></a>
    </td>
</tr>
