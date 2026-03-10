<tr>
    <td class="td-kecil" style="vertical-align: middle; text-align: center; font-size: 1.5rem;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]bhp_id') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]satuan') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modMap, '[ii]spbd_id') ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modMap, '[ii]spbd_qty') ?>
        <span class="no_urut"></span>
    </td>
    <td class="td-kecil" style="vertical-align: middle;" id="item-detail">
        <span><?= $modDetail->bhp_nm ?></span>
    </td>
    <td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<span><?= $modDetail->qty_spb ?></span>
    </td>
    <td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<span><?= $modDetail->qty_termutasi ?></span>
    </td>
    <td class="td-kecil" style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty', ['class'=>'form-control float','style'=>'width:100%; padding:3px;','placeholder'=>'Qty','disabled'=>$disabled]); ?>
    </td>
    <td class="td-kecil" style="vertical-align: middle;  font-size: 1.5rem; text-align: center;">
		<span><?= $modDetail->satuan ?></span>
    </td>
	<td class="td-kecil" style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextarea($modDetail, '[ii]keterangan', ['class'=>'form-control','style'=>'width:100%; height: 55px; font-size:1.1rem; padding:5px;','placeholder'=>'Keterangan','disabled'=>$disabled]); ?>
    </td>
    <td class="td-kecil" style="vertical-align: middle; text-align: center; display: <?= ($disabled)?'none':'' ?>">
        <a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>