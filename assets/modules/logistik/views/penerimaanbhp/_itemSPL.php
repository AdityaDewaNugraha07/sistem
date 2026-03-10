<?php $hide = ''; if(Yii::$app->user->identity->pegawai->departement_id == \app\components\Params::DEPARTEMENT_ID_LOGISTIC){ $hide = 'none'; } ?>
<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]bhp_id'); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]terimabhpd_diskon'); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]spld_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]terima_bhpd_id',['style'=>'width:50px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modelDetail, '[ii]terimabhpd_qty_old',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; font-size: 1.2rem;">
		<?= $modelDetail->bhp->bhp_nm; ?>
    </td>
	<td style="vertical-align: middle; text-align: center;">
		<?= !empty($qty_spl)?$qty_spl:"-"; ?>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
		<?= yii\helpers\Html::activeTextInput($modelDetail, '[ii]terimabhpd_qty',['class'=>'form-control float','onblur'=>'setSubtotal(this)','style'=>'padding: 3px; font-size:1.1rem; text-align:center; width:60%; float:left; height: 25px;']); ?>
		<span class="satuan">
			&nbsp; <?= !empty($modelDetail->bhp_id)?$modelDetail->bhp->bhp_satuan:""; ?>
		</span>
    </td>
    <td style="vertical-align: middle; text-align: center; padding: 3px;">
        <?= \yii\helpers\Html::activeTextInput($modelDetail, '[ii]harga_estimasi', ['class'=>'form-control money-format','readonly'=>'readonly','style'=>'padding: 3px; font-size:1.1rem;display:'.$hide]); ?>
    </td>
    <td style="vertical-align: middle; text-align: center; padding: 3px;">
        <?= \yii\helpers\Html::activeTextInput($modelDetail, '[ii]terimabhpd_harga', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.1rem;display:'.$hide,'onblur'=>'setSubtotal(this)']); ?>
    </td>
	<td style="vertical-align: middle; padding: 3px; width: 100px;">
		<?php // echo \yii\helpers\Html::activeTextInput($modelDetail, '[ii]subtotal_display', ['class'=>'form-control money-format','disabled'=>'disabled','style'=>'padding: 3px; font-size:1.1rem; font-weight:bold']); ?>
		<?= \yii\helpers\Html::activeTextInput($modelDetail, '[ii]subtotal', ['class'=>'form-control float','disabled'=>'disabled','style'=>'padding: 3px; font-size:1.1rem; font-weight:bold;display:'.$hide]); ?>
    </td>
    <td style="vertical-align: middle; text-align: left; padding: 3px;">
		<?php
			$disabled = '';
			$checked = "";
			$checked2 = "";
			if(!empty($modelDetail->terima_bhpd_id)){
				$disabled = 'disabled';
				if($modelDetail->ppn_peritem != 0){
					$checked = "checked";
				}
				if($modelDetail->pph_peritem != 0){
					$checked2 = "checked";
				}
			}
		?>
		<span style="float: left;"><input type="checkbox" <?= $checked ?> name="[ii]is_ppn_peritem" onclick="setPpnPerItem(this)" <?= $disabled; ?> style="display:<?= $hide ?>"> &nbsp; </span>
        <?= \yii\helpers\Html::activeTextInput($modelDetail, '[ii]ppn_peritem', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.1rem; width:70%; height: 25px;display:'.$hide,'disabled'=>'disabled','onblur'=>'setPpnPerItem(this,this.value)']); ?>
    </td>
	<td style="vertical-align: middle; text-align: left; padding: 3px;">
        <span style="float: left;"><input type="checkbox" <?= $checked2 ?> name="[ii]is_pph_peritem" onclick="setPphPerItem(this)" <?= $disabled; ?> style="display:<?= $hide ?>"> &nbsp; </span>
        <?= \yii\helpers\Html::activeTextInput($modelDetail, '[ii]pph_peritem', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.1rem; width:70%; height: 25px;display:'.$hide,'disabled'=>'disabled','onblur'=>'setPphPerItem(this,this.value)']); ?>
		<span style="font-size: 1.0rem;" id="npwpitemplace"><?= (!empty($modelDetail->npwp)?"NPWP : ".$modelDetail->npwp:"") ?></span>
    </td>
	<td style="vertical-align: middle; text-align: center; padding: 3px;">
		<?php
			$content = [];
			if(!empty($modelDetail->suplier_id)){
				$content[$modelDetail->suplier_id] = $modelDetail->suplier->suplier_nm;
			}
		?>
		<?= yii\bootstrap\Html::activeDropDownList($modelDetail, '[ii]suplier_id', $content,['class'=>'form-control select2','style'=>'padding:3px; font-size:1.1rem; float:left;']); ?>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
        <?= \yii\helpers\Html::activeTextarea($modelDetail, '[ii]terimabhpd_keterangan', ['prompt'=>'','class'=>'form-control','style'=>'height:50px; padding: 3px; font-size:1.1rem;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red" onclick="cancelItemThis(this);" id="btn-cancel-item"><i class="fa fa-remove"></i></a>
		<?php
		if(!empty($modelDetail->terima_bhpd_id)){
			$sql = "SELECT * FROM t_retur_bhp WHERE terima_bhpd_id = ".$modelDetail->terima_bhpd_id;
			$mod = Yii::$app->db->createCommand($sql)->queryOne();
			if(!empty($mod)){ ?>
				<a onclick="infoReturBHP(<?= $mod['retur_bhp_id'] ?>);" class="blue-steel" style="font-size: 1rem"><?= $mod['kode']; ?></a>
			<?php }else{ ?>
				<a href="javascript:void(0);" onclick="returBHP(<?= $modelDetail->terima_bhpd_id ?>);" class="btn blue-steel btn-xs btn-outline" style="font-size: 0.9rem"><i class="fa fa-share"></i> <?= Yii::t('app', 'Retur BHP'); ?></a><br>
				<!--<a href="javascript:void(0);" onclick="abortItem(<?php // echo $modelDetail->terima_bhpd_id ?>);" class="btn red-flamingo btn-xs btn-outline" style="font-size: 0.9rem"><i class="fa fa-close"></i> <?= Yii::t('app', 'Abort'); ?></a>-->
			<?php }
		}
			?>
    </td>
</tr>