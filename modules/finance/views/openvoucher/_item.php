<?php
if(!empty($model->open_voucher_id) && empty($edit)){
    $disabled = true;
    $removebtn = '<center><a class="btn btn-xs grey" disabled="disabled"><i class="fa fa-remove"></i></a></center>';
}else{
    $disabled = false;
    $removebtn = '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>';
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?= \yii\helpers\Html::activeTextarea($modDetail, '[ii]deskripsi', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;','rows'=>'1','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]nominal', ['class'=>'form-control float', 'onblur'=>'total()','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]ppn', ['class'=>'form-control float', 'onblur'=>'total()','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]pph', ['class'=>'form-control float', 'onblur'=>'total()','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>$disabled]); ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]subtotal', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','onblur'=>'total()','disabled'=>true]); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo $removebtn; ?>
    </td>
</tr>
<script>
    
</script>
