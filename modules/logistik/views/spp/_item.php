<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?php echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]bhp_id',['readonly'=>'readonly']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;">
        <?= yii\helpers\Html::activeTextInput($modDetail, '[ii]bhp_nama',['class'=>'form-control','prompt'=>'','style'=>'width:95%','readonly'=>'readonly']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kebutuhan', ['class'=>'form-control float','style'=>'width:80%; text-align:center;','readonly'=>'readonly']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_terpenuhi', ['class'=>'form-control float','style'=>'width:80%; text-align:center;','readonly'=>'readonly']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]sppd_qty', ['class'=>'form-control float','style'=>'width:80%; text-align:center;','readonly'=>'readonly']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]current_stock', ['class'=>'form-control float','style'=>'width:80%; text-align:center;','readonly'=>'readonly']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <span class="satuan"><?= $modDetail->satuan; ?></span>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextarea($modDetail, '[ii]sppd_ket', ['class'=>'form-control','style'=>'width:100%; height: 55px; font-size:1.1rem; padding:5px;']); ?>
    </td>
	<td style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>