<?php
$disabled = (isset($disabled)?TRUE:FALSE);
?>
<tr style="">
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]kas_bon_id',['style'=>'width:50px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?php echo \yii\helpers\Html::activeTextarea($model, '[ii]detail_deskripsi', ['class'=>'form-control','style'=>'height:40px; font-size:1.2rem; padding:3px;','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php echo \yii\helpers\Html::activeTextInput($model, '[ii]detail_nominal', ['class'=>'form-control float','onblur'=>'setTotal()','style'=>'padding:3px;','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle; text-align: center; " class="td-kecil">
		<?php
		if(!empty($model->kas_bon_id)){
			echo "<a onclick='infoKasbon(".$model->kas_bon_id.")' style='font-size: 1.1rem;'>".$model->kode_kasbon."</a>";
		}
		?>
    </td>
	<td class="text-align-center">
		<a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>
	</td>
</tr>