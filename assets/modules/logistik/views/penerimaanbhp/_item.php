<?php $hide = ''; if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_LOGISTIC){ $hide = 'none'; } ?>
<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]bhp_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]spod_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]terima_bhpd_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]terimabhpd_diskon',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]diskon_rp',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]terimabhpd_qty_old',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;">
		<?= $modelDetail->bhp->bhp_nm; ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
		<?= !empty($qty_po)?$qty_po:"-"; ?>
    </td>
    <td style="vertical-align: middle;">
		<center><?= yii\helpers\Html::activeTextInput($modelDetail, '[ii]terimabhpd_qty',['class'=>'form-control float2','style'=>'padding:3px;','onblur'=>'setSubtotal(this)','disabled'=>FALSE, 'disabled'=>$disabled]); ?></center>
    </td>
    <td style="vertical-align: middle;">
		<span class="satuan">
			<?= !empty($modelDetail->bhp_id)?$modelDetail->bhp->bhp_satuan:""; ?>
		</span>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?php // echo \yii\helpers\Html::activeTextInput($modelDetail, '[ii]terimabhpd_harga_display', ['class'=>'form-control money-format','disabled'=>'disabled','onblur'=>'setSubtotal(this)']); ?>
        <?php echo \yii\helpers\Html::activeTextInput($modelDetail, '[ii]terimabhpd_harga', ['class'=>'form-control float2','onblur'=>'setSubtotal(this)','style'=>'padding:3px;display:'.$hide]); ?>
    </td>
    <td style="vertical-align: middle; padding: 3px; width: 130px;">
        <?php // echo \yii\helpers\Html::activeTextInput($modelDetail, '[ii]subtotal_display', ['class'=>'form-control money-format','disabled'=>'disabled','style'=>'padding: 3px;']); ?>
        <?php echo \yii\helpers\Html::activeTextInput($modelDetail, '[ii]subtotal', ['class'=>'form-control float2','disabled'=>'disabled','style'=>'padding: 3px;']); ?>
    </td>
	<td style="vertical-align: middle; text-align: center; padding: 3px;">
		<?php
			$disabled = '';
			$checked = "";
			if(!empty($modelDetail->terima_bhpd_id)){
				$disabled = 'disabled';
				if($modelDetail->pph_peritem != 0){
					$checked = "checked";
				}
			}
		?>
        <span style="float: left;"><input type="checkbox" <?= $checked ?> name="[ii]is_pph_peritem" onclick="setPphPerItem(this)" style="display:<?= $hide ?>"> &nbsp; </span>
        <?= \yii\helpers\Html::activeTextInput($modelDetail, '[ii]pph_peritem', ['class'=>'form-control float2','style'=>'padding: 3px; font-size:1.1rem; width:70%; height: 25px;display:'.$hide,'disabled'=>'disabled','onblur'=>'setPphPerItem(this,this.value)']); ?>
		<span style="font-size: 1.0rem;" id="npwpitemplace"><?= (!empty($modelDetail->npwp)?"NPWP : ".$modelDetail->npwp:"") ?></span>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
        <?php echo \yii\helpers\Html::activeTextarea($modelDetail, '[ii]terimabhpd_keterangan', ['class'=>'form-control','style'=>'height:55px; font-size:1.1rem; padding:3px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>