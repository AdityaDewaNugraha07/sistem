<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;">
		<?php
		if(!isset($loadJs)){
			echo yii\helpers\Html::activeDropDownList($modSplDetail, '[ii]bhp_id',[],['class'=>'form-control select2','prompt'=>'','style'=>'width:90%']);
		}else{
			echo yii\helpers\Html::activeTextInput($modSplDetail, '[ii]bhp_nm',['class'=>'form-control','style'=>'width:90%','readonly'=>'readonly']);
			echo \yii\bootstrap\Html::activeHiddenInput($modSplDetail, '[ii]bhp_id');
			echo \yii\bootstrap\Html::activeHiddenInput($modSplDetail, '[ii]sppd_id');
		}
		?>
    </td>
    <td style="vertical-align: middle;">
		<center><?= yii\helpers\Html::activeTextInput($modSplDetail, '[ii]spld_qty',['class'=>'form-control float','style'=>'padding:3px;text-align:center;']); ?></center>
    </td>
    <td style="vertical-align: middle; text-align: center;">
		<span class="satuan">
			<?= !empty($modSplDetail->bhp_satuan)?$modSplDetail->bhp_satuan:""; ?>
		</span>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?= \yii\helpers\Html::activeTextInput($modSplDetail, '[ii]spld_harga_estimasi', ['class'=>'form-control money-format','onblur'=>'setSubtotal(this)','style'=>'padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modSplDetail, '[ii]subtotal', ['class'=>'form-control money-format','readonly'=>'readonly','style'=>'padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextarea($modSplDetail, '[ii]spld_keterangan', ['class'=>'form-control','style'=>'height:55px; font-size:1.1rem; padding: 5px;']); ?>
    </td>
	<td style="vertical-align: middle;">
        <?= yii\bootstrap\Html::activeDropDownList($modSplDetail, '[ii]suplier_id', \app\models\MSuplier::getOptionListBHP(),['class'=>'form-control select2','prompt'=>'']) ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>
<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>